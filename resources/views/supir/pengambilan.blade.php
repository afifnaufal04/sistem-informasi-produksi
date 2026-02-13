{{-- resources/views/supir/pengambilan/index.blade.php --}}
@extends('layouts.allApp')

@section('title', 'Perjalanan Pengambilan')
@section('role', 'Supir')

@section('content')
    <div class="container mx-auto px-4 sm:px-6 py-3">

    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800 tracking-tight">Status Perjalanan</h1>
            <p class="text-gray-500 mt-1 text-sm md:text-base">Kelola tugas pengambilan logistik aktif Anda.</p>
        </div>
    </div>

    {{-- Container Utama --}}
    <div class="space-y-4 md:space-y-0 md:grid md:grid-cols-2 lg:grid-cols-3 md:gap-6 items-start">
        @forelse ($pengambilan->sortBy('pengambilan_id') as $item)
            @if($item->status !== 'Selesai')
                
                {{-- CARD WRAPPER --}}
                <div class="group bg-white rounded-3xl border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden">
                    {{-- Header/Ribbon - Lebih Cerah --}}
                    <div class="bg-green-600 px-6 py-3 md:py-4 flex justify-between items-center">
                        <div class="flex items-center space-x-2">
                            <div class="w-2 h-2 bg-white rounded-full"></div>
                            <span class="text-white font-bold text-[10px] md:text-xs uppercase tracking-wider">Tugas #{{ $item->pengambilan_id }}</span>
                        </div>
                    </div>
                    <div class="p-4 md:p-6">
                        <div class="flex flex-col space-y-5">
                            {{-- Section 1: Kendaraan & QC --}}
                            <div class="flex flex-row gap-3 md:gap-4">
                                <div class="flex-1 flex items-center p-3 bg-gray-50 rounded-2xl border border-gray-100">
                                    <div class="w-8 h-8 md:w-10 md:h-10 bg-indigo-100 rounded-xl flex items-center justify-center text-indigo-600 mr-3 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1m-4 0h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[9px] font-medium text-gray-500 uppercase mb-0.5">Kendaraan</p>
                                        <p class="text-xs md:text-sm font-bold text-gray-800 truncate">
                                            {{ $item->kendaraan->nama ?? '-' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="flex-1 flex items-center p-3 bg-gray-50 rounded-2xl border border-gray-100">
                                    <div class="w-8 h-8 md:w-10 md:h-10 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 mr-3 shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[9px] font-medium text-gray-500 uppercase mb-0.5">QC</p>
                                        <p class="text-xs md:text-sm font-bold text-gray-800 truncate">
                                            {{ $item->qc->name ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>


                            {{-- Section 2: Items --}}
                            <div>
                                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Daftar Barang</h3>
                                <div class="space-y-2 max-h-48 overflow-y-auto pr-1 custom-scrollbar">
                                    @foreach($item->detailPengambilan as $detail)
                                        <div class="p-3 bg-white rounded-xl border border-gray-100 flex justify-between items-center transition-colors hover:bg-gray-50">
                                            <div class="min-w-0 flex-1">
                                                <p class="font-bold text-gray-800 text-xs md:text-sm leading-tight truncate">
                                                    {{ $detail->detailPengiriman->produksi->barang->nama_barang ?? '-' }}
                                                </p>
                                                <p class="text-[10px] text-gray-400 truncate">
                                                    {{ $detail->detailPengiriman->supplier->name ?? 'Supplier' }}
                                                </p>
                                            </div>
                                            <div class="text-right ml-4 shrink-0">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-100">
                                                    {{ $detail->jumlah_diambil }} pcs
                                                </span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Section 3: Action --}}
                            @if($item->status === 'Diambil')
                                <div class="pt-2">
                                    <form action="{{ route('supir.pengambilan.selesai', $item->pengambilan_id) }}" method="POST">
                                        @csrf
                                        <button type="submit" onclick="return confirm('Apakah Anda yakin sudah sampai di pabrik?')" class="w-full py-3.5 bg-indigo-600 text-white rounded-2xl font-bold text-xs uppercase tracking-wider shadow-sm hover:bg-indigo-700 active:bg-indigo-800 transition-all flex items-center justify-center space-x-2">
                                            <span>Selesaikan</span>
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif
        @empty
            <div class="col-span-full py-20 text-center">
                <div class="inline-flex p-5 bg-gray-50 rounded-full mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Tidak ada tugas aktif</h3>
                <p class="text-gray-500 text-sm mt-1">Semua pengambilan telah diselesaikan.</p>
            </div>
        @endforelse
    </div>
</div>

<style>
    @keyframes fade-up {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .container { animation: fade-up 0.4s ease-out forwards; }
    
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }
</style>
@endsection