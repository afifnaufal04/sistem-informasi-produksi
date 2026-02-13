{{-- resources/views/marketing/dashboard.blade.php --}}
@extends('layouts.allApp')

@section('title', 'Dashboard Marketing')
@section('role', 'Marketing')

@section('content')
<script src="https://unpkg.com/@phosphor-icons/web"></script>
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.05);
    }
    .marketing-gradient {
        background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 100%);
    }
    .order-gradient {
        background: linear-gradient(135deg, #059669 0%, #10b981 100%);
    }
    .animate-float {
        animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-10px); }
    }
</style>
<div class="container mx-auto px-6 py-3 flex-grow">
    <!-- Header Section -->
    <div class="mb-7">
        <h1 class="text-3xl font-extrabold text-slate-900">Dashboard Marketing</h1>
        <p class="text-slate-500 font-medium">Selamat datang kembali, <span class="text-blue-600 font-bold">{{ Auth::user()->name }}</span>!</p>
        <div class="inline-flex items-center gap-2 px-3 py-1 bg-blue-50 text-blue-600 rounded-full border border-blue-100 mb-2">
            <i class="ph-bold ph-chart-line-up text-xs"></i>
            <span class="text-[10px] font-bold uppercase tracking-widest">Marketing Aktif</span>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6 mb-12">
    <!-- Total Barang -->
        <div class="glass-card p-6 md:p-8 rounded-[2.5rem] relative overflow-hidden group">
            <div class="absolute -right-10 -bottom-10 h-40 w-40 bg-blue-500/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
               
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <p class="text-slate-400 font-bold text-l uppercase tracking-widest">
                            Total Barang
                        </p>
                        <div class="h-1.5 w-1.5 rounded-full bg-blue-500 animate-pulse"></div>
                    </div>
                    <h3 class="text-5xl md:text-6xl font-black text-slate-900">{{ $barangs }}</h3>
                    <p class="text-blue-600 font-bold text-sm mt-2">
                        Produk Tersedia di Katalog
                    </p>
                </div>

                <div class="h-16 w-16 md:h-20 md:w-20 marketing-gradient rounded-3xl flex items-center justify-center text-white shadow-xl animate-float">
                    <i class="ph-bold ph-package text-3xl md:text-4xl"></i>
                </div>
            </div>
        </div>
        <!-- Total Pemesanan -->
        <div class="glass-card p-6 md:p-8 rounded-[2.5rem] relative overflow-hidden group">
            <div class="absolute -right-10 -bottom-10 h-40 w-40 bg-emerald-500/5 rounded-full group-hover:scale-150 transition-transform duration-700"></div>
            <div class="flex justify-between items-start relative z-10">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <p class="text-slate-400 font-bold text-l uppercase tracking-widest">
                            Total Pemesanan
                        </p>
                        <div class="h-1.5 w-1.5 rounded-full bg-emerald-500 animate-pulse"></div>
                    </div>
                    <h3 class="text-5xl md:text-6xl font-black text-slate-900">{{ $pemesanan }}</h3>
                    <p class="text-emerald-600 font-bold text-sm mt-2">
                        Order Berhasil Diterima
                    </p>
                </div>

                <div class="h-16 w-16 md:h-20 md:w-20 order-gradient rounded-3xl flex items-center justify-center text-white shadow-xl animate-float"
                    style="animation-delay: 0.5s">
                    <i class="ph-bold ph-shopping-cart-simple text-3xl md:text-4xl"></i>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection