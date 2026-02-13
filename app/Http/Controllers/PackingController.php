<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Packing;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; // Import the Log facade

use Illuminate\Support\Facades\DB;

class PackingController extends Controller
{
    // Daftar Packing
    public function index()
    {
        $role = Auth::user()->role;
        $packings = Packing::with('pemesananBarang.pemesanan','pemesananBarang.pemesanan.pembeli', 'pemesananBarang.barang')
            ->orderBy('created_at', 'desc')
            ->where('status_packing', '!=', 'Selesai')
            ->get();
        return view($role.'.daftarpacking', compact('packings'));
    }

    // Update Status Packing dan Menyelesaikan Detail Pemesanan jika sudah lengkap
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'jumlah_selesai_packing' => 'required|integer|min:0',
        ]);

        try {
            $packing = Packing::findOrFail($id);

            // ❗ Validasi agar tidak melebihi jumlah packing
            if ($request->jumlah_selesai_packing > $packing->jumlah_packing) {
                return back()->withErrors([
                    'error' => 'Jumlah selesai packing melebihi jumlah packing!'
                ]);
            }

            // Update jumlah selesai packing
            $packing->jumlah_selesai_packing = $request->jumlah_selesai_packing;

            // Jika sudah penuh → otomatis selesai
            if ($packing->jumlah_selesai_packing == $packing->jumlah_packing) {
                $packing->status_packing = 'Selesai';
            } else {
                $packing->status_packing = 'Dalam Proses';
            }
            
            $packing->save();
            
            // Hitung total packing
            $totalPacking = Packing::where('pemesanan_barang_id', $packing->pemesanan_barang_id)
                ->where('status_packing', 'Selesai')
                ->sum('jumlah_packing');
            
            // Ambil jumlah pemesanan
            $jumlahPemesanan = $packing->pemesananBarang->jumlah_pemesanan;
            
            // Jika total packing sudah sesuai, otomatis selesaikan pemesanan
            if ($totalPacking >= $jumlahPemesanan) {
                $packing->pemesananBarang->status = 'selesai';
                $packing->pemesananBarang->save();
            }

            return back()->with('success', 'Status packing berhasil diperbarui!');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal memperbarui status: ' . $e->getMessage()]);
        }
    }

    // Delete Packing
    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $packing = Packing::findOrFail($id);
            
            // Kembalikan stok gudang
            $pemesananBarang = $packing->pemesananBarang;
            $barang = $pemesananBarang->barang;
            $barang->increment('stok_gudang', $packing->jumlah_packing);
            
            $packing->delete();
            
            DB::commit();
            return back()->with('success', 'Data packing berhasil dihapus dan stok dikembalikan!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menghapus data: ' . $e->getMessage()]);
        }
    }

    // Packing untuk Gudang
    // Menampilkan halaman daftar packing
    public function indexForGudang()
    {
        $packings = Packing::with([
            'pemesananBarang.pemesanan.pembeli',
            'pemesananBarang.barang'
        ])
        ->orderBy('created_at', 'desc')
        ->get();
        
        return view('gudang.daftarpacking', compact('packings'));
    }
    
    // Get detail packing beserta kebutuhan bahan
    public function getPackingDetail($packing_id)
    {
        try {
            $packing = Packing::with([
                'pemesananBarang.pemesanan.pembeli',
                'pemesananBarang.barang.bahanPendukungBarang.bahanPendukung'
            ])->findOrFail($packing_id);
            
            // Ambil barang
            $barang = $packing->pemesananBarang->barang;
            
            // Hitung kebutuhan bahan (sub_proses_id = 11 untuk Packing)
            $bahanPendukung = $barang->bahanPendukungBarang()
                ->where('sub_proses_id', 11)
                ->with('bahanPendukung')
                ->get();
            
            $kebutuhanBahan = $bahanPendukung->map(function ($item) use ($packing) {
                // Hitung total butuh
                $totalButuh = $item->jumlah_bahan_pendukung * $packing->jumlah_packing;
                
                return [
                    'bahan_pendukung_id' => $item->bahan_pendukung_id,
                    'nama_bahan' => $item->bahanPendukung->nama_bahan_pendukung,
                    'kebutuhan_per_pcs' => $item->jumlah_bahan_pendukung,
                    'total_butuh' => $totalButuh,
                    'stok' => $item->bahanPendukung->stok_bahan_pendukung,
                    'satuan' => $item->bahanPendukung->satuan,
                ];
            });
            
            return response()->json([
                'success' => true,
                'packing' => [
                    'packing_id' => $packing->packing_id,
                    'no_spk' => $packing->pemesananBarang->pemesanan->no_SPK_kwas ?? '-',
                    'nama_pembeli' => $packing->pemesananBarang->pemesanan->pembeli->nama_pembeli ?? '-',
                    'nama_barang' => $barang->nama_barang,
                    'jumlah_packing' => $packing->jumlah_packing,
                    'status_konfirmasi' => $packing->status_konfirmasi ?? 'pending',
                ],
                'kebutuhan_bahan' => $kebutuhanBahan
            ]);
            
        } catch (\Exception $e) {
            Log::error('Error in getPackingDetail: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat detail packing'
            ], 500);
        }
    }
    
    // Konfirmasi packing dan kurangi stok bahan
    public function confirmPacking($packing_id)
    {
        try {
            DB::beginTransaction();
            
            $packing = Packing::with([
                'pemesananBarang.barang.bahanPendukungBarang.bahanPendukung'
            ])->findOrFail($packing_id);
            
            // Cek apakah sudah dikonfirmasi
            if ($packing->gudang_konfirmasi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Packing ini sudah dikonfirmasi sebelumnya'
                ], 400);
            }
            
            $barang = $packing->pemesananBarang->barang;
            
            // Ambil kebutuhan bahan untuk packing
            $bahanPendukung = $barang->bahanPendukungBarang()
                ->where('sub_proses_id', 11)
                ->with('bahanPendukung')
                ->get();
            
            // Validasi stok dan kurangi
            foreach ($bahanPendukung as $item) {
                $totalButuh = $item->jumlah_bahan_pendukung * $packing->jumlah_packing;
                $bahan = $item->bahanPendukung;
                
                // Cek stok
                if ($bahan->stok_bahan_pendukung < $totalButuh) {
                    DB::rollBack();
                    return response()->json([
                        'success' => false,
                        'message' => "Stok {$bahan->nama_bahan_pendukung} tidak mencukupi. Dibutuhkan: {$totalButuh} {$bahan->satuan}, Tersedia: {$bahan->stok_bahan_pendukung} {$bahan->satuan}"
                    ], 400);
                }
                
                // Kurangi stok
                $bahan->stok_bahan_pendukung -= $totalButuh;
                $bahan->save();
            }
            
            // Update status konfirmasi packing
            $packing->gudang_konfirmasi = true;
            $packing->save();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Packing berhasil dikonfirmasi dan stok bahan berhasil dikurangi'
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in confirmPacking: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
