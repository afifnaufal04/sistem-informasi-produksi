<?php

namespace App\Http\Controllers;

use App\Models\BahanPendukung;
use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\Pemesanan;
use App\Models\PemesananBarang;
use App\Models\Pembeli;
use App\Models\BahanPendukungBarang;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use function Symfony\Component\Clock\now;

class PemesananController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        
        $pesanans = Pemesanan::with('pemesananBarang.barang', 'pembeli')
            ->when($search, function($query, $search) {
                return $query->whereHas('pembeli', function($q) use ($search) {
                    $q->where('nama_pembeli', 'LIKE', "%{$search}%");
                });
            })
            ->when($status, function($query, $status) {
                return $query->where('status_pemesanan', $status);
            })
            ->get();

        $role = Auth::user()->role;

        return view($role . '.showpemesanan', compact('pesanans', 'search', 'status'));
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     $barangs = Barang::all();
    //     $pembelis = Pembeli::all(); // ambil semua data pembeli

    //     return view('marketing.tambahpemesanan', compact('barangs', 'pembelis'));
    // }

    public function create()
    {
        $barangs = Barang::all();
        $pembelis = Pembeli::all(); // ambil semua data pembeli

        $bulan = Carbon::now()->format('m');
        $tahun = Carbon::now()->format('Y');

        // Ambil pemesanan terakhir bulan ini
        $lastPemesanan = Pemesanan::whereMonth('created_at', $bulan)
            ->whereYear('created_at', $tahun)
            ->orderBy('pemesanan_id', 'desc')
            ->first();

        // Tentukan nomor urut berikutnya
        if ($lastPemesanan) {
            $lastNumber = (int) explode('/', $lastPemesanan->no_SPK_kwas)[0];
            $nextNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $nextNumber = '001';
        }

        // kode_buyer akan dimasukkan nanti setelah memilih pembeli
        $nomorSPKBase = "{$nextNumber}/SPK"; // bagian awal SPK
        $bulanTahun = "{$bulan}/{$tahun}";  // bagian akhir SPK

        // kirim ke view, nanti digabung setelah pembeli dipilih
        return view('marketing.tambahpemesanan', compact('barangs', 'pembelis', 'nomorSPKBase', 'bulanTahun'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pembeli_id' => 'required|exists:pembeli,pembeli_id',
            'no_PO' => 'required|string',
            'no_SPK_kwas' => 'required|string|unique:pemesanan,no_SPK_kwas',
            'tanggal_pemesanan' => 'required|date',
            'periode_produksi' => 'nullable|string',
            'barang_id' => 'required|array',
            'barang_id.*' => 'exists:barang,barang_id',
            'jumlah_pemesanan' => 'required|array', // Tambahkan validasi
            'jumlah_pemesanan.*' => 'required|integer|min:1', // Validasi setiap item
        ]);

        // Buat data pemesanan utama
        $pemesanan = Pemesanan::create([
            'pembeli_id' => $validated['pembeli_id'],
            'no_PO' => $request->no_PO,
            'no_SPK_kwas' => $request->no_SPK_kwas,
            'tanggal_pemesanan' => $validated['tanggal_pemesanan'],
            'periode_produksi' => $request->periode_produksi,
            'tgl_penerbitan_spk' => now(),
            'status_pemesanan' => 'diproses',
        ]);

        // Simpan setiap barang yang dipesan dengan jumlahnya
        foreach ($validated['barang_id'] as $index => $barangId) {
            PemesananBarang::create([
                'pemesanan_id' => $pemesanan->pemesanan_id,
                'barang_id' => $barangId,
                'status' => 'pending',
                'jumlah_pemesanan' => $validated['jumlah_pemesanan'][$index], // Ambil berdasarkan index
            ]);        
        }

        return redirect()->route('marketing.pemesanan.index')->with('success', 'Pemesanan berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id)
    // {
    //     $pemesanan = Pemesanan::with(['pemesananBarang.barang'])->findOrFail($id);
    //     $barang   = Barang::all(); // untuk pilihan dropdown

    //     return view('marketing.editpemesanan', compact('pemesanan', 'barang'));
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $request->validate([
    //         'pembeli_id' => 'required|integer',
    //         'tanggal_pemesanan' => 'required|date',
    //         'barang_id' => 'required|array',
    //         'barang_id.*' => 'integer',
    //     ]);

    //     $pemesanan = Pemesanan::findOrFail($id);

    //     // Update data pemesanan
    //     $pemesanan->update([
    //         'pembeli_id'        => $request->pembeli_id,
    //         'no_PO'             => $request->no_PO,
    //         'tanggal_pemesanan' => $request->tanggal_pemesanan,
    //         'no_SPK_kwas'       => $request->no_SPK_kwas,
    //     ]);

    //     // Hapus data lama pemesanan_barang
    //     PemesananBarang::where('pemesanan_id', $pemesanan->pemesanan_id)->delete();

    //     // Tambahkan ulang barang yang dipilih
    //     foreach ($request->barang_id as $barangId) {
    //         $bahanBarang = \App\Models\BahanPendukungBarang::create([
    //             'barang_id' => $barangId,
    //             'bahan_pendukung_id' => null,
    //         ]);

    //         PemesananBarang::create([
    //             'pemesanan_id' => $pemesanan->pemesanan_id,
    //             'bahan_dan_barang_id' => $bahanBarang->bahan_dan_barang_id,
    //             'status' => 'baru',
    //         ]);
    //     }

    //     return redirect()->to('/pemesanan')->with('success', 'PO berhasil diperbarui.');
    // }

    public function show(string $id)
    {
        $pemesanan = Pemesanan::with(['pemesananBarang.barang', 'pembeli'])->findOrFail($id);
        $role = Auth::user()->role;

        return view($role . '.detailpemesanan', compact('pemesanan'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pemesanan = Pemesanan::findOrFail($id);

        // Hapus relasi pemesanan_barang dulu
        PemesananBarang::where('pemesanan_id', $pemesanan->pemesanan_id)->delete();

        // Baru hapus pemesanan
        $pemesanan->delete();

        return redirect()->route('pemesanan.index')->with('success', 'PO berhasil dihapus.');
    }

    public function downloadSpk($id)
    {
        // ambil data lengkap dengan relasi yang dibutuhkan
        $pemesanan = Pemesanan::with(['pemesananBarang.barang', 'pembeli'])->findOrFail($id);
        $role = Auth::user()->role;
        // nama file aman
        $pembeli = Str::slug($pemesanan->pembeli->nama_pembeli);
        $nomorSPK = Str::slug($pemesanan->no_SPK_kwas);
        $filename = "{$pembeli}_{$nomorSPK}.pdf";

        // render view jadi PDF
        $pdf = PDF::loadView($role.'.pdfSPK', compact('pemesanan'))
                  ->setPaper('a4', 'landscape'); // pakai portrait jika perlu

        // pilihan: download atau stream; di sini kita download
        return $pdf->download($filename);
        //atau: return $pdf->stream($filename);
    }

    public function konfirmasi($id, $bagian)
    {
        $pemesanan = Pemesanan::findOrFail($id);

        // pastikan hanya kolom yang valid yang bisa diubah
        $kolomValid = [
            'marketing' => 'konfirmasi_marketing',
            'ppic' => 'konfirmasi_ppic',
            'finishing' => 'konfirmasi_finishing',
            'gudang' => 'konfirmasi_gudang',
            'keprod' => 'konfirmasi_keprod',
        ];

        if (array_key_exists($bagian, $kolomValid)) {
            $kolom = $kolomValid[$bagian];
            if($pemesanan->$kolom) {           
                return redirect()->back()->with('error', 'Konfirmasi ' . ucfirst($bagian) . ' sudah dilakukan!');
            }
            $pemesanan->$kolom = true; // ubah jadi true
            $pemesanan->save();
        }
        
        return redirect()->back()->with('success', 'Konfirmasi ' . ucfirst($bagian) . ' berhasil!');
    }

    public function selesaikan($id)
    {
        try {
            $pemesanan = Pemesanan::with('pemesananBarang')->findOrFail($id);
            
            // Validasi: Cek apakah status pemesanan sudah selesai
            if ($pemesanan->status_pemesanan === 'selesai') {
                return back()->withErrors(['error' => 'Pemesanan ini sudah selesai!']);
            }
            
            // Validasi: Cek apakah semua pemesanan_barang sudah selesai
            $belumSelesai = $pemesanan->pemesananBarang()
                ->where('status', '!=', 'selesai')
                ->count();
            
            if ($belumSelesai > 0) {
                return back()->withErrors([
                    'error' => "Tidak dapat menyelesaikan pemesanan! Masih ada {$belumSelesai} pemesanan barang yang belum selesai."
                ]);
            }
            
            // Update status pemesanan
            $pemesanan->update([
                'status_pemesanan' => 'selesai'
            ]);
            
            return back()->with('success', 'Pemesanan berhasil diselesaikan!');
            
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Gagal menyelesaikan pemesanan: ' . $e->getMessage()]);
        }
    }

}
