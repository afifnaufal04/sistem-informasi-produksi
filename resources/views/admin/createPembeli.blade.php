@extends('layouts.allApp')

@section('title', 'Tambah Pembeli')
@section('role', 'Admin')

@section('content')
<div class="container mx-auto px-6 w-full">
    <h1 class="text-2xl font-bold mb-6">Tambah Pembeli</h1>

    <form action="{{ route('admin.pembeli.store') }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md space-y-4">
        @csrf
        <div>
            <label class="block text-sm font-medium mb-1">Nama Pembeli</label>
            <input type="text" name="nama_pembeli" value="{{ old('nama_pembeli') }}" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Kode Pembeli</label>
            <input type="text" name="kode_buyer" value="{{ old('kode_buyer') }}" class="w-full border rounded-lg p-2 focus:ring focus:ring-blue-200" required>
        </div>

        <div class="flex justify-end space-x-2">
            <a href="{{ route('admin.pembeli.create') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg">Batal</a>
            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Simpan</button>
        </div>
    </form>
</div>
@endsection
