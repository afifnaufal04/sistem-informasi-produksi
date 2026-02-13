@extends('layouts.allApp')

@section('title', 'Monitoring Progres Pemesanan')
@section('role', 'PPIC')

@section('content')

<div class="min-h-screen bg-gray-50/50">
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-6">
        <div class="relative">
            <h1 class="text-4xl font-black text-gray-900 tracking-tight">
                Monitoring <span class="text-blue-600">Progres Pemesanan</span>
            </h1>
        </div>
    </div>

    @if($pemesanans->isEmpty())
        <div class="bg-white rounded-lg border-2 border-dashed border-gray-200 p-8 text-center shadow-sm">
            <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-50 rounded-2xl mb-4 transform rotate-3 hover:rotate-0 transition-transform duration-300">
                <svg class="w-10 h-10 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                </svg>
            </div>
            <h3 class="text-xl font-bold text-gray-800">Antrian Kosong</h3>
            <p class="text-gray-400 max-w-sm mx-auto mt-2 text-sm">Saat ini tidak ada pesanan yang sedang dalam proses pengerjaan atau packing.</p>
        </div>
    @else
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
            @foreach ($pemesanans as $pemesanan)
                <div class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-lg hover:shadow-blue-900/5 hover:-translate-y-1 transition-all duration-300 flex flex-col overflow-hidden relative">
                    <!-- Card Header -->
                    <div class="p-4 pb-2 relative z-10">
                        <div class="flex justify-between items-start">
                            <div class="space-y-2">
                                <div class="flex items-center space-x-3">
                                    <span class="bg-gray-900 text-white text-[10px] font-black uppercase tracking-[0.2em] px-3 py-1 rounded-full shadow-lg shadow-gray-200">
                                        #{{ $pemesanan->pemesanan_id }}
                                    </span>
                                    <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider">
                                        {{ \Carbon\Carbon::parse($pemesanan->tanggal_pemesanan)->locale('id') ->translatedFormat('d F Y') }}
                                    </span>
                                </div>
                                <h3 class="text-2xl font-extrabold text-gray-900 group-hover:text-blue-600 transition-colors">
                                    {{ $pemesanan->pembeli->nama_pembeli ?? 'Mitra Eksternal' }}
                                </h3>
                                <div class="flex items-center text-sm font-semibold text-gray-500 bg-gray-50 self-start px-3 py-1 rounded-lg border border-gray-100">
                                    <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    SPK: {{ $pemesanan->no_PO }}
                                </div>
                            </div>

                            <div class="shrink-0">
                                @if($pemesanan->status === 'Selesai')
                                    <div class="flex flex-col items-end">
                                        <span class="inline-flex items-center px-5 py-2 rounded-2xl text-[11px] font-black uppercase tracking-widest bg-green-500 text-white shadow-lg shadow-green-200">
                                            <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                            {{ $pemesanan->status }}
                                        </span>
                                    </div>
                                @else
                                    <div class="flex flex-col items-end">
                                        <span class="inline-flex items-center px-5 py-2 rounded-2xl text-[11px] font-black uppercase tracking-widest bg-amber-400 text-white shadow-lg shadow-amber-200">
                                            <svg class="w-3.5 h-3.5 mr-1.5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                                            Diproses
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Content Body -->
                    <div class="px-4 pb-4 pt-3 flex-grow overflow-y-auto max-h-[380px] scrollbar-hide">
                        <div class="flex items-center justify-between mb-6">
                            <h4 class="text-[11px] font-black text-gray-400 uppercase tracking-[0.2em]">Daftar Inventaris & Progres</h4>
                            <div class="h-px bg-gray-100 flex-grow ml-4"></div>
                        </div>
                        
                        <div class="space-y-4">
                            @forelse ($pemesanan->pemesananBarang as $pb)
                                @php
                                    $packingSelesai = $pb->packing->sum('jumlah_packing');
                                    $progressBarang = $pb->jumlah_pemesanan > 0
                                        ? round(($packingSelesai / $pb->jumlah_pemesanan) * 100)
                                        : 0;
                                @endphp
                                
                                <div class="relative group/item bg-white rounded-lg p-3 border border-gray-100 shadow-sm hover:shadow-md hover:border-blue-100 transition-all duration-200">

                                    <!-- Item Info -->
                                    <div class="flex justify-between items-end mb-2">
                                        <div class="flex items-center">
                                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-blue-50 to-indigo-50 flex items-center justify-center mr-3 group-hover/item:from-blue-600 group-hover/item:to-indigo-600 transition-all">
                                                <svg class="w-5 h-5 text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="block text-sm font-black text-gray-800 leading-none mb-1">
                                                    {{ $pb->barang->nama_barang ?? 'Unknown SKU' }}
                                                </span>
                                                <span class="text-[11px] font-bold text-gray-400 italic">
                                                    Progress: {{ $progressBarang }}%
                                                </span>
                                            </div>
                                        </div>

                                        <div class="text-right">
                                            <div class="text-lg font-black text-gray-900 leading-none">
                                                {{ $packingSelesai }}
                                            </div>
                                            <div class="text-[10px] font-bold text-gray-400 uppercase mt-1">
                                                / {{ $pb->jumlah_pemesanan }} Unit
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Progress Bar -->
                                    <div class="relative w-full bg-gray-100 h-4 rounded-full overflow-hidden mb-3 p-1 border border-gray-50 shadow-inner">
                                        <div class="h-full bg-emerald-600 rounded-full transition-all duration-1000 shadow-[0_0_14px_rgba(16,185,129,0.8)]" style="width: {{ min(100, $progressBarang) }}%">
                                            <div class="absolute inset-0 bg-white/15 animate-pulse"></div>
                                        </div>
                                    </div>

                                    <!-- Riwayat Penyelesaian -->
                                    @if($pb->packing->isNotEmpty())
                                        <div class="mt-3">
                                            <div class="flex items-center text-[10px] font-black text-blue-500 mb-2 tracking-widest">
                                                <svg class="w-3 h-3 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                RIWAYAT PENYELESAIAN
                                            </div>

                                            <div class="space-y-1">
                                                @foreach($pb->packing->take(3) as $pack)
                                                    <div class="flex justify-between items-center text-[11px]">
                                                        <div class="flex items-center text-gray-700">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 mr-2"></span>
                                                            <span class="font-bold">
                                                                {{ $pack->jumlah_packing }}
                                                                <span class="font-medium text-gray-400">
                                                                    Unit {{ $pb->barang->nama_barang ?? 'Unknown SKU' }} selesai
                                                                </span>
                                                            </span>
                                                        </div>

                                                        <span class="text-[10px] font-bold text-gray-400 italic">
                                                            {{ \Carbon\Carbon::parse($pack->created_at)->locale('id')->diffForHumans() }}
                                                        </span>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>

                            @empty
                                <div class="text-center py-10">
                                    <p class="text-sm text-gray-400 font-bold italic tracking-wide">Data inventaris barang tidak ditemukan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
</div>
@endsection