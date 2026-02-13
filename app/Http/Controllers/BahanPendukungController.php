<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BahanPendukung;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;

class BahanPendukungController extends Controller
{
    public function index()
    {
        $bahanPendukung = BahanPendukung::orderBy('updated_at', 'desc')->get();
        $role = Auth::user()->role;

        return view($role . '.daftarbahanpendukung', compact('bahanPendukung'));
    }

    
    public function create()
    {
        return view('gudang.createbahanpendukung');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan_pendukung' => 'required|string|max:100',
            'stok_bahan_pendukung' => 'required|integer|min:0',
            'harga_bahan_pendukung' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:20',
            'catatan' => 'nullable|string',
        ]);

        BahanPendukung::create([
            'nama_bahan_pendukung' => $request->nama_bahan_pendukung,
            'stok_bahan_pendukung' => $request->stok_bahan_pendukung,
            'harga_bahan_pendukung' => $request->harga_bahan_pendukung,
            'satuan' => $request->satuan,
            'catatan' => $request->catatan,
        ]);

        $role = Auth::user()->role;
        return redirect()
            ->route($role . '.daftarbahanpendukung')
            ->with('success', 'Data bahan pendukung berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $bahanPendukung = BahanPendukung::findOrFail($id);
        $role = Auth::user()->role;
        return view($role . '.editbahanpendukung', compact('bahanPendukung'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_bahan_pendukung' => 'required|string|max:100',
            'stok_bahan_pendukung' => 'required|integer|min:0',
            'harga_bahan_pendukung' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:20',
            'catatan' => 'nullable|string',
        ]);

        $role = Auth::user()->role;
        $bahanPendukung = BahanPendukung::findOrFail($id);
        $bahanPendukung->update([
            'nama_bahan_pendukung' => $request->nama_bahan_pendukung,
            'stok_bahan_pendukung' => $request->stok_bahan_pendukung,
            'harga_bahan_pendukung' => $request->harga_bahan_pendukung,
            'satuan' => $request->satuan,
            'catatan' => $request->catatan,
        ]);

        return redirect()
            ->route($role . '.daftarbahanpendukung')
            ->with('success', 'Data bahan pendukung berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $bahanPendukung = BahanPendukung::findOrFail($id);
        $role = Auth::user()->role;

        try {
            $bahanPendukung->delete();

            return redirect()
                ->route($role . '.daftarbahanpendukung')
                ->with('success', 'Data bahan pendukung berhasil dihapus!');
        } catch (QueryException $e) {
            if ((string) $e->getCode() === '23000') {
                return redirect()
                    ->route($role . '.daftarbahanpendukung')
                    ->with('error', 'Gagal menghapus: bahan pendukung masih dipakai oleh data lain. Hapus/ubah data terkait terlebih dahulu.');
            }

            throw $e;
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
        $bahanPendukung = BahanPendukung::where('nama_bahan_pendukung', 'like', '%' . $query . '%')->get();
        $role = Auth::user()->role;
        
        return view($role . '.daftarbahanpendukung', compact('bahanPendukung', 'query'));
    }
}
