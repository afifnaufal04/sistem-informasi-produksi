@extends('layouts.allApp')

@section('title', 'Edit Bahan Pendukung')
@section('role', 'PPIC')

@section('content')
<div class="max-w-3xl mx-auto mt-8 mb-10 bg-white p-8 rounded-lg shadow-md">
    <h2 class="text-2xl font-semibold text-center text-gray-800 mb-6">Edit Bahan Pendukung</h2>

    @if(session('success'))
        <div class="mb-4 p-3 rounded-md bg-green-100 text-green-700 border border-green-300">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('ppic.daftarbahanpendukung.update', $bahanPendukung->bahan_pendukung_id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Nama Bahan Pendukung -->
        <div class="mb-5">
            <label for="nama_bahan_pendukung" class="block text-sm font-semibold text-gray-700 mb-2">
                Nama Bahan Pendukung
            </label>
            <input type="text" name="nama_bahan_pendukung" id="nama_bahan_pendukung"
                   value="{{ old('nama_bahan_pendukung', $bahanPendukung->nama_bahan_pendukung) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            @error('nama_bahan_pendukung')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Stok Bahan Pendukung -->
        <div class="mb-5">
            <label for="stok_bahan_pendukung" class="block text-sm font-semibold text-gray-700 mb-2">
                Stok
            </label>
            <input type="number" name="stok_bahan_pendukung" id="stok_bahan_pendukung"
                   value="{{ old('stok_bahan_pendukung', $bahanPendukung->stok_bahan_pendukung) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            @error('stok_bahan_pendukung')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Harga Bahan Pendukung -->
        <div class="mb-5">
            <label for="harga_bahan_pendukung" class="block text-sm font-semibold text-gray-700 mb-2">
                Harga (Rp)
            </label>
            <input type="number" name="harga_bahan_pendukung" id="harga_bahan_pendukung"
                   value="{{ old('harga_bahan_pendukung', $bahanPendukung->harga_bahan_pendukung) }}"
                   class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
            @error('harga_bahan_pendukung')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tombol Aksi -->
        <div class="flex items-center justify-between mt-8">
            <a href="{{ route('ppic.daftarbahanpendukung') }}"
               class="bg-gray-500 text-white px-5 py-2 rounded-lg hover:bg-gray-700 transition">
                Kembali
            </a>

            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition">
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>
@endsection
