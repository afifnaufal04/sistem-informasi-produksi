<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\SubProses;
use App\Models\BahanPendukung;
use App\Models\BahanPendukungBarang;
use App\Models\Packing;
use App\Models\Pemesanan;
use App\Models\PemesananBarang;
// Import the BarangExport class
use App\Exports\BarangExport; 
use Illuminate\Support\Facades\Auth;use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class BarangController extends Controller
{
    public function index(Request $request)
    {
        $role = Auth::user()->role;
        
        // Ambil query search dari request
        $search = $request->input('search');
        
        // Query barang dengan filter search
        $barangs = Barang::query()
            ->when($search, function($query, $search) {
                return $query->where('nama_barang', 'LIKE', "%{$search}%");
            })
            ->get();

        return view($role . '.daftarbarang', compact('barangs', 'search'));
    }

     public function create()
    {
        $role = Auth::user()->role;

        // Ambil data sub proses dan bahan pendukung
        $subProses = SubProses::orderBy('sub_proses_id')->get();
        $bahanPendukung = BahanPendukung::orderBy('bahan_pendukung_id')->get();
        
        return view($role . '.createbarang', compact('subProses', 'bahanPendukung'));
    }

    public function store(Request $request)
    {
        $role = Auth::user()->role;
        
        $data = $request->validate([
            'nama_barang'   => 'required|string|max:255',
            'harga_barang'  => 'nullable|integer|min:0',
            'stok_gudang'   => 'nullable|integer|min:0',
            'jenis_barang'  => 'required|string|max:100',
            'panjang'       => 'required|numeric|min:0.1',
            'lebar'         => 'required|numeric|min:0.1',
            'tinggi'        => 'required|numeric|min:0.1',
            'gambar_barang' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            
            // Validasi bahan pendukung (OPSIONAL)
            'sub_proses_id' => 'nullable|array',
            'sub_proses_id.*' => 'nullable|exists:sub_proses,sub_proses_id',
            'bahan_pendukung_id' => 'nullable|array',
            'bahan_pendukung_id.*' => 'nullable|exists:bahan_pendukung,bahan_pendukung_id',
            'jumlah_bahan_pendukung' => 'nullable|array',
            'jumlah_bahan_pendukung.*' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Default nilai
            $data['stok_gudang'] = $data['stok_gudang'] ?? 0;
            $data['harga_barang'] = $data['harga_barang'] ?? 0;

            // Upload gambar kalau ada
            if ($request->hasFile('gambar_barang')) {
                $path = $request->file('gambar_barang')->store('barang', 'public');
                $data['gambar_barang'] = $path;
            }

            // Simpan barang
            $barang = Barang::create($data);

            // Simpan bahan pendukung (HANYA JIKA ADA INPUT)
            if ($request->has('sub_proses_id') && is_array($request->sub_proses_id)) {
                foreach ($request->sub_proses_id as $index => $subProsesId) {
                    // Skip jika salah satu field kosong
                    if (empty($subProsesId) || empty($request->bahan_pendukung_id[$index]) || empty($request->jumlah_bahan_pendukung[$index])) {
                        continue;
                    }

                    BahanPendukungBarang::create([
                        'barang_id' => $barang->barang_id,
                        'sub_proses_id' => $subProsesId,
                        'bahan_pendukung_id' => $request->bahan_pendukung_id[$index],
                        'jumlah_bahan_pendukung' => $request->jumlah_bahan_pendukung[$index],
                    ]);
                }
            }

            DB::commit();

            $message = $request->has('sub_proses_id') && count($request->sub_proses_id) > 0
                ? 'Barang dan bahan pendukung berhasil ditambahkan.'
                : 'Barang berhasil ditambahkan. Bahan pendukung dapat ditambahkan nanti oleh PPIC.';

            return redirect()->route($role.'.daftarbarang')
                            ->with('success', $message);
                            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan: ' . $e->getMessage()])->withInput();
        }
    }

    public function edit($id)
    {
        $role = Auth::user()->role;
        
        // Ambil barang dengan relasi bahanPendukungBarang
        // bahanPendukungBarang -> subProses
        // bahanPendukungBarang -> bahanPendukungDetail
        $barang = Barang::with(['bahanPendukungBarang.subProses', 'bahanPendukungBarang'])
                        ->findOrFail($id);
        
        // Ambil data sub proses dan bahan pendukung untuk dropdown
        $subProses = SubProses::orderBy('sub_proses_id')->get();
        $bahanPendukung = BahanPendukung::orderBy('bahan_pendukung_id')->get();
        
        return view($role . '.editbarang', compact('barang', 'subProses', 'bahanPendukung'));
    }

    public function update(Request $request, $id)
    {
        $role = Auth::user()->role;
        
        $data = $request->validate([
            'nama_barang'   => 'required|string|max:255',
            'harga_barang'  => 'nullable|integer|min:0',
            'stok_gudang'   => 'nullable|integer|min:0',
            'jenis_barang'  => 'required|string|max:100',
            'panjang'       => 'required|numeric|min:0.1',
            'lebar'         => 'required|numeric|min:0.1',
            'tinggi'        => 'required|numeric|min:0.1',
            'gambar_barang' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            
            // Validasi bahan pendukung (OPSIONAL)
            'bahan_pendukung_barang_id' => 'nullable|array',
            'bahan_pendukung_barang_id.*' => 'nullable|integer',
            'sub_proses_id' => 'nullable|array',
            'sub_proses_id.*' => 'nullable|exists:sub_proses,sub_proses_id',
            'bahan_pendukung_id' => 'nullable|array',
            'bahan_pendukung_id.*' => 'nullable|exists:bahan_pendukung,bahan_pendukung_id',
            'jumlah_bahan_pendukung' => 'nullable|array',
            'jumlah_bahan_pendukung.*' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $barang = Barang::findOrFail($id);
            
            // Default nilai
            $data['stok_gudang'] = $data['stok_gudang'] ?? 0;
            $data['harga_barang'] = $data['harga_barang'] ?? 0;

            // Upload gambar baru jika ada
            if ($request->hasFile('gambar_barang')) {
                // Hapus gambar lama jika ada
                if ($barang->gambar_barang && Storage::disk('public')->exists($barang->gambar_barang)) {
                    Storage::disk('public')->delete($barang->gambar_barang);
                }
                
                $path = $request->file('gambar_barang')->store('barang', 'public');
                $data['gambar_barang'] = $path;
            }

            // Update barang
            $barang->update($data);

            // Ambil ID bahan pendukung yang ada di form
            $existingIds = $request->input('bahan_pendukung_barang_id', []);
            $existingIds = array_filter($existingIds); // Hapus nilai kosong
            
            // Hapus bahan pendukung yang tidak ada di form (yang dihapus user)
            BahanPendukungBarang::where('barang_id', $barang->barang_id)
                                ->whereNotIn('bahan_pendukung_barang_id', $existingIds)
                                ->delete();
            
            // Loop untuk update/insert bahan pendukung
            if ($request->has('sub_proses_id') && is_array($request->sub_proses_id)) {
                foreach ($request->sub_proses_id as $index => $subProsesId) {
                    // Skip jika salah satu field penting kosong
                    if (empty($subProsesId) || empty($request->bahan_pendukung_id[$index]) || empty($request->jumlah_bahan_pendukung[$index])) {
                        continue;
                    }

                    $bpbId = $request->bahan_pendukung_barang_id[$index] ?? null;
                    
                    $dataUpdate = [
                        'barang_id' => $barang->barang_id,
                        'sub_proses_id' => $subProsesId,
                        'bahan_pendukung_id' => $request->bahan_pendukung_id[$index],
                        'jumlah_bahan_pendukung' => $request->jumlah_bahan_pendukung[$index] ?? 0,
                    ];

                    if ($bpbId) {
                        // Update existing record
                        BahanPendukungBarang::where('bahan_pendukung_barang_id', $bpbId)
                                            ->update($dataUpdate);
                    } else {
                        // Create new record
                        BahanPendukungBarang::create($dataUpdate);
                    }
                }
            }

            DB::commit();

            return redirect()->route($role . '.daftarbarang')
                            ->with('success', 'Barang berhasil diperbarui.');
                            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memperbarui: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        $role = Auth::user()->role;
        
        DB::beginTransaction();
        try {
            $barang = Barang::findOrFail($id);
            
            // Hapus gambar jika ada
            if ($barang->gambar_barang && Storage::disk('public')->exists($barang->gambar_barang)) {
                Storage::disk('public')->delete($barang->gambar_barang);
            }
            
            // Hapus bahan pendukung terkait
            BahanPendukungBarang::where('barang_id', $barang->barang_id)->delete();
            
            // Hapus barang
            $barang->delete();
            
            DB::commit();
            
            return redirect()->route($role . '.daftarbarang')->with('success', 'Barang berhasil dihapus.');
                            
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menghapus: ' . $e->getMessage()]);
        }
    }


    // Fungsi untuk Packing

    // Mendapatkan daftar pemesanan berdasarkan barang_id
    public function getPemesananByBarang($barang_id)
    {
        $pemesanan = PemesananBarang::with([
                'pemesanan',
                'pemesanan.pembeli', 
                'barang',
                'packing' // tambahkan relasi packing
            ])
            ->withSum('packing', 'jumlah_packing') // hitung total packing
            ->where('barang_id', $barang_id)
            ->get()
            ->filter(function($pb) {
                // Filter yang belum selesai packing
                $totalPacking = $pb->packing_sum_jumlah_packing ?? 0;
                return $pb->jumlah_pemesanan > $totalPacking;
            })
            ->values() // reset index array
            ->map(function($pb) {
                // Tambahkan info sisa yang perlu di-packing
                $totalPacking = $pb->packing_sum_jumlah_packing ?? 0;
                $pb->total_packing = $totalPacking;
                $pb->sisa_packing = $pb->jumlah_pemesanan - $totalPacking;
                return $pb;
            });
        
        return response()->json([
            'success' => true,
            'pemesanan' => $pemesanan
        ]);
    }


    public function getBahanPacking($barang_id)
    {
        try {
            $barang = Barang::with(['bahanPendukungBarang' => function($query) {
                $query->where('sub_proses_id', 11)
                    ->with('bahanPendukung');
            }])->findOrFail($barang_id);

            $result = $barang->bahanPendukungBarang->map(function ($item) {
                return [
                    'nama_bahan' => $item->bahanPendukung->nama_bahan_pendukung ?? 'N/A',
                    'stok'       => $item->bahanPendukung->stok_bahan_pendukung ?? 0,
                    'kebutuhan'  => $item->jumlah_bahan_pendukung ?? 0,
                    'satuan'     => $item->bahanPendukung->satuan ?? 'pcs',
                ];
            });

            return response()->json($result);
            
        } catch (\Exception $e) {
            Log::error('Error in getBahanPacking: ' . $e->getMessage());
            return response()->json([
                'error' => 'Gagal mengambil data bahan pendukung',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function storePacking(Request $request)
    {
        $request->validate([
            'barang_id' => 'required|exists:barang,barang_id',
            'pemesanan_barang_id' => 'required|exists:pemesanan_barang,pemesanan_barang_id',
            'jumlah_packing' => 'required|integer|min:1',
        ]);

        DB::beginTransaction();
        try {
            // ... kode validasi stok dan pemesanan ...
            $barang = Barang::findOrFail($request->barang_id);

            // Simpan data packing TANPA sub_proses_id
            Packing::create([
                'pemesanan_barang_id' => $request->pemesanan_barang_id,
                'jumlah_packing' => $request->jumlah_packing,
                'gudang_konfirmasi' => false,
            ]);

            $pemesananBarang = PemesananBarang::findOrFail($request->pemesanan_barang_id);
            $pemesananBarang->status = 'diproses';
            $pemesananBarang->save();

            // Kurangi stok gudang
            $barang->decrement('stok_gudang', $request->jumlah_packing);

            DB::commit();
            return back()->with('success', "Berhasil menambahkan {$request->jumlah_packing} pcs ke packing!");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyimpan data packing: ' . $e->getMessage()])->withInput();
        }
    }

    public function export()
    {
        // Ambil semua data barang dengan relasi jika ada
        $barangs = Barang::orderBy('nama_barang')->get();

        // Nama file dengan timestamp
        $filename = 'Daftar_Barang_Stok_' . date('YmdHis') . '.xlsx';

        // Return Excel download
        return \Maatwebsite\Excel\Facades\Excel::download( 
            new BarangExport($barangs),
            $filename
        );
    }
}