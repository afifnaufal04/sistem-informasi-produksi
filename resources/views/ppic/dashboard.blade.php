@extends('layouts.allApp')

@section('title', 'Dashboard')
@section('role', 'PPIC')

@section('content')
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            box-shadow: 0 20px 40px -15px rgba(0, 0, 0, 0.05);
        }

        .dark-card {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .accent-gradient {
            background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        }

        .status-pulse {
            animation: pulse-animation 1s infinite;
        }

        @keyframes pulse-animation {
            0% {
                box-shadow: 0 0 0 0px rgba(99, 102, 241, 0.4);
            }

            100% {
                box-shadow: 0 0 0 10px rgba(99, 102, 241, 0);
            }
        }

        .hover-glow:hover {
            border-color: rgba(99, 102, 241, 0.5);
            box-shadow: 0 0 20px rgba(99, 102, 241, 0.1);
        }
    </style>
    <div class="container mx-auto px-6 py-3">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-800">Dashboard PPIC</h1>
            <p class="text-gray-600 mt-2">Selamat datang, <span class="font-semibold">{{ Auth::user()->name }}</span>!</p>
            <div
                class="inline-flex items-center gap-2 px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full border border-indigo-100">
                <div class="h-2 w-2 bg-indigo-600 rounded-full status-pulse"></div>
                <span class="text-[10px] font-bold uppercase tracking-widest">Sistem Aktif</span>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-3 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5 mb-8">
            <!-- Card Total Produksi -->
            <div class="glass-card p-6 rounded-[2.5rem] hover-glow transition-all duration-500 group border-l-4 border-l-indigo-400">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-3 md:p-4 bg-indigo-50 text-indigo-600 rounded-2xl transition-all duration-500 self-center md:self-auto text-center md:group-hover:bg-indigo-500 md:group-hover:text-white md:transform">
                        <i class="ph-bold ph-factory text-2xl"></i>
                    </div>
                </div>
                <p class="text-slate-400 font-bold text-[10px] text-center uppercase tracking-widest mb-1 sm:text-xs sm:text-left">Total Produksi</p>
                <h3 class="text-2xl text-center font-black text-slate-900 sm:text-4xl sm:text-left">{{ $produksi }}</h3>
            </div>

            <!-- Card Dalam Proses -->
            <div class="glass-card p-6 rounded-[2.5rem] hover-glow transition-all duration-500 group border-l-4 border-l-amber-400">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-3 md:p-4 bg-amber-50 text-amber-600 rounded-2xl transition-all duration-500 self-center md:self-auto text-center md:group-hover:bg-amber-500 md:group-hover:text-white md:transform">
                        <i class="ph-bold ph-clock-countdown text-2xl"></i>
                    </div>
                </div>
                <p class="text-slate-400 font-bold text-[10px] text-center uppercase tracking-widest mb-1 sm:text-xs sm:text-left">Dalam Proses</p>
                <h3 class="text-2xl text-center font-black text-slate-900 sm:text-4xl sm:text-left">{{ $produksiDalamProses }}</h3>
            </div>

            <!-- Card Target Selesai -->
            <div class="glass-card p-6 rounded-[2.5rem] hover-glow transition-all duration-500 group border-l-4 border-l-emerald-400">
                <div class="flex justify-between items-start mb-6">
                    <div class="p-3 md:p-4 bg-emerald-50 text-emerald-600 rounded-2xl transition-all duration-500 self-center md:self-auto text-center md:group-hover:bg-emerald-500 md:group-hover:text-white md:transform">
                        <i class="ph-bold ph-check-circle text-2xl"></i>
                    </div>
                </div>
                <p class="text-slate-400 font-bold text-[10px] text-center uppercase tracking-widest mb-1 sm:text-xs sm:text-left">Target Selesai</p>
                <h3 class="text-2xl text-center font-black text-slate-900 sm:text-4xl sm:text-left">{{ $produksiSelesai }}</h3>
            </div>
        </div>

        <!-- Stok Barang & Stok Kritis (Gabungan) -->
        <div class="md:col-span-3 bg-white rounded-2xl shadow-sm p-5 border border-gray-100 mb-5">
            <div class="flex items-center mb-4">
                <div class="flex items-center justify-center w-12 h-12 bg-red-50 rounded-xl mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <div>
                    <p class="text-l font-bold text-gray-900 uppercase tracking-wide">
                        Stok Barang & Stok Kritis
                    </p>
                    <p class="text-sm text-gray-500">
                        Monitoring ketersediaan barang
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 gap-4">
                <!-- Total Stok -->
                <div class="bg-gray-50 rounded-xl p-5">
                    <p class="text-l font-bold text-gray-800 uppercase mb-1">Total Barang</p>
                    <h3 class="text-4xl font-bold text-gray-900">
                        {{ $barang }}
                    </h3>
                </div>

                <!-- Stok Kritis -->
                <div class="bg-red-50 rounded-xl p-5 border border-red-200">
                    <p class="text-xs font-bold text-red-600 uppercase mb-1">Stok Kritis</p>
                    <h3 class="text-4xl font-bold text-red-600">
                        {{ $barangHabis }}
                    </h3>
                    <p class="text-xs text-red-500 mt-1">
                        *Butuh perhatian segera
                    </p>
                </div>
            </div>
        </div>

        <!-- Feature Section -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">

            <!-- Inventory Management -->
            <div class="lg:col-span-8 dark-card rounded-[3rem] p-8 text-white relative overflow-hidden group">
                <div
                    class="absolute top-0 right-0 w-96 h-96 accent-gradient opacity-20 blur-[100px] -mr-48 -mt-48 transition-all duration-700 group-hover:opacity-40">
                </div>

                <div class="relative z-10">
                    <div class="flex justify-between items-center mb-12">
                        <div>
                            <h2 class="text-3xl font-bold tracking-tight">Bahan Pendukung</h2>
                            <p class="text-slate-400">Distribusi stok & status vendor eksternal</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-5">
                        <!-- Card 1 -->
                        <div
                            class="p-8 rounded-[2.5rem] bg-white/5 border border-white/10 hover:bg-white/10 transition-colors cursor-pointer text-center md:text-left">
                            <i class="ph ph-cube text-indigo-400 text-3xl mb-4 block mx-auto md:mx-0"></i>
                            <p class="text-4xl font-black mb-1">
                                {{ $stokBP }}
                            </p>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">
                                Total Stok BP
                            </p>
                        </div>

                        <!-- Card 2 -->
                        <div
                            class="p-8 rounded-[2.5rem] bg-white/5 border border-white/10 hover:bg-white/10 transition-colors cursor-pointer text-center md:text-left">
                            <i class="ph ph-shopping-cart text-purple-400 text-3xl mb-4 block mx-auto md:mx-0"></i>
                            <p class="text-4xl font-black mb-1">
                                {{ $orderBP }}
                            </p>
                            <p class="text-slate-400 text-xs font-bold uppercase tracking-widest">
                                Total Order
                            </p>
                        </div>

                        <!-- Card 3 -->
                        <div
                            class="p-8 rounded-[2.5rem] accent-gradient shadow-2xl shadow-indigo-500/20 text-center md:text-left">
                            <i class="ph ph-handshake text-white text-3xl mb-4 block mx-auto md:mx-0"></i>
                            <p class="text-4xl font-black mb-1">
                                {{ $orderBPSelesai }}
                            </p>
                            <p class="text-indigo-100 text-xs font-bold uppercase tracking-widest">
                                Order Selesai
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Packing & Logistics -->
            <div class="lg:col-span-4 flex flex-col gap-8">
                <div class="glass-card flex-grow rounded-[3rem] p-7 flex flex-col justify-between">
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800 mb-8 flex items-center gap-2">
                            <i class="ph-fill ph-package text-indigo-600"></i> Bagian Packing
                        </h3>

                        <div class="space-y-6">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="h-12 w-12 bg-yellow-50 text-yellow-600 rounded-2xl flex items-center justify-center font-black">
                                        {{ $packing}}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">Packing</p>
                                    </div>
                                </div>
                                <i class="ph ph-caret-right text-slate-300"></i>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="h-12 w-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center font-black">
                                        {{ $packingDalamProses }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">Proses Packing</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">Antrean Aktif</p>
                                    </div>
                                </div>
                                <i class="ph ph-caret-right text-slate-300"></i>
                            </div>

                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="h-12 w-12 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center font-black">
                                        {{ $packingSelesai }}
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-800">Selesai Packing</p>
                                        <p class="text-[10px] text-slate-400 font-bold uppercase">Siap Distribusi</p>
                                    </div>
                                </div>
                                <i class="ph ph-caret-right text-slate-300"></i>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection