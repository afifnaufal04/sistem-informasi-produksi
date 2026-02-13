@extends('layouts.allApp')

@section('title', 'Edit Barang')
@section('role', 'Admin')

@section('content')
<div class="container mx-auto px-6 w-full">
    <h1 class="text-2xl font-bold mb-6">Edit Barang</h1>

    <form action="{{ route('admin.barang.update', $barang->barang_id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Nama Barang</label>
            <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Harga Barang</label>
            <input type="number" name="harga_barang" value="{{ old('harga_barang', $barang->harga_barang) }}" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Stok Gudang</label>
            <input type="number" name="stok_gudang" value="{{ old('stok_gudang', $barang->stok_gudang) }}" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" readonly>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Jenis Barang</label>
            <select name="jenis_barang" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" required>
                <option value="diy" {{ old('jenis_barang', $barang->jenis_barang) == 'diy' ? 'selected' : '' }}>DIY</option>
                <option value="superindo" {{ old('jenis_barang', $barang->jenis_barang) == 'superindo' ? 'selected' : '' }}>Superindo</option>
                <option value="pendopo" {{ old('jenis_barang', $barang->jenis_barang) == 'pendopo' ? 'selected' : '' }}>Pendopo</option>
                <option value="ooa" {{ old('jenis_barang', $barang->jenis_barang) == 'ooa' ? 'selected' : '' }}>OOA</option>
            </select>
        </div>

        <div class="grid grid-cols-3 gap-4">
            <div>
                <label class="block text-sm font-medium mb-1">Panjang (cm)</label>
                <input type="number" name="panjang" value="{{ old('panjang', $barang->panjang) }}" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Lebar (cm)</label>
                <input type="number" name="lebar" value="{{ old('lebar', $barang->lebar) }}" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200">
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Tinggi (cm)</label>
                <input type="number" name="tinggi" value="{{ old('tinggi', $barang->tinggi) }}" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200">
            </div>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Upload Gambar</label>
            <input type="file" name="gambar_barang" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200">
            @if($barang->gambar_barang)
                <p class="text-sm mt-2">Gambar saat ini:</p>
                <img src="{{ asset('storage/' . $barang->gambar_barang) }}" alt="Gambar Barang" class="w-32 h-32 object-cover mt-2 rounded">
            @endif
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('admin.daftarbarang') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Batal</a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Update</button>
        </div>
    </form>
</div>
@endsection
