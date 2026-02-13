@extends('layouts.allApp')

@section('title', 'Daftar Pembeli')
@section('role', 'Admin')

@section('content')
    <div class="container mx-auto px-6 w-full">
        <h1 class="text-4xl font-bold text-center mb-4">Daftar Pembeli</h1>

        @if (session('success'))
            <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span onclick="document.getElementById('alert-success').remove();"
                    class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">&times;</span>
            </div>
        @endif

        <div class="flex justify-end mb-6">
            <a href="{{ route('admin.pembeli.create') }}"
                class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg">Tambah Pembeli</a>
        </div>

        <div class="grid gap-4 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
            @forelse ($buyers as $pembeli)
                <div class="bg-white rounded-xl shadow-lg ring-1 ring-gray-200 overflow-hidden flex flex-col relative">
                    <div class="px-4 text-center flex-1">
                        <h3 class="text-sm font-semibold text-gray-900 mb-1 truncate">{{ $pembeli->nama_pembeli }}</h3>
                        <div class="text-xs text-gray-600 mb-2">Kode: <span
                                class="font-medium">{{ $pembeli->kode_buyer }}</span></div>
                    </div>

                    <div class="px-4 pb-4 flex flex-col items-center gap-2">

                        <div class="w-full flex justify-center gap-2">
                            <a href=""
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium shadow-sm bg-yellow-400 hover:bg-yellow-500 text-white">Edit</a>
                            <form action="{{ route('admin.pembeli.destroy', $pembeli->pembeli_id) }}" method="POST"
                                data-confirm-delete="1" data-swal-text="Yakin hapus pembeli ini?" class="inline-block">
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