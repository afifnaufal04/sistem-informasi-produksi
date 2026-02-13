@extends('layouts.allApp')

@section('title', 'Tambah Progres Produksi')
@section('role', 'Kepala Produksi')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Tambah Progres Produksi</h2>

    <div class="bg-white shadow-md rounded-lg p-6 mx-auto">
        <form id="progresForm" method="POST" action="{{ route('keprod.progres.store') }}">
            @csrf

            {{-- Pilih Barang --}}
            <div class="mb-4">
                <label for="barang_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Barang
                </label>
                <select id="barang_id" name="barang_id" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Barang --</option>
                    @foreach ($barangs as $b)
                        <option value="{{ $b->barang_id }}">{{ $b->nama_barang }}</option>
                    @endforeach
                </select>
                @error('barang_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jumlah Produksi --}}
            <div class="mb-4">
                <label for="jumlah_produksi" class="block text-sm font-medium text-gray-700 mb-2">
                    Jumlah Produksi
                </label>
                <input type="number" id="jumlah" name="jumlah_produksi" min="1" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                @error('jumlah_produksi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('keprod.progres.index') }}"
                    class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500">Batal</a>

                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Simpan</button>
            </div>

        </form>
    </div>
</div>
@endsection
