@extends('layouts.allApp')

@section('title', 'Pelacakan Progres Produksi')
@section('role', 'Quality Control')

@section('content')
<div class="container mx-auto px-6 w-full">
    <h2 class="text-3xl font-bold mb-6">Pelacakan Progres Produksi</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 p-4">
    @forelse ($matrix as $index => $row)
        <div class="bg-white rounded-xl shadow-xl overflow-hidden border border-gray-100 hover:shadow-2xl transition duration-300">
            <div class="p-5">
                <div class="flex justify-between items-start mb-4">
                    <h3 class="text-xl font-bold text-green-600">Progres #{{ $index + 1 }}</h3>
                    <span class="inline-block bg-blue-100 text-blue-700 text-xs font-semibold px-3 py-1 rounded-full">
                        {{ $row['status_produksi'] }}
                    </span>
                </div>

                <h2 class="text-2xl font-extrabold text-gray-800 mb-1">{{ $row['nama_barang'] }}</h2>
                <p class="text-lg text-gray-600 mb-1">Jumlah: <span class="font-bold">{{ $row['jumlah_produksi'] }}</span></p>
                <p class="text-lg text-gray-600 mb-4">Jenis Barang: <span class="font-bold">{{ $row['jenis_barang'] }}</span></p>

                <div class="grid grid-cols-2 gap-2 mb-4 text-sm">
                    @foreach ($subProsesList as $sp)
                        <div class="flex justify-between bg-gray-50 px-3 py-1 rounded-lg">
                            <span class="text-gray-600">{{ $sp->nama_sub_proses }}</span>
                            <span class="font-bold text-gray-800">
                                {{ $row['subproses'][$sp->sub_proses_id] ?? 0 }}
                            </span>
                        </div>
                    @endforeach
                </div>
                <hr class="my-4 border-gray-100">
            </div>
        </div>
        @empty
        {{-- Empty state (tidak ada progres) di sini --}}
        @endforelse
    </div>
</div>
@endsection
