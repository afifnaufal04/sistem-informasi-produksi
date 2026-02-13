<?php

namespace App\Http\Controllers;

use App\Models\GudangQcGagal;
use App\Models\Pemesanan;
use App\Models\PemesananBarang;
use App\Models\ProgresProduksi;
use App\Models\Barang;
use App\Models\SubProses;

use Illuminate\Http\Request;

class GudangQCController extends Controller
{
    public function getPemesananList()
    {
        $data = Pemesanan::select('pemesanan_id', 'no_SPK_kwas', 'pembeli_id')->get();
        return response()->json($data);
    }


    public function pindahkanKePemesanan(Request $request)
    {
        try {
            $request->validate([
                'gagal_id' => 'required|exists:gudang_qc_gagal,gudang_qc_gagal_id',
                'pemesanan_id' => 'required|exists:pemesanan,pemesanan_id',
                'barang_id' => 'required|exists:barang,barang_id',
                'sub_proses_id' => 'required',
                'jumlah' => 'required|integer|min:1',
            ]);

            $qc = GudangQcGagal::find($request->gagal_id);
            if (!$qc) {
                return response()->json(['success' => false, 'message' => 'Data QC gagal tidak ditemukan.']);
            }

            // cek stok qc gagal
            if ($request->jumlah > $qc->jumlah) {
                return response()->json(['success' => false, 'message' => 'Jumlah melebihi stok di gudang QC gagal.']);
            }

            // cek barang di pemesanan
            $pemesananBarang = PemesananBarang::where('pemesanan_id', $request->pemesanan_id)
                ->where('barang_id', $request->barang_id)
                ->first();

            if (!$pemesananBarang) {
                return response()->json(['success' => false, 'message' => 'Barang tidak ada di pemesanan yang dipilih.']);
            }

            // total progres sekarang untuk pemesanan_barang ini
            $totalProgres = ProgresProduksi::where('pemesanan_barang_id', $pemesananBarang->pemesanan_barang_id)
                ->sum('jumlah');

            // pastikan ambil field jumlah pesanan yang benar (fallback ke beberapa kemungkinan)
            $totalPemesanan = $pemesananBarang->jumlah_pemesanan 
                            ?? $pemesananBarang->jumlah 
                            ?? 0;

            $sisaKekurangan = (int)$totalPemesanan - (int)$totalProgres;

            if ($sisaKekurangan <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jumlah barang sudah memenuhi pesanan.'
                ]);
            }

            if ($request->jumlah > $sisaKekurangan) {
                return response()->json([
                    'success' => false,
                    'message' => "Barang yang diinputkan melebihi jumlah kekurangan di progres produksi. Sisa kekurangan: {$sisaKekurangan}."
                ]);
            }

            // update atau create progres untuk sub_proses yang dipilih
            $progres = ProgresProduksi::where('pemesanan_barang_id', $pemesananBarang->pemesanan_barang_id)
                ->where('sub_proses_id', $request->sub_proses_id)
                ->first();

            if ($progres) {
                $progres->increment('jumlah', $request->jumlah);
            } else {
                ProgresProduksi::create([
                    'pemesanan_barang_id' => $pemesananBarang->pemesanan_barang_id,
                    'sub_proses_id' => $request->sub_proses_id,
                    'jumlah' => $request->jumlah,
                ]);
            }

            // kurangi stok QC gagal
            $sisa = $qc->jumlah - $request->jumlah;
            if ($sisa <= 0) $qc->delete();
            else $qc->update(['jumlah' => $sisa]);

            return response()->json(['success' => true, 'message' => 'Barang berhasil dipindahkan.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }



    public function create()
    {
        $barang = Barang::all();
        $subProses = SubProses::all();

        return view('ppic.tambahstok', compact('barang', 'subProses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'barang_id' => 'required|exists:barang,barang_id',
            'sub_proses_id' => 'required|exists:sub_proses,sub_proses_id',
            'jumlah' => 'required|integer|min:1',
            'asal_spk' => 'nullable|string|max:255',
            'catatan' => 'nullable|string|max:255',
        ]);

        GudangQcGagal::create($validated);

        return redirect()->route('ppic.qcGagal.view')->with('success', 'Stok QC Gagal berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $barang = Barang::all();
        $subProses = SubProses::all();
        $gudangQcGagal = GudangQcGagal::findOrFail($id);
        return view('ppic.editstok', compact('gudangQcGagal', 'barang', 'subProses'));
    }
    public function update(Request $request, $id)
    {
        $gudangQcGagal = GudangQcGagal::findOrFail($id);
        $gudangQcGagal->update($request->all());
        return redirect()->route('ppic.qcGagal.view')->with('success', 'Stok berhasil diperbarui.');
    }

    public function destroy($id)
    {
        try {
            $qcGagal = GudangQCGagal::findOrFail($id);
            $qcGagal->delete();

            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dihapus.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus data: ' . $e->getMessage()
            ], 500);
        }
    }




}
