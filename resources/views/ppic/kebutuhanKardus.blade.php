@extends('layouts.allApp')

@section('title', 'Kebutuhan Kardus')
@section('role', 'PPIC')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-6 gap-4">
            <div>
                <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">
                    Kebutuhan Kardus Pemesanan
                </h2>
                <p class="text-gray-500 mt-1">Estimasi otomatis berdasarkan jumlah pemesanan barang aktif.</p>
            </div>
        </div>

        <!-- Stats Overview (Optional but Elegant) -->
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Jenis Barang</p>
                <p class="text-2xl font-extrabold text-gray-900">{{ count($dataKebutuhan) }}</p>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-xs font-bold text-gray-400 uppercase tracking-wider">Total Kebutuhan Kardus</p>
                <p class="text-2xl font-extrabold text-green-600">
                    {{ collect($dataKebutuhan)->sum('total_kardus') }}
                </p>
            </div>
        </div>

        <!-- Card Container -->
        <div class="grid gap-4 grid-cols-1 lg:grid-cols-2">
            @forelse ($dataKebutuhan as $index => $item)
                <div
                    class="group bg-white border border-gray-100 rounded-2xl p-5 hover:border-green-300 hover:shadow-xl hover:shadow-blue-500/5 transition-all duration-300 relative overflow-hidden">
                    <!-- Decorative index number -->
                    <div
                        class="absolute -right-4 -top-4 text-6xl font-black text-gray-50 opacity-[0.03] group-hover:opacity-[0.07] transition-opacity">
                        {{ $index + 1 }}
                    </div>

                    <div class="flex flex-col md:flex-row md:items-center gap-6 relative z-10">
                        <!-- Column 1: Item Info -->
                        <div class="flex-1">
                            <div class="flex items-center gap-3 mb-2">
                                <span
                                    class="flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 text-sm font-bold">
                                    {{ $index + 1 }}
                                </span>
                                <span
                                    class="text-xl font-bold text-gray-800 group-hover:text-green-600 transition-colors">{{ $item['nama_barang'] }}</span>
                            </div>
                            <div class="flex flex-wrap items-center gap-y-2 gap-x-4 mt-3">
                                <div class="flex items-center gap-1.5 text-sm text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <span>Ukuran: <span
                                            class="font-bold text-green-600">{{ $item['ukuran_kardus'] }}</span></span>
                                </div>
                                <div class="flex items-center gap-1.5 text-sm text-gray-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-gray-400" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                    </svg>
                                    <span>Per Kardus isi <span class="font-bold text-green-600">{{ $item['kardus_per_barang'] }}
                                            barang</span></span>
                                </div>
                            </div>
                        </div>

                        <!-- Column 2: Quantities -->
                        <div
                            class="w-full md:w-auto flex items-center justify-between md:justify-start gap-6 md:pl-8 border-t md:border-t-0 md:border-l border-gray-100 pt-4 md:pt-0">
                            <div class="text-center">
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter mb-1">Pesanan</p>
                                <p class="text-lg font-bold text-gray-700">{{ $item['jumlah_pemesanan'] }}</p>
                            </div>
                            <div class="flex flex-col items-center justify-center bg-emerald-50 px-6 py-3 rounded-2xl">
                                <p class="text-[10px] font-bold text-green-400 uppercase tracking-tighter mb-1">Total Kebutuhan
                                </p>
                                <p class="text-2xl font-black text-green-600 leading-none">
                                    {{ $item['total_kardus'] }}
                                    <span class="text-xs font-medium ml-1">Pcs</span>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white border-2 border-dashed border-gray-200 rounded-3xl p-12 text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700">Data Tidak Ditemukan</h3>
                    <p class="text-gray-500 mt-1">Belum ada rincian kebutuhan kardus yang tersedia saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection