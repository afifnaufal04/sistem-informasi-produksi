<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pengambilan;
use App\Models\DetailPengambilan;
use App\Models\DetailPengiriman;
use App\Models\Kendaraan;
use App\Models\User;
use App\Models\ProgresProduksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class PengambilanController extends Controller
{
    public function index()
    {
        $pengambilan = Pengambilan::with([
            'detailPengambilan.detailPengiriman.produksi.barang',
            'detailPengambilan.detailPengiriman.supplier',
            'kendaraan',
            'supir',
            'qc'
        ])
        ->orderBy('created_at', 'desc')->get();

        return view('keprod.pengambilan', compact('pengambilan'));
    }

    public function create()
    {
        // Ambil barang yang lolos QC dan belum diambil semua
        $detailPengiriman = DetailPengiriman::with([
            'produksi.barang',
            'supplier',
            'subProses',
            'qcHasil',
            'detailPengambilan',
            'pengiriman'
        ])
        
        ->where('status_pengiriman', 'diterima')
        ->whereHas('qcHasil', function($query) {
            $query->where('jumlah_lolos', '>', 0);
        })
        ->whereHas('pengiriman', function ($query) {
            $query->where('tipe_pengiriman', 'eksternal');
        })
        ->get()
        ->map(function($item) {
            $totalDiambil = $item->detailPengambilan->sum('jumlah_diambil');
            $lolosQc = $item->qcHasil->jumlah_lolos ?? 0;
            $dapatDiambil = $lolosQc - $totalDiambil;
            
            return [
                'detail_pengiriman_id' => $item->detail_pengiriman_id,
                'nama_barang' => $item->produksi->barang->nama_barang ?? '-',
                'sub_proses' => $item->subProses->nama_sub_proses ?? '-',
                'supplier' => $item->supplier->name ?? '-',
                'supplier_id' => $item->supplier_id,
                'lolos_qc' => $lolosQc,
                'sudah_diambil' => $totalDiambil,
                'dapat_diambil' => $dapatDiambil,
            ];
        })
        ->filter(fn($item) => $item['dapat_diambil'] > 0);

        $supir = User::where('role', 'supir')->get();
        $qc = User::where('role', 'qc')->get();
        $kendaraan = Kendaraan::all();

        return view('keprod.createPengambilan', compact('detailPengiriman', 'supir', 'qc', 'kendaraan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'detail_pengiriman_id' => 'required|array',
            'detail_pengiriman_id.*' => 'required|exists:detail_pengiriman,detail_pengiriman_id',
            'jumlah_diambil' => 'required|array',
            'jumlah_diambil.*' => 'required|integer|min:1',
            'harga_produksi' => 'required|array',
            'harga_produksi.*' => 'required|numeric|min:0',
            'qc_id' => 'required|exists:users,id',
            'supir_id' => 'required|exists:users,id',
            'kendaraan_id' => 'required|exists:kendaraan,kendaraan_id',
            'tanggal_pengambilan' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            // Validasi
            $errorMessages = [];

            foreach ($request->detail_pengiriman_id as $i => $detailPengirimanId) {
                $jumlahDiambil = $request->jumlah_diambil[$i];
                
                $detailPengiriman = DetailPengiriman::with(['qcHasil', 'detailPengambilan'])
                    ->findOrFail($detailPengirimanId);

                $lolosQc = $detailPengiriman->qcHasil->jumlah_lolos ?? 0;
                $totalDiambil = $detailPengiriman->detailPengambilan->sum('jumlah_diambil');
                $dapatDiambil = $lolosQc - $totalDiambil;

                if ($jumlahDiambil > $dapatDiambil) {
                    $errorMessages[] = "Item #" . ($i + 1) . ": Jumlah melebihi yang tersedia ($dapatDiambil)";
                }
            }

            if (!empty($errorMessages)) {
                DB::rollBack();
                return back()->withErrors(['validation' => implode('<br>', $errorMessages)])->withInput();
            }

            // Buat pengambilan
            $pengambilan = Pengambilan::create([
                'kendaraan_id' => $request->kendaraan_id,
                'supir_id' => $request->supir_id,
                'qc_id' => $request->qc_id,
                'tanggal_pengambilan' => $request->tanggal_pengambilan,
                'status' => 'Dijadwalkan',
            ]);

            // Buat detail
            foreach ($request->detail_pengiriman_id as $i => $detailPengirimanId) {
                $jumlahDiambil = $request->jumlah_diambil[$i];
                $hargaProduksi = $request->harga_produksi[$i];
                $totalPembayaran = $jumlahDiambil * $hargaProduksi;

                // Ambil detail pengiriman dengan relasi
                $detailPengiriman = DetailPengiriman::with(['produksi', 'subProses'])
                ->findOrFail($detailPengirimanId);

                // Buat detail pengambilan
                DetailPengambilan::create([
                    'pengambilan_id' => $pengambilan->pengambilan_id,
                    'detail_pengiriman_id' => $detailPengirimanId,
                    'jumlah_diambil' => $jumlahDiambil,
                    'harga_produksi' => $hargaProduksi,
                    'total_pembayaran' => $totalPembayaran,
                    'status_pembayaran' => 'Belum Dibayar',
                ]);

                 // ========================================
                // UPDATE PROGRES PRODUKSI
                // ========================================
                $produksiId = $detailPengiriman->produksi_id;
                $subProsesId = $detailPengiriman->sub_proses_id;

                // Cari progres produksi yang sesuai
                $progres = ProgresProduksi::where('produksi_id', $produksiId)
                    ->where('sub_proses_id', $subProsesId)
                    ->first();

                if ($progres) {
                    // Pindahkan dari dlm_proses ke sdh_selesai (jumlah_diambil)
                    $progres->dlm_proses -= $jumlahDiambil;
                    $progres->sdh_selesai += $jumlahDiambil;
                    
                    // Update jumlah total (seharusnya tetap sama, hanya perpindahan)
                    $progres->jumlah = $progres->dlm_proses + $progres->sdh_selesai;
                    
                    $progres->save();
                }
            }

            DB::commit();
            return redirect()->route('keprod.pengambilan.index')
                ->with('success', 'Pengambilan berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal: ' . $e->getMessage()])->withInput();
        }
    }

    ///////////////////////////////////////////////////////////////////////////////////////
    //                                 UNTUK QC                                          //
    ///////////////////////////////////////////////////////////////////////////////////////

    public function indexForQC()
    {
        $pengambilanList = Pengambilan::with([
            'detailPengambilan.detailPengiriman.produksi.barang',
            'detailPengambilan.detailPengiriman.supplier',
            'supir',
            'kendaraan'
        ])
        ->where('qc_id', Auth::id())
        ->where('status', 'Dijadwalkan')
        ->orderBy('created_at', 'desc')
        ->get();

        return view('qc.pengambilan', compact('pengambilanList'));
    }

    public function konfirmasi($pengambilan_id)
    {
        DB::beginTransaction();
        try {
            $pengambilan = Pengambilan::findOrFail($pengambilan_id);

            if ($pengambilan->qc_id !== Auth::id()) {
                return back()->withErrors(['error' => 'Anda tidak berhak!']);
            }

            $pengambilan->update([
                'status' => 'Diambil',
            ]);

            DB::commit();
            return back()->with('success', 'Konfirmasi berhasil! Menunggu supir.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    ///////////////////////////////////////////////////////////////////////////////////////
    //                                 UNTUK Supir                                       //
    ///////////////////////////////////////////////////////////////////////////////////////

    public function indexForSupir()
    {
        $pengambilan = Pengambilan::with([
            'detailPengambilan.detailPengiriman.produksi.barang',
            'detailPengambilan.detailPengiriman.supplier',
            'kendaraan',
            'qc'
        ])
        ->where('supir_id', Auth::id())
        ->orderBy('created_at', 'desc')
        ->get();

        return view('supir.pengambilan', compact('pengambilan'));
    }

    // Halaman konfirmasi jumlah barang
    public function formKonfirmasi($pengambilan_id)
    {
        $pengambilan = Pengambilan::with([
            'detailPengambilan.detailPengiriman.produksi.barang',
            'detailPengambilan.detailPengiriman.supplier'
        ])->findOrFail($pengambilan_id);

        if ($pengambilan->status !== 'Menunggu Konfirmasi Supir') {
            return redirect()->route('supir.pengambilan.index')
                ->withErrors(['error' => 'Pengambilan tidak dalam status yang tepat!']);
        }

        return view('supir.pengambilan.konfirmasi', compact('pengambilan'));
    }

    // Simpan konfirmasi jumlah
    public function konfirmasiSupir(Request $request, $pengambilan_id)
    {
        $request->validate([
            'jumlah_dikonfirmasi' => 'required|array',
            'jumlah_dikonfirmasi.*' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $pengambilan = Pengambilan::with('detailPengambilan')->findOrFail($pengambilan_id);

            if ($pengambilan->status !== 'Menunggu Konfirmasi Supir') {
                return back()->withErrors(['error' => 'Status tidak valid!']);
            }

            // Update jumlah dikonfirmasi
            foreach ($pengambilan->detailPengambilan as $i => $detail) {
                $jumlahKonfirmasi = $request->jumlah_dikonfirmasi[$i];
                
                if ($jumlahKonfirmasi > $detail->jumlah_diambil) {
                    return back()->withErrors(['error' => 'Jumlah tidak boleh melebihi yang diambil!']);
                }

                $detail->update(['jumlah_dikonfirmasi' => $jumlahKonfirmasi]);
            }

            $pengambilan->update([
                'status' => 'Menunggu QC Lagi',
                'waktu_konfirmasi_supir' => now(),
            ]);

            DB::commit();
            return redirect()->route('supir.pengambilan.index')
                ->with('success', 'Konfirmasi berhasil!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    // Mulai pengambilan
    public function mulai($pengambilan_id)
    {
        DB::beginTransaction();
        try {
            $pengambilan = Pengambilan::findOrFail($pengambilan_id);

            if ($pengambilan->status !== 'Siap Diambil') {
                return back()->withErrors(['error' => 'Barang belum siap!']);
            }

            $pengambilan->update([
                'status' => 'Dalam Perjalanan',
                'waktu_mulai' => now(),
            ]);

            DB::commit();
            return redirect()->route('supir.pengambilan.perjalanan', $pengambilan_id);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    // Halaman perjalanan
    public function perjalanan($pengambilan_id)
    {
        $pengambilan = Pengambilan::with([
            'detailPengambilan.detailPengiriman.produksi.barang',
            'detailPengambilan.detailPengiriman.supplier',
            'kendaraan',
            'qc'
        ])->findOrFail($pengambilan_id);

        return view('supir.pengambilan.perjalanan', compact('pengambilan'));
    }

    // Selesai (sampai pabrik)
    public function selesai($pengambilan_id)
    {
        DB::beginTransaction();
        try {
            $pengambilan = Pengambilan::findOrFail($pengambilan_id);

            if ($pengambilan->status !== 'Diambil') {
                return back()->withErrors(['error' => 'Status tidak valid!']);
            }

            $pengambilan->update([
                'status' => 'Selesai',
                'waktu_selesai' => now(),
                'tanggal_selesai' => now(),
            ]);

            DB::commit();
            return redirect()->route('supir.pengambilan
            .index')
                ->with('success', 'Pengambilan selesai!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal: ' . $e->getMessage()]);
        }
    }

    ///////////////////////////////////////////////////////////////////////////////////////
    //                                 UNTUK Purchasing                                  //
    ///////////////////////////////////////////////////////////////////////////////////////

    public function DaftarPembayaran()
    {
        $detailPengambilan = DetailPengambilan::with([
            'pengambilan.supir',
            'detailPengiriman.produksi.barang',
            'detailPengiriman.supplier'
        ])
        ->whereHas('detailPengiriman.supplier', function($query) {
            $query->where('tipe_supplier', 'eksternal');
        })
        ->where('status_bayar', 'Belum Dibayar')->orderBy('created_at', 'desc')->get();

        return view('purchasing.daftarpembayaran', compact('detailPengambilan'));
    }

    public function KonfirmasiPembayaran($detail_pengambilan_id)
    {
        $detailPengambilan = DetailPengambilan::with([
            'pengambilan.supir',
            'detailPengiriman.produksi.barang',
            'detailPengiriman.supplier'
        ])->findOrFail($detail_pengambilan_id);
        
        $detailPengambilan->update([
            'status_bayar' => 'Lunas',
        ]);
        
        return redirect()->route('purchasing.pembayaran.index')
             ->with('success', 'Pembayaran berhasil dikonfirmasi!');
    }
}
