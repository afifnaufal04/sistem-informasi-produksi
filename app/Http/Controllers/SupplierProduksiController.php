<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DetailPengiriman;
use App\Models\Pengiriman;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\QcHasil;

class SupplierProduksiController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $filter = $request->qc_filter ?? 'belum'; // belum | selesai | all

        $produksiList = DetailPengiriman::with([
            'pengiriman.supir',
            'produksi.barang',
            'subProses',
            'qcHasil',
            'detailPengambilan'
        ])
        ->where('supplier_id', $user->id)
        ->where('status_pengiriman', 'Diterima')
        ->orderBy('waktu_diterima', 'desc')
        ->get()
        ->map(function($item) {
            // Hitung total yang sudah diambil
            $totalDiambil = $item->detailPengambilan->sum('jumlah_diambil');
            
            // Data QC
            $qcLolos = $item->qcHasil->jumlah_lolos ?? 0;
            $qcGagal = $item->qcHasil->jumlah_gagal ?? 0;
            $qcReject = $item->qcHasil->jumlah_reject ?? 0;
            
            $jumlahPengiriman = $item->jumlah_pengiriman - $qcReject;
            
            // Jumlah yang dapat diambil = jumlah lolos qc - total sudah diambil
            $dapatDiambil = $qcLolos - $totalDiambil;
            
            return [
                'detail_pengiriman_id' => $item->detail_pengiriman_id,
                'nama_barang' => $item->produksi->barang->nama_barang ?? '-',
                'sub_proses' => $item->subProses->nama_sub_proses ?? '-',
                'jumlah_pengiriman' => $jumlahPengiriman,
                'status_pengerjaan' => $item->status_pengerjaan,
                'jumlah_selesai' => $item->jumlah_selesai,
                'lolos_qc' => $qcLolos,
                'gagal_qc' => $qcGagal,
                'qc_reject' => $item->qcHasil->jumlah_reject ?? 0,
                'dapat_diambil' => $dapatDiambil,
                'sudah_diambil' => $totalDiambil,
                'waktu_diterima' => $item->waktu_diterima,
                'tanggal_selesai' => $item->pengiriman->tanggal_selesai ?? null,
            ];
        });

        // ============================
        // FILTER DATA
        // ============================
        if ($filter === 'belum') {
            $produksiList = $produksiList->filter(function ($item) {
                return $item['sudah_diambil'] < $item['jumlah_pengiriman'];
            });
        } elseif ($filter === 'selesai') {
            $produksiList = $produksiList->filter(function ($item) {
                return $item['sudah_diambil'] >= $item['jumlah_pengiriman'];
            });
        }
        // kalau 'all' → tidak difilter

        return view('supplier.produksi', compact('produksiList', 'filter'));
    }

    // Update status pengerjaan dan jumlah selesai
    public function update(Request $request, $id)
    {
        $request->validate([
            'status_pengerjaan' => ['required', 'in:Dalam Pengerjaan,Selesai,Perlu Perbaikan'], // Gunakan array dan hapus spasi setelah koma
            'jumlah_selesai' => 'required|integer|min:0'
        ]);

        $user = Auth::user();

        DB::beginTransaction();
        try {
            $detail = DetailPengiriman::with('qcHasil')->findOrFail($id);

            // Validasi: supplier hanya bisa edit miliknya sendiri
            if ($detail->supplier_id !== $user->id) {
                return back()->withErrors(['error' => 'Anda tidak berhak mengubah data ini!']);
            }

            // Validasi: jumlah selesai tidak boleh melebihi jumlah pengiriman
            if ($request->jumlah_selesai > $detail->jumlah_pengiriman) {
                return back()->withErrors(['error' => 'Jumlah selesai tidak boleh melebihi jumlah pengiriman!']);
            }

            // Hitung total yang sudah diambil
            $totalDiambil = $detail->detailPengambilan->sum('jumlah_diambil');

            // Validasi: jumlah selesai tidak boleh kurang dari yang sudah diambil
            if ($request->jumlah_selesai < $totalDiambil) {
                return back()->withErrors(['error' => "Jumlah selesai tidak boleh kurang dari yang sudah diambil ($totalDiambil)!"]);
            }

            // Update data
            $detail->update([
                'status_pengerjaan' => $request->status_pengerjaan,
                'jumlah_selesai' => $request->jumlah_selesai
            ]);

            if ($detail->qcHasil) {
                $detail->qcHasil->update([
                    'tombol_aksi' => true
                ]);
            }

            DB::commit();
            return back()->with('success', 'Status pengerjaan berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mengupdate: ' . $e->getMessage()]);
        }
    }

    public function indexForQc(Request $request)
    {
        $user = Auth::user();
        $filter = $request->qc_filter ?? 'belum'; // belum | selesai | all

        $produksiList = DetailPengiriman::with([
                'pengiriman.supir',
                'produksi.barang',
                'subProses',
                'supplier',
                'qcHasil',
                'detailPengambilan'
            ])
            ->whereHas('pengiriman', function ($q) use ($user) {
                $q->where('qc_id', $user->id);
            })
            ->where('status_pengiriman', 'Diterima')
            ->orderBy('waktu_diterima', 'desc')
            ->get()
            ->map(function ($item) {
                // Total sudah diambil
                $totalDiambil = $item->detailPengambilan->sum('jumlah_diambil');

                // Data QC
                $qcLolos  = $item->qcHasil->jumlah_lolos ?? 0;
                $qcGagal  = $item->qcHasil->jumlah_gagal ?? 0;
                $qcReject = $item->qcHasil->jumlah_reject ?? 0;

                $jumlahPengiriman = $item->jumlah_pengiriman - $qcReject;
                $dapatDiambil = $qcLolos - $totalDiambil;

                return [
                    'detail_pengiriman_id' => $item->detail_pengiriman_id,
                    'supplier_name'        => $item->supplier->name ?? '-',
                    'nama_barang'          => $item->produksi->barang->nama_barang ?? '-',
                    'sub_proses'           => $item->subProses->nama_sub_proses ?? '-',
                    'jumlah_pengiriman'    => $jumlahPengiriman,
                    'status_pengerjaan'    => $item->status_pengerjaan,
                    'jumlah_selesai'       => $item->jumlah_selesai,
                    'lolos_qc'             => $qcLolos,
                    'gagal_qc'             => $qcGagal,
                    'qc_reject'            => $qcReject,
                    'dapat_diambil'        => $dapatDiambil,
                    'sudah_diambil'        => $totalDiambil,
                    'waktu_diterima'       => $item->waktu_diterima,
                    'tanggal_selesai'      => $item->pengiriman->tanggal_selesai ?? null,
                    'tombol_aksi'         => $item->qcHasil->tombol_aksi?? true,
                ];
            });

        // ============================
        // FILTER DATA
        // ============================
        if ($filter === 'belum') {
            $produksiList = $produksiList->filter(function ($item) {
                return $item['sudah_diambil'] < $item['jumlah_pengiriman'];
            });
        } elseif ($filter === 'selesai') {
            $produksiList = $produksiList->filter(function ($item) {
                return $item['sudah_diambil'] >= $item['jumlah_pengiriman'];
            });
        }
        // kalau 'all' → tidak difilter

        return view($user->role.'.produksi', compact('produksiList', 'filter'));
    }

    // Method untuk Nilai Kualitas (Lolos & Gagal QC saja)
    public function nilaiKualitas(Request $request, $detail_pengiriman_id)
    {
        $request->validate([
            'jumlah_lolos' => 'required|integer|min:0',
            'jumlah_gagal' => 'required|integer|min:0',
            'catatan' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();
        try {
            $detailPengiriman = DetailPengiriman::findOrFail($detail_pengiriman_id);
            $jumlah_selesai = $detailPengiriman->jumlah_selesai;
            $totalQc = $request->jumlah_lolos + $request->jumlah_gagal;
            
            // Validasi: Total QC harus sama dengan jumlah_selesai
            if ($totalQc != $jumlah_selesai) {
                return back()->withErrors(['error' => "Total QC ($totalQc) harus sama dengan jumlah selesai ($jumlah_selesai)!"])
                    ->withInput();
            }

            $status = 'selesai';
            $statusPengerjaan = 'Selesai';
            if ($request->jumlah_gagal > 0) {
                $status = 'perlu_perbaikan';
                $statusPengerjaan = 'Perlu Perbaikan';
            }

            QCHasil::updateOrCreate(
                ['detail_pengiriman_id' => $detail_pengiriman_id],
                [
                    'pengambilan_id' => null,
                    'qc_id' => Auth::id(),
                    'tanggal_qc' => now(),
                    'jumlah_lolos' => $request->jumlah_lolos,
                    'jumlah_gagal' => $request->jumlah_gagal,
                    'catatan' => $request->catatan,
                    'status' => $status,
                    'tombol_aksi' => true,
                ]
            );

            $detailPengiriman->update([
                'status_pengerjaan' => $statusPengerjaan,
                'jumlah_selesai' => $jumlah_selesai - $request->jumlah_gagal
            ]);

            

            DB::commit();
            return back()->with('success', 'Penilaian kualitas berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan penilaian: ' . $e->getMessage()]);
        }
    }

    // Method baru untuk Reject barang
    public function rejectBarang(Request $request, $detail_pengiriman_id)
    {
        $request->validate([
            'jumlah_reject' => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $detailPengiriman = DetailPengiriman::findOrFail($detail_pengiriman_id);

            $subProses = $detailPengiriman->sub_proses_id;

            $progresProduksi = $detailPengiriman->produksi->progresProduksi->where('sub_proses_id', $subProses)->first();
             
            
            // Validasi: jumlah reject tidak boleh melebihi progres produksi dlm_proses
            if ($request->jumlah_reject > $progresProduksi->dlm_proses) {
                return back()->withErrors(['error' => 'Jumlah reject melebihi jumlah dalam proses!']);
            }

            // Update atau create QC hasil untuk reject
            $qcHasil = QCHasil::where('detail_pengiriman_id', $detail_pengiriman_id)->first();
            
            if ($qcHasil) {
                // Update existing record
                $qcHasil->increment('jumlah_reject', $request->jumlah_reject);
                $qcHasil->update([
                    'status' => 'selesai',
                    'tombol_aksi' => false,
                ]);

            } else {
                // Create new record
                QCHasil::create([
                    'detail_pengiriman_id' => $detail_pengiriman_id,
                    'pengambilan_id' => null,
                    'qc_id' => Auth::id(),
                    'tanggal_qc' => now(),
                    'jumlah_lolos' => 0,
                    'jumlah_gagal' => 0,
                    'jumlah_reject' => $request->jumlah_reject,
                    'catatan' => null,
                    'status' => 'selesai',
                    'tombol_aksi' => false,
                ]);
            }

            // ========================================
            // UPDATE PROGRES PRODUKSI
            // ========================================
            // Kurangi dlm_proses (barang reject dikeluarkan dari sistem)
            $progresProduksi->dlm_proses -= $request->jumlah_reject;
            
            // Hitung ulang jumlah total
            $progresProduksi->jumlah = $progresProduksi->dlm_proses + $progresProduksi->sdh_selesai;
            $progresProduksi->save();

            // Kurangi jumlah pengiriman
            $jumlahPengirimanBaru = $detailPengiriman->jumlah_selesai - $request->jumlah_reject;
            $detailPengiriman->update([
                'jumlah_selesai' => $jumlahPengirimanBaru,
            ]);

            DB::commit();
            return back()->with('success', "Berhasil mereject {$request->jumlah_reject} barang!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal mereject barang: ' . $e->getMessage()]);
        }
    }

    // Untuk halaman keprod yaitu list pengerjaan supplier produksi
    public function listPengerjaan()
    {
        $pengiriman = Pengiriman::with([
            'detailPengiriman.supplier',
            'detailPengiriman.produksi.barang',
            'detailPengiriman.detailPengambilan',
            'detailPengiriman.qcHasil',
            'qc'
        ])
        ->get()
        ->map(function ($p) {
            // Loop setiap detail pengiriman dan tambahkan data yang sudah dihitung
            $p->detailPengiriman->map(function ($detail) {
                // Hitung sudah diambil
                $detail->sudah_diambil = $detail->detailPengambilan->sum('jumlah_diambil');

                // Hitung Jumlah pengiriman setelah dikurangi reject
                $detail->jumlah_pengiriman_bersih = $detail->jumlah_pengiriman - ($detail->qcHasil->jumlah_reject ?? 0);
                
                // Hitung lolos QC
                $detail->lolos_qc = $detail->qcHasil->jumlah_lolos ?? 0;
                
                // Hitung sisa belum diambil
                $detail->sisa_belum_diambil = $detail->lolos_qc - $detail->sudah_diambil;
                
                return $detail;
            });
            
            return $p;
        })
        ->filter(function ($p) {
            // Filter: tampilkan jika salah satu kondisi terpenuhi
            return $p->detailPengiriman->some(function ($detail) {
                // 1. Belum ada QC sama sekali → TAMPIL
                if (!$detail->qcHasil) {
                    return true;
                }
                
                // 2. Sudah ada QC dan masih punya sisa → TAMPIL
                return $detail->sisa_belum_diambil > 0;
            });
        });
        
        // Bagian List Pengerjaan per Supplier
        $groupedSuppliers = $pengiriman
            ->flatMap(fn ($p) => $p->detailPengiriman)
            ->groupBy('supplier_id');
        
        return view('keprod.listPengerjaanSupplier', compact('pengiriman', 'groupedSuppliers'));
    }

}
