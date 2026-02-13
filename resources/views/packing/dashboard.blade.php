{{-- resources/views/packing/dashboard.blade.php --}}
@extends('layouts.allApp')

@section('title', 'Dashboard Packing')
@section('role', 'Packing')

@section('content')
<!-- Impor Font & Ikon -->
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(15px);
        -webkit-backdrop-filter: blur(15px);
        border: 1px solid rgba(255, 255, 255, 0.5);
        box-shadow: 0 15px 35px -10px rgba(0, 0, 0, 0.05);
    }
    .accent-gradient {
        background: linear-gradient(135deg, #0ea5e9 0%, #2563eb 100%);
    }
    .success-gradient {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    .warning-gradient {
        background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
    }
    .progress-track {
        background: rgba(226, 232, 240, 0.5);
        border-radius: 999px;
        overflow: hidden;
    }
    .progress-bar-shine {
        position: relative;
        overflow: hidden;
    }
    .progress-bar-shine::after {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        animation: shine 2s infinite;
    }
    .status-pulse {
        animation: pulse-animation 1s infinite;
    }
    @keyframes pulse-animation {
        0% { box-shadow: 0 0 0 0px rgba(99, 102, 241, 0.4); }
        100% { box-shadow: 0 0 0 10px rgba(99, 102, 241, 0); }
    }
</style>

<div class="container mx-auto px-6 py-3"> 
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Dashboard Packing</h1>
        <p class="text-gray-600 mt-2">Selamat datang, <span class="font-bold text-indigo-500">{{ Auth::user()->name }}</span>!</p>
        <p class="text-slate-500 font-medium">Monitoring real-time alur pengemasan barang.</p>
        <div class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full border border-indigo-100">
            <div class="h-2 w-2 bg-indigo-600 rounded-full status-pulse"></div>
            <span class="text-[10px] font-bold uppercase tracking-widest">Packing Aktif</span>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-3 md:grid-cols-3 gap-4 md:gap-6 mb-6 md:mb-8">
        {{-- Total Packing --}}
        <div class="relative overflow-hidden bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 group hover:shadow-xl transition-all">
            <div class="relative z-10 flex flex-col items-center text-center md:flex-row md:justify-between md:items-start md:text-left">
                <div>
                    <p class="text-[11px] font-black text-blue-500 uppercase tracking-widest mb-2">
                        Total Packing
                    </p>
                    <h3 class="text-5xl font-black text-gray-900 tracking-tighter">
                        {{ $packing }}
                    </h3>
                </div>
                <div class="hidden md:flex p-4 bg-blue-50 text-blue-600 rounded-2xl shadow-lg shadow-blue-100/50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                    </svg>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
        </div>


        {{-- Selesai --}}
        <div class="relative overflow-hidden bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 group hover:shadow-xl transition-all">
            <div class="relative z-10 flex flex-col items-center text-center md:flex-row md:justify-between md:items-start md:text-left">
                <div>
                    <p class="text-[11px] font-black text-emerald-500 uppercase tracking-widest mb-2">
                        Selesai
                    </p>
                    <h3 class="text-5xl font-black text-gray-900 tracking-tighter">
                        {{ $packingSelesai }}
                    </h3>
                </div>
                <div class="hidden md:flex p-4 bg-emerald-50 text-emerald-600 rounded-2xl shadow-lg shadow-blue-100/50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
        </div>

        {{-- Dalam Proses --}}
        <div class="relative overflow-hidden bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 group hover:shadow-xl transition-all">
            <div class="relative z-10 flex flex-col items-center text-center md:flex-row md:justify-between md:items-start md:text-left">
                <div>
                    <p class="text-[11px] font-black text-amber-500 uppercase tracking-widest mb-2">
                        Sedang Diproses
                    </p>
                    <h3 class="text-5xl font-black text-gray-900 tracking-tighter">
                        {{ $packingDalamProses }}
                    </h3>
                </div>
                <div class="hidden md:flex p-4 bg-amber-50 text-amber-600 rounded-2xl shadow-lg shadow-blue-100/50">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-amber-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
        </div>
    </div>

    <!-- Detail Progress Section -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 md:gap-6">
        <!-- Progress Visualization -->
        <div class="lg:col-span-8 glass-card rounded-[3rem] p-10">
            <h2 class="text-2xl font-black text-slate-800 mb-8 flex items-center gap-3">
                <i class="ph-fill ph-chart-line-up text-emerald-600"></i>
                Analisis Progress
            </h2>
            
            <div class="space-y-10">
                <!-- Selesai Progress -->
                <div>
                    <div class="flex justify-between items-end mb-3">
                        <div>
                            <p class="text-lg font-bold text-slate-800">Completion Rate</p>
                            <p class="text-sm text-slate-400 font-medium">Total barang yang sudah ter-packing rapi</p>
                        </div>
                        <span class="text-3xl font-black text-emerald-600">{{ $packing > 0 ? round(($packingSelesai / $packing) * 100) : 0 }}%</span>
                    </div>
                    <div class="progress-track h-8 p-1">
                        <div class="success-gradient h-full rounded-full progress-bar-shine flex items-center justify-end px-4 transition-all duration-1000" style="width: {{ $packing > 0 ? ($packingSelesai / $packing * 100) : 0 }}%">
                            <span class="text-[15px] font-black font-medium text-white">{{ $packingSelesai }}</span>
                        </div>
                    </div>
                </div>

                <!-- Dalam Proses Progress -->
                <div>
                    <div class="flex justify-between items-end mb-3">
                        <div>
                            <p class="text-lg font-bold text-slate-800">Queue Status</p>
                            <p class="text-sm text-slate-400 font-medium">Barang dalam antrean pengemasan</p>
                        </div>
                        <span class="text-3xl font-black text-amber-500">{{ $packing > 0 ? round(($packingDalamProses / $packing) * 100) : 0 }}%</span>
                    </div>
                    <div class="progress-track h-8 p-1">
                        <div class="warning-gradient h-full rounded-full progress-bar-shine flex items-center justify-end px-4 transition-all duration-1000" style="width: {{ $packing > 0 ? ($packingDalamProses / $packing * 100) : 0 }}%">
                            <span class="text-[15px] font-medium font-black text-white">{{ $packingDalamProses }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary Statistics Side -->
        <div class="lg:col-span-4 space-y-8">
            <div class="glass-card rounded-[2.5rem] p-8">
                <h4 class="text-slate-800 font-bold mb-4 flex items-center gap-2">
                    <i class="ph ph-info text-blue-600"></i> Tip Kerja
                </h4>
                <p class="text-xs text-slate-500 leading-relaxed italic">"Pastikan label pengiriman terbaca dengan jelas oleh scanner untuk menghindari delay logistik."</p>
            </div>
        </div>
    </div>
</div>
@endsection