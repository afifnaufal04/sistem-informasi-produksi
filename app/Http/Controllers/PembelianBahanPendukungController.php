<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\BahanPendukung;
use App\Models\PembelianBahanPendukung;
use App\Models\DetailPembelianBahanPendukung;
use Illuminate\Support\Facades\Auth;

class PembelianBahanPendukungController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $role = Auth::user()->role;

        $tahunList = PembelianBahanPendukung::whereNotNull('tanggal_pembelian')
            ->get(['tanggal_pembelian'])
            ->map(function($item) {
                return date('Y', strtotime($item->tanggal_pembelian));
            })
            ->unique()
            ->sortDesc()
            ->values();
        
        $tahunDipilih = request()->get('tahun', date('Y'));

        // Jika user pilih 'semua', tampilkan semua data tanpa filter tahun
        if ($tahunDipilih === 'semua') {
            if ($role === 'purchasing') {
                $pembelians = PembelianBahanPendukung::with('detailpembelianbahanpendukung.bahanpendukung')
                              ->whereIn('status_order', ['Menunggu', 'Proses Pembelian'])
                              ->get();
            } elseif ($role === 'gudang') {
                $pembelians = PembelianBahanPendukung::with('detailpembelianbahanpendukung.bahanpendukung')
                    ->whereIn('status_order', ['Proses Pembelian', 'Barang Diterima'])
                    ->get();
            } elseif ($role === 'ppic'){
                $pembelians = PembelianBahanPendukung::with('detailpembelianbahanpendukung.bahanpendukung')
                    ->whereIn('status_order', ['Proses Pembelian', 'Menunggu', 'Barang Diterima'])
                    ->get();
            } else {
                abort(403, 'Unauthorized action.');
            }
        } else {
            $pembelians = PembelianBahanPendukung::with('detailpembelianbahanpendukung.bahanpendukung')
                ->where(function ($query) use ($role) {
                    if ($role === 'purchasing') {
                        $query->whereIn('status_order', ['Menunggu', 'Proses Pembelian']);
                    } elseif ($role === 'gudang') {
                        $query->whereIn('status_order', ['Proses Pembelian', 'Barang Diterima']);
                    } elseif ($role === 'ppic') {
                        $query->whereIn('status_order', ['Proses Pembelian', 'Menunggu', 'Barang Diterima']);
                    } else{
                        abort(403, 'Unauthorized action.');
                    }
                })
                ->whereYear('tanggal_pembelian', $tahunDipilih)
                ->get();
        }

        $historyPembelian = PembelianBahanPendukung::with('detailpembelianbahanpendukung.bahanpendukung')
        ->where('status_order', 'Barang Diterima')
        ->orderBy('tanggal_pembelian', 'desc')
        ->get();

        return view($role.'.daftarorderbahanpendukung', compact('pembelians', 'tahunList', 'tahunDipilih', 'historyPembelian'));
    }

    /**
     * Form tambah pembelian.
     */
    public function create()
    {
        $bahanPendukung = BahanPendukung::all();
        $role = Auth::user()->role;
        return view($role.'.orderbahanpendukung', compact('bahanPendukung'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 🧾 Validasi input
        $request->validate([
            'tanggal_pembelian' => 'required|date',
            'catatan' => 'nullable|string|max:2000',
            'bahan_pendukung_id' => 'required|array|min:1',
            'bahan_pendukung_id.*' => 'required|exists:bahan_pendukung,bahan_pendukung_id',
            'harga_bahan_pendukung.*' => 'required|numeric|min:0',
            'jumlah_pembelian.*' => 'required|integer|min:1',
            'subtotal.*' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
        ]);

        // 🧠 Simpan ke tabel utama pembelian_bahan_pendukung
        $pembelian = PembelianBahanPendukung::create([
            'tanggal_pembelian' => $request->tanggal_pembelian,
            'total_harga' => $request->total,
            'catatan' => $request->catatan,
        ]);

        // 🧩 Simpan ke tabel detail pembelian
        foreach ($request->bahan_pendukung_id as $index => $bahanId) {
            DetailPembelianBahanPendukung::create([
                'pembelian_bahan_pendukung_id' => $pembelian->pembelian_bahan_pendukung_id,
                'bahan_pendukung_id' => $bahanId,
                'harga_bahan_pendukung' => $request->harga_bahan_pendukung[$index],
                'jumlah_pembelian' => $request->jumlah_pembelian[$index],
                'subtotal' => $request->subtotal[$index],
            ]);
        }

        $role = Auth::user()->role;

        return redirect()->route($role.'.daftarorderbahanpendukung')
            ->with('success', 'Order bahan pendukung berhasil disimpan!');
    }

    public function keperluanBahanPendukung($pemesananId)
    {
        // Ambil bahan pendukung yang diperlukan untuk pemesanan tertentu
        $bahanPendukung = BahanPendukung::whereHas('bahanPendukungBarang.barang.pemesananBarang', function ($query) use ($pemesananId) {
            $query->where('pemesanan_id', $pemesananId);
        })->get();

        return response()->json($bahanPendukung);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = PembelianBahanPendukung::with('detailpembelianbahanpendukung.bahanpendukung')->findOrFail($id);
        $bahanPendukung = BahanPendukung::all();
        $role = Auth::user()->role;
        return view($role.'.editorderbahanpendukung', compact('order', 'bahanPendukung'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // 🔹 Validasi input
        $request->validate([
            'tanggal_pembelian' => 'required|date',
            'catatan' => 'nullable|string|max:2000',
            'bahan_pendukung_id' => 'required|array|min:1',
            'harga_bahan_pendukung' => 'required|array',
            'jumlah_pembelian' => 'required|array',
            'subtotal' => 'required|array',
            'total' => 'required|numeric|min:1',
        ]);

        // 🔹 Ambil order yang mau diupdate
        $order = PembelianBahanPendukung::findOrFail($id);

        // 🔹 Update data utama order
        $order->update([
            'tanggal_pembelian' => $request->tanggal_pembelian,
            'total_harga' => $request->total,
            'catatan' => $request->catatan,
        ]);

        // 🔹 Hapus semua detail lama untuk diganti dengan yang baru
        DetailPembelianBahanPendukung::where('pembelian_bahan_pendukung_id', $order->pembelian_bahan_pendukung_id)->delete();

        // 🔹 Simpan detail baru
        foreach ($request->bahan_pendukung_id as $index => $bahanId) {
            if (!$bahanId) continue;

            DetailPembelianBahanPendukung::create([
                'pembelian_bahan_pendukung_id' => $order->pembelian_bahan_pendukung_id,
                'bahan_pendukung_id' => $bahanId,
                'harga_bahan_pendukung' => $request->harga_bahan_pendukung[$index],
                'jumlah_pembelian' => $request->jumlah_pembelian[$index],
                'subtotal' => $request->subtotal[$index],
            ]);

            $role = Auth::user()->role;
        }

        // 🔹 Redirect dengan pesan sukses
        return redirect()->route($role.'.daftarorderbahanpendukung')
            ->with('success', 'Data order bahan pendukung berhasil diperbarui!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    /**
 * Konfirmasi oleh Purchasing (ubah status ke 'Proses Pembelian')
 */
    public function konfirmasiPembelian($id)
    {
        $order = PembelianBahanPendukung::findOrFail($id);

        if ($order->status_order === 'Menunggu') {
            $order->update(['status_order' => 'Proses Pembelian']);
        }

        return redirect()->back()->with('success', 'Status berhasil diubah menjadi Proses Pembelian!');
    }

    /**
     * Konfirmasi oleh Gudang (ubah status ke 'Barang Diterima')
     */
    public function konfirmasiSampai($id)
    {
        $order = PembelianBahanPendukung::findOrFail($id);
        $detailPembelianBahanPendukung = DetailPembelianBahanPendukung::where('pembelian_bahan_pendukung_id', $order->pembelian_bahan_pendukung_id)->get();

        if ($order->status_order === 'Proses Pembelian') {
            $order->update(['status_order' => 'Barang Diterima']);

            foreach ($detailPembelianBahanPendukung as $detail) {
                $bahanPendukung = BahanPendukung::findOrFail($detail->bahan_pendukung_id);
                $bahanPendukung->update(['stok_bahan_pendukung' => $bahanPendukung->stok_bahan_pendukung + $detail->jumlah_pembelian]);
            }
        } elseif ($order->status_order === 'Menunggu') {
            return redirect()->back()->with('error', 'Belum ada pembelian yang dilakukan');
        }

        return redirect()->back()->with('success', 'Barang Diterima dan stok bahan pendukung berhasil diperbarui!');
    }

    // Menu untuk PPIC untuk melihat jumlah kardus yang dibutuhkan keseluruhan di awal
    public function jumlahKardusBahanPendukung()
    {
        $role = Auth::user()->role;

        $kardusList = BahanPendukung::where('nama_bahan_pendukung', 'LIKE', '%Kardus%')
        ->with([
            'bahanPendukungBarang.barang.pemesananBarang' => function ($query) {
                $query->where('status', '!=', 'selesai');
            }
        ])
        ->get();

        $dataKebutuhan = [];

        foreach ($kardusList as $kardus) {
            foreach ($kardus->bahanPendukungBarang as $bpBarang) {
                if (!$bpBarang->barang) continue;

                foreach ($bpBarang->barang->pemesananBarang as $pemesanan) {

                    $key = $bpBarang->barang->nama_barang . '-' . $kardus->nama_bahan_pendukung;

                    $totalKardus =
                        $pemesanan->jumlah_pemesanan *
                        $bpBarang->jumlah_bahan_pendukung;

                    if (!isset($dataKebutuhan[$key])) {
                        $dataKebutuhan[$key] = [
                            'nama_barang'       => $bpBarang->barang->nama_barang,
                            'jumlah_pemesanan'  => 0,
                            'kardus_per_barang' => $bpBarang->jumlah_bahan_pendukung,
                            'ukuran_kardus'     => $kardus->nama_bahan_pendukung,
                            'total_kardus'      => 0,
                        ];
                    }

                    // 🔥 AKUMULASI
                    $dataKebutuhan[$key]['jumlah_pemesanan'] += $pemesanan->jumlah_pemesanan;
                    $dataKebutuhan[$key]['total_kardus']     += $totalKardus;
                }
            }
        }

        // Reset index biar Blade rapi
        $dataKebutuhan = array_values($dataKebutuhan);

        return view(
            $role . '.kebutuhanKardus',
            compact('dataKebutuhan')
        );
    }

}
