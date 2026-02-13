<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pengambilan;
use App\Models\DetailPengambilan;
use App\Models\DetailPengiriman;
use App\Models\Kendaraan;
use App\Models\ProgresProduksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PengambilanInternalController extends Controller
{

    public function create()
    {
        // Ambil barang dari pengiriman internal yang lolos QC dan belum diambil semua
        $detailPengiriman = DetailPengiriman::with([
            'produksi.barang',
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
            $query->where('tipe_pengiriman', 'internal');
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
                'lolos_qc' => $lolosQc,
                'sudah_diambil' => $totalDiambil,
                'dapat_diambil' => $dapatDiambil,
                'no_pengiriman' => $item->pengiriman->no_pengiriman ?? '-',
            ];
        })
        ->filter(fn($item) => $item['dapat_diambil'] > 0);

        return view('keprod.createPengambilanInternal', compact('detailPengiriman'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'detail_pengiriman_id' => 'required|array',
            'detail_pengiriman_id.*' => 'required|exists:detail_pengiriman,detail_pengiriman_id',
            'jumlah_diambil' => 'required|array',
            'jumlah_diambil.*' => 'required|integer|min:1',
            'tanggal_pengambilan' => 'required|date',
        ]);

        DB::beginTransaction();
        try {
            // Validasi
            $errorMessages = [];

            foreach ($request->detail_pengiriman_id as $i => $detailPengirimanId) {
                $jumlahDiambil = $request->jumlah_diambil[$i];
                
                $detailPengiriman = DetailPengiriman::with(['qcHasil', 'detailPengambilan', 'pengiriman'])
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

            // Buat pengambilan internal
            $pengambilan = Pengambilan::create([
                'tanggal_pengambilan' => $request->tanggal_pengambilan,
                'tanggal_selesai' => $request->tanggal_pengambilan,
                'status' => 'Selesai',
            ]);

            // Buat detail (tanpa harga dan pembayaran untuk internal)
            foreach ($request->detail_pengiriman_id as $i => $detailPengirimanId) {
                
                // Ambil detail pengiriman dengan relasi
                $detailPengiriman = DetailPengiriman::with(['produksi', 'subProses'])
                ->findOrFail($detailPengirimanId);

                DetailPengambilan::create([
                    'pengambilan_id' => $pengambilan->pengambilan_id,
                    'detail_pengiriman_id' => $detailPengirimanId,
                    'jumlah_diambil' => $request->jumlah_diambil[$i],
                    'harga_produksi' => 0, // Internal tidak ada harga
                    'total_pembayaran' => 0, // Internal tidak ada pembayaran
                    'status_pembayaran' => 'Tidak Ada', // Status khusus internal
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
                    // Pindahkan dari dlm_proses ke sdh_selesai
                    $progres->dlm_proses -= $jumlahDiambil;
                    $progres->sdh_selesai += $jumlahDiambil;
                    
                    // Update jumlah total (seharusnya tetap sama, hanya perpindahan)
                    $progres->jumlah = $progres->dlm_proses + $progres->sdh_selesai;
                    
                    $progres->save();
                }
            }

            DB::commit();
            return redirect()->route('keprod.pengambilan.index')
                ->with('success', 'Pengambilan internal berhasil dibuat!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal: ' . $e->getMessage()])->withInput();
        }
    }
}