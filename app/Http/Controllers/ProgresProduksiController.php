<?php

namespace App\Http\Controllers;

use App\Models\GudangQcGagal;
use App\Models\PemesananBarang;
use App\Models\Pemesanan;
use App\Models\ProgresProduksi;
use App\Models\Barang;
use App\Models\SubProses;
use App\Models\Proses;
use App\Models\HistoryPemindahan;
use App\Models\Produksi;
use App\Models\Packing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ProgresProduksiController extends Controller
{
    /**
     * Fungsi utama untuk memindahkan barang dari satu sub-proses ke sub-proses berikutnya.
     */
    public function pindahProses(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pemesanan_barang_id' => 'required|exists:pemesanan_barang,pemesanan_barang_id',
            'sub_proses_asal_id' => 'nullable|exists:sub_proses,sub_proses_id', // Bisa null untuk proses pertama kali
            'sub_proses_tujuan_id' => 'required|exists:sub_proses,sub_proses_id',
            'jumlah' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pemesananBarangId = $request->input('pemesanan_barang_id');
        $asalId = $request->input('sub_proses_asal_id');
        $tujuanId = $request->input('sub_proses_tujuan_id');
        $jumlah = $request->input('jumlah');

        try {
            DB::beginTransaction();

            $pemesananBarang = PemesananBarang::findOrFail($pemesananBarangId);

            // Jika ini adalah input pertama kali ke proses produksi
            if (is_null($asalId)) {
                $totalDiProduksi = $pemesananBarang->progresProduksi()->sum('jumlah');
                if (($totalDiProduksi + $jumlah) > $pemesananBarang->jumlah_pemesanan) {
                    throw new \Exception('Jumlah yang dimasukkan melebihi total pesanan.');
                }
                 // Set status ke 'produksi' jika ini input pertama
                if($pemesananBarang->status == 'pending'){
                    $pemesananBarang->status = 'produksi';
                    $pemesananBarang->save();
                }
            } else {
                // Mengurangi jumlah dari proses asal
                $progresAsal = ProgresProduksi::where('pemesanan_barang_id', $pemesananBarangId)
                    ->where('sub_proses_id', $asalId)
                    ->first();

                if (!$progresAsal || $progresAsal->jumlah < $jumlah) {
                    throw new \Exception('Jumlah barang di proses asal tidak mencukupi.');
                }

                $progresAsal->jumlah -= $jumlah;

                if ($progresAsal->jumlah > 0) {
                    $progresAsal->save();
                } else {
                    $progresAsal->delete(); // Hapus jika jumlah jadi 0
                }
            }

            // Menambah atau membuat entri baru untuk proses tujuan
            $progresTujuan = ProgresProduksi::firstOrNew([
                'pemesanan_barang_id' => $pemesananBarangId,
                'sub_proses_id' => $tujuanId,
            ]);

            $progresTujuan->jumlah = ($progresTujuan->jumlah ?? 0) + $jumlah;
            $progresTujuan->save();
            
            // LOGIKA KHUSUS SETELAH FINISHING (QC & PACKING)
            $subProsesTujuan = SubProses::find($tujuanId);

            // Asumsi sub-proses setelah finishing diberi nama "Lolos QC" atau "Packing"
            if (str_contains(strtolower($subProsesTujuan->nama_sub_proses), 'lolos qc')) {
                $pemesananBarang->increment('jumlah_stok_acc', $jumlah);
            }
            
            if (str_contains(strtolower($subProsesTujuan->nama_sub_proses), 'packing')) {
                $pemesananBarang->increment('jumlah_selesai_packing', $jumlah);
            }

            // Cek apakah proses untuk barang pesanan ini sudah selesai
            //$this->cekStatusSelesai($pemesananBarang->fresh());

            DB::commit();

            return response()->json(['message' => 'Berhasil memindahkan barang.', 'data' => $progresTujuan], 200);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Tampilkan halaman pelacakan progres untuk PPIC
     */
    public function index()
    {
        $role = Auth::user()->role;

        // Ambil semua produksi beserta progres dan barang
        $produksiList = Produksi::with(['barang', 'progresProduksi.subProses'])->get();

        // Ambil semua sub proses berurutan
        $subProsesList = SubProses::orderBy('proses_id')
            ->where('nama_sub_proses', '!=', 'packing')
            ->orderBy('urutan')
            ->get();

        // Ambil sub proses terakhir
        $subProsesTerakhir = $subProsesList->where('nama_sub_proses', 'wax')->first();

        $matrix = [];

        foreach ($produksiList as $produksi) {
            $row = [
                'produksi_id' => $produksi->produksi_id,
                'nama_barang' => $produksi->barang->nama_barang ?? '-',
                'jumlah_produksi' => $produksi->jumlah_produksi ?? 0,
                'jenis_barang' => $produksi->barang->jenis_barang ?? '-',
                'status_produksi' => $produksi->status_produksi ?? '-',
                'no_SPK_kwas' => $produksi->pemesananBarang->no_SPK_kwas ?? '-',
                'subproses' => []
            ];

            $jumlahSiapGudang = 0;

            foreach ($subProsesList as $sp) {
                // Ambil progres untuk sub proses ini
                $progres = $produksi->progresProduksi
                            ->where('sub_proses_id', $sp->sub_proses_id)
                            ->first();

                $jumlahTotal = $progres->jumlah ?? 0;
                $dlmProses = $progres->dlm_proses ?? 0;
                $sdhSelesai = $progres->sdh_selesai ?? 0;

                // Simpan jumlah total untuk ditampilkan di card
                $row['subproses'][$sp->sub_proses_id] = $jumlahTotal;

                // Cek jika ini sub proses terakhir (wax)
                if ($subProsesTerakhir && $sp->sub_proses_id == $subProsesTerakhir->sub_proses_id) {
                    // Yang bisa dipindah ke gudang HANYA yang sdh_selesai
                    $jumlahSiapGudang = $sdhSelesai;
                }
            }

            // Tambahkan informasi siap gudang
            $row['jumlah_siap_gudang'] = $jumlahSiapGudang;
            $row['bisa_pindah_gudang'] = $jumlahSiapGudang > 0;

            $matrix[] = $row;
        }

        // Tentukan tampilan sesuai role login
        $view = match ($role) {
            'ppic'   => 'ppic.pelacakan_progres',
            'keprod' => 'keprod.pelacakan_progres',
            'qc'     => 'qc.pelacakan_progres',
            default  => abort(403)
        };

        return view($view, compact('matrix', 'subProsesList', 'subProsesTerakhir'));
    }

    public function createProduksi()
    {
        // Ambil data barang untuk dropdown
        $barangs = Barang::all(); 

        return view('keprod.createProduksi', compact('barangs'));
    }

    public function storeProduksi(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,barang_id',
            'jumlah_produksi' => 'required|integer|min:1'
        ]);

        Produksi::create([
            'barang_id' => $request->barang_id,
            'pemesanan_barang_id' => null,
            'jumlah_produksi' => $request->jumlah_produksi, // wajib ikut disimpan
            'status_produksi' => 'Pending',
        ]);

        return redirect()->route('keprod.progres.index')
            ->with('success', 'Produksi berhasil ditambahkan!');
    }

    public function pindahKeGudang(Request $request, $produksi_id)
    {
        $request->validate([
            'jumlah_pindah' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            $produksi = Produksi::with(['barang', 'progresProduksi'])->findOrFail($produksi_id);

            // ========================================
            // AMBIL SUB PROSES ACUAN (WAX)
            // ========================================
            $subProsesAcuan = SubProses::where('nama_sub_proses', 'wax')->first();

            if (!$subProsesAcuan) {
                return back()->withErrors(['error' => 'Sub proses acuan (Wax) tidak ditemukan!']);
            }

            // ========================================
            // CEK JUMLAH DI SUB PROSES ACUAN
            // ========================================
            $progresAcuan = $produksi->progresProduksi
                ->where('sub_proses_id', $subProsesAcuan->sub_proses_id)
                ->first();

            // Validasi: apakah ada progres di sub proses wax?
            if (!$progresAcuan) {
                return back()->withErrors([
                    'error' => 'Tidak ada barang di sub proses Wax!'
                ]);
            }

            // Validasi: apakah ada barang yang sudah selesai?
            if ($progresAcuan->sdh_selesai <= 0) {
                return back()->withErrors([
                    'error' => 'Tidak ada barang yang sudah selesai di sub proses Wax! (Semua masih dalam proses)'
                ]);
            }

            // Validasi: jumlah tidak boleh melebihi yang sudah selesai
            if ($request->jumlah_pindah > $progresAcuan->sdh_selesai) {
                return back()->withErrors([
                    'error' => "Jumlah melebihi barang yang tersedia! Tersedia: {$progresAcuan->sdh_selesai}, diminta: {$request->jumlah_pindah}"
                ]);
            }

            // ========================================
            // TAMBAH STOK GUDANG
            // ========================================
            $produksi->barang->increment('stok_gudang', $request->jumlah_pindah);

            // ========================================
            // KURANGI JUMLAH PRODUKSI
            // ========================================
            $produksi->decrement('jumlah_produksi', $request->jumlah_pindah);

            // ========================================
            // UPDATE PROGRES DI SUB PROSES ACUAN
            // ========================================
            $progresAcuan->sdh_selesai -= $request->jumlah_pindah;
            $progresAcuan->jumlah = $progresAcuan->dlm_proses + $progresAcuan->sdh_selesai;

            // Jika masih ada sisa, update; jika tidak, hapus record
            if ($progresAcuan->jumlah > 0) {
                $progresAcuan->save();
            } else {
                $progresAcuan->delete();
            }

            // ========================================
            // UPDATE STATUS PRODUKSI
            // ========================================
            $totalProgres = ProgresProduksi::where('produksi_id', $produksi_id)->sum('jumlah');
            
            if ($totalProgres == 0) {
                $produksi->update(['status_produksi' => 'Selesai']);
            }

            // ========================================
            // CATAT HISTORY
            // ========================================
            HistoryPemindahan::create([
                'produksi_id' => $produksi_id,
                'barang_id' => $produksi->barang_id,
                'jumlah' => $request->jumlah_pindah,
                'keterangan' => 'Pindah dari produksi ke gudang',
                'tanggal_pemindahan' => now(),
            ]);

            DB::commit();
            return back()->with('success', "Berhasil memindahkan {$request->jumlah_pindah} pcs ke gudang!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memindahkan barang: ' . $e->getMessage()]);
        }
    }


    public function ProsesPenyelesaianPemesanan()
{
    // Ambil data pemesanan beserta barang dan total selesai packing
    $pemesanans = Pemesanan::with([
        'pembeli',
        'pemesananBarang.barang',
        'pemesananBarang' => function ($query) {
            $query->withSum('packing', 'jumlah_selesai_packing');
        }
    ])->get();

    // Loop setiap pemesanan
    foreach ($pemesanans as $pemesanan) {

        // Loop setiap barang dalam pemesanan
        foreach ($pemesanan->pemesananBarang as $pb) {

            // Total real selesai packing
            $packingSelesai = $pb->packing_sum_jumlah_selesai_packing ?? 0;

            // Simpan untuk ditampilkan di view
            $pb->packing_selesai = $packingSelesai;

            // Hitung progress per barang
            if ($pb->jumlah_pemesanan > 0) {
                $progressBarang = ($packingSelesai / $pb->jumlah_pemesanan) * 100;
                $pb->progress_barang = min(100, round($progressBarang));
            } else {
                $pb->progress_barang = 0;
            }

        }
    }

    return view('ppic.progresPenyelesaianPemesanan', compact('pemesanans'));
}




    // Menu untuk keprod history pemindahan barang
    public function historyPemindahan(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $query = HistoryPemindahan::with(['produksi.barang', 'barang'])
            ->orderBy('tanggal_pemindahan', 'desc');

        // 🔹 Filter Bulan
        if ($bulan) {
            $query->whereMonth('tanggal_pemindahan', $bulan);
        }

        // 🔹 Filter Tahun
        if ($tahun) {
            $query->whereYear('tanggal_pemindahan', $tahun);
        }

        $historyPemindahans = $query->get();

        return view('keprod.historyPemindahan', compact(
            'historyPemindahans',
            'bulan',
            'tahun'
        ));
    }
}