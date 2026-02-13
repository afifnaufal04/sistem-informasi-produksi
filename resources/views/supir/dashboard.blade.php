{{-- resources/views/supir/dashboard.blade.php --}}
@extends('layouts.allApp')

@section('title', 'Dashboard Supir')
@section('role', 'Supir')

@section('content')
<style>
    .dark-card {
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
    }
</style>
<div class="container mx-auto px-6 py-3">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight leading-none">Dashboard Logistik</h1>
            <p class="text-gray-500 mt-2 text-lg font-medium">Pantau rute dan selesaikan tugas pengiriman Anda hari ini.</p>
        </div>
    </div>

    {{-- Pengiriman & Pengambilan Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 mb-6 md:mb-8">
        
        {{-- Pengiriman Section --}}
        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 transition-all hover:shadow-xl">
            <div class="flex items-center justify-between mb-5 md:mb-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-2xl mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-gray-800 tracking-tight">Pengiriman</h2>
                        <p class="text-[10px] font-bold text-blue-500 uppercase">Antar Produk ke Customer</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-4xl font-black text-gray-900 tracking-tighter">{{ $pengiriman }}</span>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Total</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 md:gap-4">
                <div class="p-5 bg-blue-50/50 rounded-3xl border border-blue-100/50">
                    <p class="text-[10px] font-black text-blue-600 uppercase mb-1">Selesai</p>
                    <h4 class="text-2xl font-black text-gray-800">{{ $pengirimanSelesai }}</h4>
                </div>
                <div class="p-5 bg-amber-50/50 rounded-3xl border border-amber-100/50">
                    <p class="text-[10px] font-black text-amber-600 uppercase mb-1">Proses</p>
                    <h4 class="text-2xl font-black text-gray-800">{{ $pengirimanDalamProses }}</h4>
                </div>
            </div>
            
            {{-- Progress Bar --}}
            <div class="mt-8">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-[10px] font-black text-gray-400 uppercase">Delivery Progress</span>
                    <span class="text-[10px] font-black text-blue-600 uppercase">{{ $pengiriman > 0 ? round(($pengirimanSelesai / $pengiriman) * 100) : 0 }}%</span>
                </div>
                <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-blue-600 h-full rounded-full transition-all duration-1000" style="width: {{ $pengiriman > 0 ? ($pengirimanSelesai / $pengiriman) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>

        {{-- Pengambilan Section --}}
        <div class="bg-white p-6 md:p-8 rounded-2xl shadow-sm border border-gray-100 transition-all hover:shadow-xl">
            <div class="flex items-center justify-between mb-5 md:mb-6">
                <div class="flex items-center">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-2xl mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-gray-800 tracking-tight">Pengambilan</h2>
                        <p class="text-[10px] font-bold text-indigo-500 uppercase">Ambil Bahan dari Supplier</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-4xl font-black text-gray-900 tracking-tighter">{{ $pengambilan }}</span>
                    <p class="text-[10px] font-bold text-gray-400 uppercase">Total</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 md:gap-4">
                <div class="p-5 bg-emerald-50/50 rounded-3xl border border-emerald-100/50">
                    <p class="text-[10px] font-black text-emerald-600 uppercase mb-1">Selesai</p>
                    <h4 class="text-2xl font-black text-gray-800">{{ $pengambilanSelesai }}</h4>
                </div>
                <div class="p-5 bg-amber-50/50 rounded-3xl border border-amber-100/50">
                    <p class="text-[10px] font-black text-amber-600 uppercase mb-1">Proses</p>
                    <h4 class="text-2xl font-black text-gray-800">{{ $pengambilanDalamProses }}</h4>
                </div>
            </div>

            {{-- Progress Bar --}}
            <div class="mt-8">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-[10px] font-black text-gray-400 uppercase">Pickup Progress</span>
                    <span class="text-[10px] font-black text-indigo-600 uppercase">{{ $pengambilan > 0 ? round(($pengambilanSelesai / $pengambilan) * 100) : 0 }}%</span>
                </div>
                <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                    <div class="bg-indigo-600 h-full rounded-full transition-all duration-1000" style="width: {{ $pengambilan > 0 ? ($pengambilanSelesai / $pengambilan) * 100 : 0 }}%"></div>
                </div>
            </div>
        </div>
    </div>

    {{-- Ringkasan Performa Hari Ini --}}
    <div class="relative group p-6 md:p-8 lg:p-10 rounded-2xl dark-card text-white overflow-hidden">
    <div class="flex items-center mb-6 md:mb-8">
        <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mr-4 shadow-inner">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 640 512" fill="currentColor">
                <path d="M64 96c0-35.3 28.7-64 64-64l288 0c35.3 0 64 28.7 64 64l0 32 50.7 0c17 0 33.3 6.7 45.3 18.7L621.3 192c12 12 18.7 28.3 18.7 45.3L640 384c0 35.3-28.7 64-64 64l-3.3 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-102.6 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-3.3 0c-35.3 0-64-28.7-64-64l0-48-40 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 240c-13.3 0-24-10.7-24-24s10.7-24 24-24l176 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 144c-13.3 0-24-10.7-24-24S10.7 96 24 96l40 0zM576 288l0-50.7-45.3-45.3-50.7 0 0 96 96 0zM256 424a40 40 0 1 0 -80 0 40 40 0 1 0 80 0zm232 40a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/>
            </svg>
                    </div>
        <h3 class="text-2xl font-black tracking-tight">
            Ringkasan Operasional
        </h3>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6">
        <div>
            <span class="text-[11px] font-black text-indigo-400 uppercase tracking-[0.2em]">
                Total Tugas
            </span>
            <div class="text-5xl font-black mt-2">
                {{ $pengiriman + $pengambilan }}
            </div>
        </div>

        <div>
            <span class="text-[11px] font-black text-emerald-400 uppercase tracking-[0.2em]">
                Total Selesai
            </span>
            <div class="text-5xl font-black mt-2">
                {{ $pengirimanSelesai + $pengambilanSelesai }}
            </div>
        </div>
        <div>
            <span class="text-[11px] font-black text-amber-400 uppercase tracking-[0.2em]">
                Masih Berjalan
            </span>
            <div class="text-5xl font-black mt-2">
                {{ $pengirimanDalamProses + $pengambilanDalamProses }}
            </div>
        </div>

        <div class="flex items-center justify-center bg-black/40 border border-white/10 rounded-2xl">
            <div class="text-center">
                <span class="text-[11px] font-black text-indigo-300 uppercase tracking-[0.2em]">
                    Presentasi
                </span>
                <div class="text-4xl font-black mt-2">
                    {{ ($pengiriman + $pengambilan) > 0
                        ? round((($pengirimanSelesai + $pengambilanSelesai) / ($pengiriman + $pengambilan)) * 100)
                        : 0 }}%
                </div>
            </div>
        </div>
    </div>
</div>

</div>
@endsection