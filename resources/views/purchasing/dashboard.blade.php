{{-- resources/views/purchasing/dashboard.blade.php --}}
@extends('layouts.allApp')

@section('title', 'Dashboard Purchasing')
@section('role', 'Purchasing')

@section('content')
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<style>
    .gradient-blue {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
    }
    .gradient-red {
        background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%);
    }
    .card-hover:hover {
        transform: translateY(-5px);
        transition: all 0.3s ease;
    }
</style>
<div class="container mx-auto px-6 py-3">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight leading-none">Dashboard Purchasing</h1>
            <p class="text-gray-500 mt-2 text-lg font-medium">Kelola pengadaan bahan dan pantau status pembayaran.</p>
        </div>
    </div>

    {{-- Inventory Section --}}
    <div class="mb-12">
        <div class="flex items-center mb-6">
            <div class="w-1.5 h-8 bg-purple-600 rounded-full mr-4"></div>
            <h2 class="text-xl font-extrabold text-gray-800 tracking-tight">Kondisi Stok Bahan Pendukung</h2>
        </div>
        
        <div class="grid grid-cols-2 md:grid-cols-2 gap-4 md:gap-8 mb-10">
            <!-- Total Stok BP -->
            <div class="gradient-blue rounded-[3rem] p-8 text-white relative overflow-hidden group card-hover shadow-2xl shadow-blue-200">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                    <i class="ph-fill ph-package text-[12rem]"></i>
                </div>
                <div class="relative z-10">
                    <div class="h-14 w-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6 border border-white/30">
                        <i class="ph-bold ph-clipboard-text text-2xl"></i>
                    </div>
                    <p class="text-blue-100 font-bold text-xs uppercase tracking-[0.2em] mb-1">Total Stok BP</p>
                    <div class="flex flex-col md:flex-row md:items-baseline md:gap-2">
                        <h3 class="text-4xl md:text-6xl font-black">{{ $stokBP }}</h3>
                        <span class="text-sm font-medium text-blue-200">
                            Item Bahan Pendukung
                        </span>
                    </div>
                </div>
            </div>

            <!--  Stok Habis -->
            <div class="gradient-red rounded-[3rem] p-8 text-white relative overflow-hidden group card-hover shadow-2xl shadow-red-200">
                <div class="absolute -right-4 -bottom-4 opacity-10 group-hover:scale-110 transition-transform duration-500">
                    <i class="ph-fill ph-warning-diamond text-[12rem]"></i>
                </div>
                <div class="relative z-10">
                    <div class="h-14 w-14 bg-white/20 backdrop-blur-md rounded-2xl flex items-center justify-center mb-6 border border-white/30">
                        <i class="ph-bold ph-bell-ringing text-2xl"></i>
                    </div>
                    <p class="text-red-100 font-bold text-xs uppercase tracking-[0.2em] mb-1">Item perlu Restock</p>
                    <div class="flex flex-col md:flex-row md:items-baseline md:gap-2">
                        <h3 class="text-4xl md:text-6xl font-black">{{ $stokBPHabis }}</h3>
                        <span class="text-sm font-medium text-red-200">
                            Item Bahan Pendukung
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Orders Management --}}
    <div class="mb-6 md:mb-8">
        <div class="flex items-center mb-6">
            <div class="w-1.5 h-8 bg-blue-600 rounded-full mr-4"></div>
            <h2 class="text-xl font-extrabold text-gray-800 tracking-tight">Manajemen Order</h2>
        </div>
        
        <div class="grid grid-cols-3 md:grid-cols-3 gap-4 md:gap-8">
            <div class="bg-white p-4 md:p-6 rounded-[2rem] shadow-sm border border-gray-100 transition-shadow text-center md:hover:shadow-md">
                <p class="text-[9px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">
                    Total Order
                </p>
                <h4 class="text-xl sm:text-2xl md:text-3xl font-black text-blue-600">
                    {{ $orderBP }}
                </h4>
                <div class="mt-3 md:mt-4 h-1 w-10 md:w-12 bg-blue-100 rounded-full mx-auto"></div>
            </div>

            <div class="bg-white p-4 md:p-6 rounded-[2rem] shadow-sm border border-gray-100 transition-shadow text-center md:hover:shadow-md">
                <p class="text-[9px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">
                    Dalam Proses
                </p>
                <h4 class="text-xl sm:text-2xl md:text-3xl font-black text-amber-500">
                    {{ $orderBPDalamProses }}
                </h4>
                <div class="mt-3 md:mt-4 h-1 w-10 md:w-12 bg-amber-100 rounded-full mx-auto"></div>
            </div>

            <div class="bg-white p-4 md:p-6 rounded-[2rem] shadow-sm border border-gray-100 transition-shadow text-center md:hover:shadow-md">
                <p class="text-[9px] sm:text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">
                    Telah Diterima
                </p>
                <h4 class="text-xl sm:text-2xl md:text-3xl font-black text-emerald-600">
                    {{ $orderBPSelesai }}
                </h4>
                <div class="mt-3 md:mt-4 h-1 w-10 md:w-12 bg-emerald-100 rounded-full mx-auto"></div>
            </div>
        </div>
    </div>

    {{-- Payment Status Section --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <!-- Header -->
        <div class="p-4 md:p-6 border-b border-gray-50
                    flex justify-between items-center bg-gray-50/30">
            <div class="flex items-center">
                <div class="w-10 h-10 md:w-12 md:h-12
                            bg-emerald-100 text-emerald-600
                            rounded-2xl flex items-center justify-center
                            mr-3 md:mr-4 shadow-inner">
                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base md:text-lg font-extrabold text-gray-800">
                        Status Pembayaran Ke Supplier
                    </h3>
                    <p class="text-[10px] md:text-xs text-gray-400 font-bold uppercase">
                        Monitoring Pembayaran Supplier
                    </p>
                </div>
            </div>
        </div>

        <!-- Content -->
        <div class="p-4 md:p-6">
            <div class="grid grid-cols-2 gap-4 md:gap-6">
                <!-- Belum Dibayar -->
                <div class="relative group p-4 md:p-6 bg-gradient-to-br from-orange-50 to-white rounded-[2rem] border border-orange-100 transition-all md:hover:shadow-xl md:hover:shadow-orange-100/50">
                    <div class="flex justify-center md:justify-between items-center mb-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <p class="text-xs md:text-sm font-bold text-gray-500 uppercase text-center md:text-left">
                        Belum Dibayar
                    </p>
                    <h3 class="text-2xl md:text-5xl font-black text-orange-600 mt-1 tracking-tighter text-center md:text-left">
                        {{ $pembayaranBelumDibayar }}
                    </h3>
                    <p class="mt-3 text-[10px] md:text-[11px] text-orange-700 font-medium">
                        Memerlukan verifikasi invoice segera
                    </p>
                </div>

                <!-- Sudah Dibayar -->
                <div class="relative group p-4 md:p-6 bg-gradient-to-br from-emerald-50 to-white rounded-[2rem] border border-emerald-100 transition-all md:hover:shadow-xl md:hover:shadow-emerald-100/50">
                    <div class="flex justify-center md:justify-between items-center mb-3">
                        <svg class="w-5 h-5 md:w-6 md:h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-xs md:text-sm font-bold text-gray-500 uppercase text-center md:text-left">
                        Sudah Dibayar (Lunas)
                    </p>
                    <h3 class="text-2xl md:text-5xl font-black text-emerald-600 mt-1 tracking-tighter text-center md:text-left">
                        {{ $pembayaranSudahDibayar }}
                    </h3>
                    <p class="mt-3 text-[10px] md:text-[11px] text-emerald-700 font-medium">
                        Transaksi periode berjalan aman
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>
@endsection