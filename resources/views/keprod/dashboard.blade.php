{{-- resources/views/keprod/dashboard.blade.php --}}
@extends('layouts.allApp')

@section('title', 'Dashboard Produksi')
@section('role', 'Kepala Produksi')

@section('content')
<div class="container mx-auto px-6 py-3">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight leading-none">Dashboard Produksi</h1>
            <p class="text-gray-500 mt-2 text-lg font-medium">Pantau performa operasional dan target hari ini.</p>
        </div>
    </div>

    {{-- Main Production Stats --}}
    <div class="mb-4 md:mb-6">
        <div class="flex items-center mb-6">
            <div class="w-1.5 h-8 bg-indigo-600 rounded-full mr-4"></div>
            <h2 class="text-xl font-extrabold text-gray-800 tracking-tight">Status Produksi Utama</h2>
        </div>
        
        <div class="grid grid-cols-3 md:grid-cols-4 gap-4 md:gap-8">

            <!-- Total Produksi Card -->
            <div class="group relative bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-gray-100 transition-all duration-500 md:hover:shadow-2xl md:hover:border-indigo-100 md:hover:-translate-y-2 overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row md:items-start md:justify-between gap-4">

                    <!-- Icon -->
                    <div class="p-3 md:p-4 bg-indigo-50 text-indigo-600 rounded-2xl transition-all duration-500 self-center md:self-auto text-center md:group-hover:bg-indigo-600 md:group-hover:text-white md:transform md:group-hover:rotate-12">
                        <svg class="w-6 h-6 md:w-8 md:h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>

                    <!-- Text -->
                    <div class="text-center md:text-left">
                        <p class="text-[9px] sm:text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-1">
                            Total Produksi
                        </p>
                        <h3 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black leading-none">
                            {{ $produksi }}
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Dalam Proses Card -->
            <div class="group relative bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-gray-100 transition-all duration-500 md:hover:shadow-2xl md:hover:border-amber-100 md:hover:-translate-y-2 overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <!-- Icon -->
                    <div class="p-3 md:p-4 bg-amber-50 text-amber-600 rounded-2xl transition-all duration-500 self-center md:self-auto text-center md:group-hover:bg-amber-500 md:group-hover:text-white md:transform md:group-hover:rotate-12">
                        <svg class="w-6 h-6 md:w-8 md:h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0"/>
                        </svg>
                    </div>
                    <!-- Text -->
                    <div class="text-center md:text-left">
                        <p class="text-[9px] sm:text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-1">
                            Dalam Proses
                        </p>
                        <h3 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black leading-none">
                            {{ $produksiDalamProses }}
                        </h3>
                    </div>
                </div>
            </div>

            <!-- Produksi Selesai Card -->
            <div class="group relative bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-gray-100 transition-all duration-500 md:hover:shadow-2xl md:hover:border-emerald-100 md:hover:-translate-y-2 overflow-hidden">
                <div class="relative z-10 flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                    <!-- Icon -->
                    <div class="p-3 md:p-4 bg-emerald-50 text-emerald-600 rounded-2xl transition-all duration-500 self-center md:self-auto text-center md:group-hover:bg-emerald-600 md:group-hover:text-white md:transform md:group-hover:rotate-12">
                        <svg class="w-6 h-6 md:w-8 md:h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0"/>
                        </svg>
                    </div>
                    <!-- Text -->
                    <div class="text-center md:text-left">
                        <p class="text-[9px] sm:text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-1">
                            Selesai Produksi
                        </p>
                        <h3 class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl font-black leading-none">
                            {{ $produksiSelesai }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Logistics Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 md:gap-6 lg:gap-8">
        {{-- Card Pengiriman --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden flex flex-col">
            <div class="px-8 pt-6 pb-4 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-2xl flex items-center justify-center mr-4 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 640 512" fill="currentColor">
                            <path d="M64 96c0-35.3 28.7-64 64-64l288 0c35.3 0 64 28.7 64 64l0 32 50.7 0c17 0 33.3 6.7 45.3 18.7L621.3 192c12 12 18.7 28.3 18.7 45.3L640 384c0 35.3-28.7 64-64 64l-3.3 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-102.6 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-3.3 0c-35.3 0-64-28.7-64-64l0-48-40 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 240c-13.3 0-24-10.7-24-24s10.7-24 24-24l176 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 144c-13.3 0-24-10.7-24-24S10.7 96 24 96l40 0zM576 288l0-50.7-45.3-45.3-50.7 0 0 96 96 0zM256 424a40 40 0 1 0 -80 0 40 40 0 1 0 80 0zm232 40a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-800">Alur Pengiriman</h3>
                        <p class="text-xs text-gray-400 font-bold uppercase">Distribusi Barang Jadi</p>
                    </div>
                </div>
                <span class="px-4 py-1 bg-white border border-purple-100 text-purple-600 text-[10px] font-black rounded-full shadow-sm">LIVE UPDATES</span>
            </div>
            <div class="px-8 pt-4 pb-8 space-y-4">
                <div class="flex justify-between items-center p-5 bg-gradient-to-r from-purple-50 to-white rounded-3xl border border-purple-50">
                    <span class="text-sm font-bold text-gray-600 uppercase tracking-wider">Total Pengiriman</span>
                    <span class="text-3xl font-black text-purple-600">{{ $pengiriman }}</span>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div class="p-5 bg-white rounded-3xl border border-gray-100 shadow-sm transition-hover hover:border-emerald-200">
                        <p class="text-[10px] text-emerald-600 font-black uppercase mb-1">Selesai</p>
                        <p class="text-3xl font-black text-gray-800">{{ $pengirimanSelesai }}</p>
                    </div>
                    <div class="p-5 bg-white rounded-3xl border border-gray-100 shadow-sm transition-hover hover:border-blue-200">
                        <p class="text-[10px] text-blue-600 font-black uppercase mb-1">Transit</p>
                        <p class="text-3xl font-black text-gray-800">{{ $pengirimanDalamProses }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card Pengambilan --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden flex flex-col">
            <div class="px-8 pt-6 pb-4 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-indigo-100 text-indigo-600 rounded-2xl flex items-center justify-center mr-4 shadow-inner">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 512 512" fill="currentColor">
                            <path d="M 198.42214532871972 137.30103806228374 Q 199.30795847750866 141.73010380622839 203.73702422145328 142.6159169550173 Q 205.50865051903114 142.6159169550173 206.39446366782008 141.73010380622839 L 248.02768166089965 118.69896193771626 L 248.02768166089965 118.69896193771626 Q 255.11418685121106 114.26989619377163 262.2006920415225 118.69896193771626 L 303.83391003460207 141.73010380622839 L 303.83391003460207 141.73010380622839 Q 304.719723183391 142.6159169550173 306.49134948096884 142.6159169550173 Q 310.9204152249135 141.73010380622839 311.8062283737024 137.30103806228374 L 311.8062283737024 29.231833910034602 L 311.8062283737024 29.231833910034602 L 382.6712802768166 29.231833910034602 L 382.6712802768166 29.231833910034602 Q 400.38754325259515 30.11764705882353 412.78892733564015 41.63321799307958 Q 424.3044982698962 54.034602076124564 425.1903114186851 71.75086505190312 L 425.1903114186851 213.4809688581315 L 425.1903114186851 213.4809688581315 Q 424.3044982698962 231.19723183391002 412.78892733564015 243.59861591695503 Q 400.38754325259515 255.11418685121106 382.6712802768166 256 L 127.55709342560553 256 L 127.55709342560553 256 Q 109.840830449827 255.11418685121106 97.43944636678201 243.59861591695503 Q 85.92387543252595 231.19723183391002 85.03806228373702 213.4809688581315 L 85.03806228373702 71.75086505190312 L 85.03806228373702 71.75086505190312 Q 85.92387543252595 54.034602076124564 97.43944636678201 41.63321799307958 Q 109.840830449827 30.11764705882353 127.55709342560553 29.231833910034602 L 198.42214532871972 29.231833910034602 L 198.42214532871972 29.231833910034602 L 198.42214532871972 137.30103806228374 L 198.42214532871972 137.30103806228374 Z M 503.1418685121107 326.8650519031142 Q 512 339.2664359861592 510.2283737024221 353.439446366782 L 510.2283737024221 353.439446366782 L 510.2283737024221 353.439446366782 Q 507.57093425605535 367.61245674740485 496.0553633217993 376.47058823529414 L 383.55709342560556 459.7370242214533 L 383.55709342560556 459.7370242214533 Q 351.66782006920414 482.7681660899654 311.8062283737024 482.7681660899654 L 170.07612456747404 482.7681660899654 L 28.346020761245676 482.7681660899654 Q 15.944636678200691 482.7681660899654 7.972318339100346 474.79584775086505 Q 0 466.8235294117647 0 454.42214532871975 L 0 397.7301038062284 L 0 397.7301038062284 Q 0 385.3287197231834 7.972318339100346 377.35640138408303 Q 15.944636678200691 369.3840830449827 28.346020761245676 369.3840830449827 L 61.121107266435985 369.3840830449827 L 61.121107266435985 369.3840830449827 L 100.98269896193771 337.4948096885813 L 100.98269896193771 337.4948096885813 Q 131.98615916955018 312.69204152249137 171.8477508650519 312.69204152249137 L 240.94117647058823 312.69204152249137 L 311.8062283737024 312.69204152249137 Q 324.2076124567474 312.69204152249137 332.1799307958477 320.6643598615917 Q 340.1522491349481 328.636678200692 340.1522491349481 341.038062283737 Q 340.1522491349481 353.439446366782 332.1799307958477 361.4117647058824 Q 324.2076124567474 369.3840830449827 311.8062283737024 369.3840830449827 L 255.11418685121106 369.3840830449827 L 240.94117647058823 369.3840830449827 Q 227.65397923875432 370.2698961937716 226.7681660899654 383.55709342560556 Q 227.65397923875432 396.84429065743944 240.94117647058823 397.7301038062284 L 348.12456747404843 397.7301038062284 L 348.12456747404843 397.7301038062284 L 453.5363321799308 319.7785467128028 L 453.5363321799308 319.7785467128028 Q 465.93771626297575 310.9204152249135 480.11072664359864 312.69204152249137 Q 494.28373702422147 315.34948096885813 503.1418685121107 326.8650519031142 L 503.1418685121107 326.8650519031142 Z M 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 L 170.96193771626298 369.3840830449827 L 170.96193771626298 369.3840830449827 Q 170.96193771626298 369.3840830449827 170.96193771626298 369.3840830449827 Q 170.96193771626298 369.3840830449827 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 Z" />
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-extrabold text-gray-800">Alur Pengambilan</h3>
                        <p class="text-xs text-gray-400 font-bold uppercase">Logistik Bahan Baku</p>
                    </div>
                </div>
                <span class="px-4 py-1 bg-white border border-indigo-100 text-indigo-600 text-[10px] font-black rounded-full shadow-sm">VERIFIED</span>
            </div>
            <div class="px-8 pt-4 pb-8 space-y-4">
                <div class="flex justify-between items-center p-5 bg-gradient-to-r from-indigo-50 to-white rounded-3xl border border-indigo-50">
                    <span class="text-sm font-bold text-gray-600 uppercase tracking-wider">Total Pengambilan</span>
                    <span class="text-3xl font-black text-indigo-600">{{ $pengambilan }}</span>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div class="p-5 bg-white rounded-3xl border border-gray-100 shadow-sm transition-hover hover:border-emerald-200">
                        <p class="text-[10px] text-emerald-600 font-black uppercase mb-1">Diterima</p>
                        <p class="text-3xl font-black text-gray-800">{{ $pengambilanSelesai }}</p>
                    </div>
                    <div class="p-5 bg-white rounded-3xl border border-gray-100 shadow-sm transition-hover hover:border-orange-200">
                        <p class="text-[10px] text-orange-600 font-black uppercase mb-1">Menunggu</p>
                        <p class="text-3xl font-black text-gray-800">{{ $pengambilanDalamProses }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection