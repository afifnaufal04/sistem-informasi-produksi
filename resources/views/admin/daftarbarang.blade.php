@extends('layouts.allApp')

@section('title', 'Daftar Barang')
@section('role', 'Admin')

@section('content')
    <div class="container mx-auto px-6 w-full">
        <h1 class="text-4xl font-bold text-center mb-4">Daftar Barang</h1>

        @if (session('success'))
            <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span onclick="document.getElementById('alert-success').remove();"
                    class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">&times;</span>
            </div>
        @endif

        <div class="grid gap-4 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
            @forelse ($barangs as $barang)
                <div class="bg-white rounded-xl shadow-lg ring-1 ring-gray-200 overflow-hidden flex flex-col relative">
                    <div class="p-3 flex justify-center">
                        <div class="h-20 w-20 rounded-md overflow-hidden border border-gray-200 shadow-sm flex items-center justify-center transform transition duration-150 ease-out hover:shadow-md hover:border-gray-300 hover:scale-105 cursor-pointer group focus:outline-none focus:ring-2 focus:ring-green-200 js-preview-wrapper"
                            role="button" tabindex="0" aria-label="Preview image wrapper"
                            data-src="@if($barang->gambar_barang){{ asset('storage/' . $barang->gambar_barang) }}@endif">
                            @if($barang->gambar_barang)
                                <img src="{{ asset('storage/' . $barang->gambar_barang) }}"
                                    data-src="{{ asset('storage/' . $barang->gambar_barang) }}" alt="Gambar Barang"
                                    class="h-full w-full object-contain cursor-pointer js-preview-image transition-transform duration-150 ease-out group-hover:scale-105"
                                    role="img">
                            @else
                                <div class="h-full w-full bg-gray-100 flex items-center justify-center text-gray-400">No Image</div>
                            @endif
                        </div>
                    </div>

                    <div class="px-4 text-center flex-1">
                        <h3 class="text-sm font-semibold text-gray-900 mb-1 truncate">{{ $barang->nama_barang }}</h3>
                        <div class="text-xs text-gray-600 mb-2">Jenis: <span
                                class="font-medium">{{ $barang->jenis_barang }}</span></div>
                        <div class="text-xs text-gray-600 mb-2">Dimensi: <span class="font-medium">{{ $barang->panjang }} x
                                {{ $barang->lebar }} x {{ $barang->tinggi }}</span></div>
                        <div class="text-xs text-gray-600 mb-2">Stok: <span
                                class="font-medium">{{ $barang->stok_gudang }}</span></div>
                        <div class="text-sm text-green-700 font-semibold mb-2">Rp
                            {{ number_format($barang->harga_barang, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="px-4 pb-4 flex flex-col items-center gap-2">

                        <div class="w-full flex justify-center gap-2">
                            <a href=""
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium shadow-sm bg-yellow-400 hover:bg-yellow-500 text-white">Edit</a>
                            <form action="{{ route('marketing.barang.destroy', $barang->barang_id) }}" method="POST"
                                data-confirm-delete="1" data-swal-text="Yakin hapus barang ini?" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium shadow-sm bg-red-600 hover:bg-red-700 text-white">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 sm:col-span-3 md:col-span-4 text-center text-gray-500 py-6">Belum ada data barang</div>
            @endforelse
        </div>
    </div>
@endsection