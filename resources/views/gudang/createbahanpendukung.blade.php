@extends('layouts.allApp')

@section('title', 'Tambah Bahan Pendukung')
@section('role', 'Gudang')

@section('content')
<div class="max-w-lg mx-auto bg-white rounded-lg shadow-md p-6 mt-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Tambah Bahan Pendukung</h1>

    {{-- Notifikasi sukses --}}
    @if(session('success'))
        <div class="mb-4 p-3 rounded-md bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error validasi --}}
    @if ($errors->any())
        <div class="mb-4 p-3 rounded-md bg-red-100 text-red-700 border border-red-300">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('gudang.bahanpendukung.store') }}" method="POST">
        @csrf

        {{-- Nama bahan pendukung --}}
        <div class="mb-4">
            <label for="nama_bahan_pendukung" class="block text-gray-700 font-semibold mb-1">
                Nama Bahan Pendukung
            </label>
            <input type="text" name="nama_bahan_pendukung" id="nama_bahan_pendukung"
                   value="{{ old('nama_bahan_pendukung') }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                   placeholder="Contoh: Kardus Kemasan / Lem / Plastik">
        </div>

        {{-- Stok bahan pendukung --}}
        <div class="mb-4">
            <label for="stok_bahan_pendukung" class="block text-gray-700 font-semibold mb-1">
                Stok Awal
            </label>
            <input type="number" name="stok_bahan_pendukung" id="stok_bahan_pendukung"
                   value="{{ old('stok_bahan_pendukung', 0) }}"
                   min="0"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                   placeholder="Masukkan jumlah stok">
        </div>

        {{-- Harga bahan pendukung --}}
        <div class="mb-6">
            <label for="harga_bahan_pendukung" class="block text-gray-700 font-semibold mb-1">
                Harga per Unit (Rp)
            </label>
            <input type="number" name="harga_bahan_pendukung" id="harga_bahan_pendukung"
                   value="{{ old('harga_bahan_pendukung') }}"
                   min="0"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                   placeholder="Masukkan harga bahan pendukung">
        </div>

        <div class="mb-6">
            <label for="satuan" class="block text-gray-700 font-semibold mb-1">
               Satuan
            </label>
            <input type="text" name="satuan" id="satuan"
                   value="{{ old('satuan') }}"
                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                   placeholder="contoh pcs">
        </div>

        {{-- Catatan --}}
        <div class="mb-6">
            <label for="catatan" class="block text-gray-700 font-semibold mb-1">
                Catatan (Opsional)
            </label>
            <textarea name="catatan" id="catatan" rows="3"
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring focus:ring-blue-200"
                      placeholder="Masukkan catatan tambahan jika ada">{{ old('catatan') }}</textarea>
        </div>

        {{-- Tombol --}}
        <div class="flex justify-between items-center">
            <a href="{{ route('gudang.daftarbahanpendukung') }}"
               class="px-4 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg">
                Batal
            </a>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-800 text-white rounded-lg">
                Simpan
            </button>
        </div>
    </form>
</div>
@endsection
