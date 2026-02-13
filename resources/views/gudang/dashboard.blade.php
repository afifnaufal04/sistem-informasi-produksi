{{-- resources/views/gudang/dashboard.blade.php --}}
@extends('layouts.allApp')

@section('title', 'Dashboard Gudang Modern')
@section('role', 'Gudang')

@section('content')
<!-- Impor Font & Ikon -->
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        -webkit-backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.05);
    }
    .gradient-blue {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    }
    .gradient-red {
        background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
    }
    .dark-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    }
    .status-pulse {
        animation: pulse-animation 2s infinite;
    }
    @keyframes pulse-animation {
        0% { box-shadow: 0 0 0 0px rgba(59, 130, 246, 0.4); }
        100% { box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
    }
    .card-hover:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease;
    }
</style>

<div class="container mx-auto px-6 max-w-7xl">
    
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-3 gap-6">
        <div>
            <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Dashboard Gudang</h1>
            <p class="text-slate-500 font-medium italic mt-1">Selamat datang kembali, <span class="text-blue-600 font-bold">{{ Auth::user()->name }}</span>.</p>
            <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-600 rounded-full border border-blue-100 mb-4">
                <div class="h-2 w-2 bg-blue-600 rounded-full status-pulse"></div>
                <span class="text-[10px] font-bold uppercase tracking-widest">Inventory Live Control</span>
            </div>
        </div>
        
    </div>

    <!-- Main KPIs -->
    <div class="grid grid-cols-2 md:grid-cols-2 gap-4 md:gap-8 mb-10">

        <!-- Total Pemesanan -->
        <div class="gradient-blue rounded-[3rem] p-8 text-white relative overflow-hidden group card-hover shadow-2xl shadow-blue-200">
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <i class="ph-fill ph-shopping-cart text-[12rem]"></i>
            </div>
            <div class="relative z-10">
                <div class="h-14 w-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6 border border-white/30">
                    <i class="ph-bold ph-clipboard-text text-2xl"></i>
                </div>
                <p class="text-blue-100 font-bold text-xs uppercase tracking-[0.2em] mb-1">Total Pemesanan Masuk</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl md:text-6xl font-black">{{ $pemesanan }}</h3>
                    <span class="text-sm font-medium text-blue-200">Transaksi</span>
                </div>
            </div>
        </div>

        <!-- Butuh Bahan Pendukung -->
        <div class="gradient-red rounded-[3rem] p-8 text-white relative overflow-hidden group card-hover shadow-2xl shadow-red-200">
            <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                <i class="ph-fill ph-warning-diamond text-[12rem]"></i>
            </div>
            <div class="relative z-10">
                <div class="h-14 w-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6 border border-white/30">
                    <i class="ph-bold ph-bell-ringing text-2xl"></i>
                </div>
                <p class="text-red-100 font-bold text-xs uppercase tracking-[0.2em] mb-1">Urgent: Butuh Bahan Pendukung</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-4xl md:text-6xl font-black">{{ $detailPengirimanButuhBP }}</h3>
                    <span class="text-sm font-medium text-red-200">Item</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Stok & Order Sections -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <div class="lg:col-span-7 glass-card rounded-[3.5rem] p-5 md:p-8 lg:p-10">
            <div class="flex items-center justify-between mb-5 md:mb-8 lg:mb-10">
                <h2 class="text-base sm:text-lg lg:text-xl font-extrabold text-slate-800 flex items-center gap-2 md:gap-3">
                    <i class="ph-fill ph-stack text-blue-600 bg-blue-50 p-1.5 md:p-2 rounded-xl"></i>
                    Stok Bahan Pendukung
                </h2>
                <a href="{{ route('gudang.daftarbahanpendukung') }}" class="text-[10px] sm:text-xs font-bold text-blue-600 hover:underline">
                    Lihat Semua
                </a>
            </div>
            <div class="grid grid-cols-2 gap-3 md:gap-6">
                <div class="bg-slate-50 border border-slate-100 p-4 md:p-6 lg:p-8 rounded-[2.5rem] relative overflow-hidden group">
                    <p class="text-slate-400 text-[9px] sm:text-[10px] font-black uppercase tracking-widest mb-1 md:mb-2">
                        Total Ketersediaan
                    </p>
                    <p class="text-2xl sm:text-3xl lg:text-4xl font-black text-slate-900">
                        {{ $stokBP }}
                        <span class="text-sm sm:text-base lg:text-lg text-slate-400">
                            Unit
                        </span>
                    </p>
                    <div class="mt-3 md:mt-4 h-1 md:h-1.5 w-16 md:w-24 bg-blue-600 rounded-full"></div>
                </div>
                <div class="bg-red-50/50 border border-red-100 p-4 md:p-6 lg:p-8 rounded-[2.5rem] relative">
                    <p class="text-red-400 text-[9px] sm:text-[10px] font-black uppercase tracking-widest mb-1 md:mb-2">
                        Out of Stock
                    </p>
                    <p class="text-2xl sm:text-3xl lg:text-4xl font-black text-red-600">
                        {{ $stokBPHabis }}
                        <span class="text-sm sm:text-base lg:text-lg text-red-300">
                            Item
                        </span>
                    </p>
                    <div class="mt-3 md:mt-4 flex items-center gap-1.5 md:gap-2 text-[9px] sm:text-[10px] font-bold text-red-500 uppercase">
                        <i class="ph-bold ph-info"></i>
                        Perlu Reorder
                    </div>
                </div>
            </div>
        </div>


        <!-- Order BP Stats Section -->
        <div class="lg:col-span-5 dark-card rounded-[3.5rem] p-10 text-white shadow-2xl">
            <h2 class="text-xl font-bold mb-8 flex items-center gap-3">
                <i class="ph ph-shopping-bag-open text-blue-400"></i>
                Status Order BP
            </h2>
            
            <div class="space-y-6">
                <div class="flex items-center justify-between p-5 bg-white/5 rounded-3xl border border-white/5">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 bg-blue-500/20 text-blue-400 rounded-xl flex items-center justify-center">
                            <i class="ph-bold ph-list-numbers text-xl"></i>
                        </div>
                        <span class="font-bold text-sm text-slate-300">Total Order</span>
                    </div>
                    <span class="text-2xl font-black">{{ $orderBP }}</span>
                </div>

                <div class="flex items-center justify-between p-5 bg-white/5 rounded-3xl border border-white/5">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 bg-amber-500/20 text-amber-400 rounded-xl flex items-center justify-center">
                            <i class="ph-bold ph-clock-countdown text-xl"></i>
                        </div>
                        <span class="font-bold text-sm text-slate-300">Dalam Proses</span>
                    </div>
                    <span class="text-2xl font-black text-amber-400">{{ $orderBPDalamProses }}</span>
                </div>

                <div class="flex items-center justify-between p-5 bg-blue-600 rounded-3xl shadow-lg shadow-blue-900/40">
                    <div class="flex items-center gap-4">
                        <div class="h-10 w-10 bg-white/20 rounded-xl flex items-center justify-center">
                            <i class="ph-bold ph-check-square text-xl"></i>
                        </div>
                        <span class="font-bold text-sm">Order Selesai</span>
                    </div>
                    <span class="text-2xl font-black">{{ $orderBPSelesai }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection