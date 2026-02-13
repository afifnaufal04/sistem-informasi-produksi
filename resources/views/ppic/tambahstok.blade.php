@extends('layouts.allApp')

@section('title', 'Tambah Stok')
@section('role', 'PPIC')

@section('content')
<div class="container mx-auto px-6 py-8">
    <h2 class="text-3xl font-bold text-gray-800 mb-6">Tambah Stok</h2>

    <div class="bg-white shadow-md rounded-lg p-6 mx-auto">
        <form id="tambahStokForm" method="POST" action="{{ route('ppic.qcGagal.store') }}">
            @csrf

            {{-- Pilih Barang --}}
            <div class="mb-4">
                <label for="barang_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Nama Barang
                </label>
                <select id="barang_id" name="barang_id" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Barang --</option>
                    @foreach ($barang as $b)
                        <option value="{{ $b->barang_id }}">{{ $b->nama_barang }}</option>
                    @endforeach
                </select>
                @error('barang_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Pilih Sub Proses --}}
            <div class="mb-4">
                <label for="sub_proses_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Sub Proses
                </label>
                <select id="sub_proses_id" name="sub_proses_id" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">-- Pilih Sub Proses --</option>
                    @foreach ($subProses as $sp)
                        <option value="{{ $sp->sub_proses_id }}">{{ $sp->nama_sub_proses }}</option>
                    @endforeach
                </select>
                @error('sub_proses_id')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Jumlah --}}
            <div class="mb-4">
                <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                    Jumlah
                </label>
                <input type="number" id="jumlah" name="jumlah" min="1" required
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                @error('jumlah')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Asal SPK --}}
            <div class="mb-4">
                <label for="asal_spk" class="block text-sm font-medium text-gray-700 mb-2">
                    Asal SPK (Opsional)
                </label>
                <input type="text" id="asal_spk" name="asal_spk"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2">
                @error('asal_spk')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Catatan --}}
            <div class="mb-6">
                <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan (Opsional)
                </label>
                <textarea id="catatan" name="catatan" rows="3"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 p-2"></textarea>
                @error('catatan')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex justify-end gap-3">
                <a href="{{ route('ppic.qcGagal.view') }}"
                    class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500">Batal</a>
                <button type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
