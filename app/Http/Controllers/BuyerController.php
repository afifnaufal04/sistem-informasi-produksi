<?php

namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use App\Models\Pembeli;

class BuyerController extends Controller
{
    public function index()
    {
        $buyers = Pembeli::all();
        return view('admin.daftarPembeli', compact('buyers'));
    }

    public function create()
    {
        return view('admin.createPembeli');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pembeli' => 'required|string|max:255',
            'kode_buyer' => 'required|string|max:255',
        ]);

        Pembeli::create($request->all());
        return redirect()->route('admin.daftarPembeli')->with('success', 'Buyer created successfully.');
    }

    public function destroy($id)
    {
        $buyer = Pembeli::findOrFail($id);
        $buyer->delete();
        return redirect()->route('admin.daftarPembeli')->with('success', 'Buyer deleted successfully.');
    }
}
