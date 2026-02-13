@extends('layouts.allApp')

@section('title', 'Tambah Produksi')
@section('role', 'Kepala Produksi')

@section('content')
<div class="max-w-3xl mx-auto p-6 bg-white shadow-md rounded-lg">

    <h2 class="text-2xl font-bold mb-4">Tambah Produksi</h2>

    <form action="{{ route('keprod.produksi.store') }}" method="POST">
        @csrf

        {{-- Barang --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-1">Pilih Barang</label>
            <select name="barang_id" class="w-full border rounded px-3 py-2" required>
                <option value="">-- Pilih Barang --</option>
                @foreach ($barangs as $barang)
                    <option value="{{ $barang->barang_id }}">{{ $barang->nama_barang }} ( {{ $barang->jenis_barang }} )</option>
                @endforeach
            </select>
        </div>

        {{-- Jumlah --}}
        <div class="mb-4">
            <label class="block text-gray-700 font-semibold mb-1">Target Produksi</label>
            <input type="number" name="jumlah_produksi" class="w-full border rounded px-3 py-2" required min="1">
        </div>

        <button type="submit" 
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded shadow">
            Simpan
        </button>

        <a href="{{ route('keprod.progres.index') }}" 
            class="ml-2 text-gray-600 hover:text-gray-800">
            Batal
        </a>
    </form>

</div>
@endsection
