@extends('layouts.allApp')

@section('title', 'Dashboard Quality Control')
@section('role', 'Quality Control')

@section('content')
<div class="container mx-auto px-6 py-3">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-10">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight leading-none">Dashboard Quality Control</h1>
            <p class="text-gray-500 mt-2 text-lg font-medium">Monitoring standar kualitas dan verifikasi produk.</p>
        </div>
    </div>

    {{-- Quick Stats Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6 mb-12">
        <!-- Pemesanan -->
        <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-gray-100 hover:shadow-lg transition-all">
            <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest mb-1">Pemesanan</p>
            <div class="flex items-end justify-between">
                <h4 class="text-3xl font-black text-gray-900">{{ $pemesanan }}</h4>
                <div class="text-blue-200">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path><path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h.01a1 1 0 100-2H10zm3 0a1 1 0 000 2h.01a1 1 0 100-2H13zM7 13a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h.01a1 1 0 100-2H10zm3 0a1 1 0 000 2h.01a1 1 0 100-2H13z" clip-rule="evenodd"></path>
                    </svg>
                </div>
            </div>
        </div>
        <!-- Produksi -->
        <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-gray-100 hover:shadow-lg transition-all">
            <p class="text-[10px] font-black text-purple-500 uppercase tracking-widest mb-1">Produksi</p>
            <div class="flex items-end justify-between">
                <h4 class="text-3xl font-black text-gray-900">{{ $produksi }}</h4>
                <div class="text-purple-200">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 512 512">
                        <path d="M195.1 9.5C198.1-5.3 211.2-16 226.4-16l59.8 0c15.2 0 28.3 10.7 31.3 25.5L332 79.5c14.1 6 27.3 13.7 39.3 22.8l67.8-22.5c14.4-4.8 30.2 1.2 37.8 14.4l29.9 51.8c7.6 13.2 4.9 29.8-6.5 39.9L447 233.3c.9 7.4 1.3 15 1.3 22.7s-.5 15.3-1.3 22.7l53.4 47.5c11.4 10.1 14 26.8 6.5 39.9l-29.9 51.8c-7.6 13.1-23.4 19.2-37.8 14.4l-67.8-22.5c-12.1 9.1-25.3 16.7-39.3 22.8l-14.4 69.9c-3.1 14.9-16.2 25.5-31.3 25.5l-59.8 0c-15.2 0-28.3-10.7-31.3-25.5l-14.4-69.9c-14.1-6-27.2-13.7-39.3-22.8L73.5 432.3c-14.4 4.8-30.2-1.2-37.8-14.4L5.8 366.1c-7.6-13.2-4.9-29.8 6.5-39.9l53.4-47.5c-.9-7.4-1.3-15-1.3-22.7s.5-15.3 1.3-22.7L12.3 185.8c-11.4-10.1-14-26.8-6.5-39.9L35.7 94.1c7.6-13.2 23.4-19.2 37.8-14.4l67.8 22.5c12.1-9.1 25.3-16.7 39.3-22.8L195.1 9.5zM256.3 336a80 80 0 1 0 -.6-160 80 80 0 1 0 .6 160z"/>
                    </svg>
                </div>
            </div>
        </div>
        <!-- Pengiriman -->
        <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-gray-100 hover:shadow-lg transition-all">
            <p class="text-[10px] font-black text-orange-500 uppercase tracking-widest mb-1">Pengiriman</p>
            <div class="flex items-end justify-between">
                <h4 class="text-3xl font-black text-gray-900">{{ $pengiriman }}</h4>
                <div class="text-orange-200">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 640 512">
                        <path d="M64 96c0-35.3 28.7-64 64-64l288 0c35.3 0 64 28.7 64 64l0 32 50.7 0c17 0 33.3 6.7 45.3 18.7L621.3 192c12 12 18.7 28.3 18.7 45.3L640 384c0 35.3-28.7 64-64 64l-3.3 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-102.6 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-3.3 0c-35.3 0-64-28.7-64-64l0-48-40 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 240c-13.3 0-24-10.7-24-24s10.7-24 24-24l176 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 144c-13.3 0-24-10.7-24-24S10.7 96 24 96l40 0zM576 288l0-50.7-45.3-45.3-50.7 0 0 96 96 0zM256 424a40 40 0 1 0 -80 0 40 40 0 1 0 80 0zm232 40a40 40 0 1 0 0-80 40 40 0 1 0 0 80z"/>
                    </svg>
                </div>
            </div>
        </div>
        <!-- Pengambilan -->
        <div class="bg-white p-6 rounded-[1.5rem] shadow-sm border border-gray-100 hover:shadow-lg transition-all">
            <p class="text-[10px] font-black text-indigo-500 uppercase tracking-widest mb-1">Pengambilan</p>
            <div class="flex items-end justify-between">
                <h4 class="text-3xl font-black text-gray-900">{{ $pengambilan }}</h4>
                <div class="text-indigo-200">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 512 512">
                        <path d="M 198.42214532871972 137.30103806228374 Q 199.30795847750866 141.73010380622839 203.73702422145328 142.6159169550173 Q 205.50865051903114 142.6159169550173 206.39446366782008 141.73010380622839 L 248.02768166089965 118.69896193771626 L 248.02768166089965 118.69896193771626 Q 255.11418685121106 114.26989619377163 262.2006920415225 118.69896193771626 L 303.83391003460207 141.73010380622839 L 303.83391003460207 141.73010380622839 Q 304.719723183391 142.6159169550173 306.49134948096884 142.6159169550173 Q 310.9204152249135 141.73010380622839 311.8062283737024 137.30103806228374 L 311.8062283737024 29.231833910034602 L 311.8062283737024 29.231833910034602 L 382.6712802768166 29.231833910034602 L 382.6712802768166 29.231833910034602 Q 400.38754325259515 30.11764705882353 412.78892733564015 41.63321799307958 Q 424.3044982698962 54.034602076124564 425.1903114186851 71.75086505190312 L 425.1903114186851 213.4809688581315 L 425.1903114186851 213.4809688581315 Q 424.3044982698962 231.19723183391002 412.78892733564015 243.59861591695503 Q 400.38754325259515 255.11418685121106 382.6712802768166 256 L 127.55709342560553 256 L 127.55709342560553 256 Q 109.840830449827 255.11418685121106 97.43944636678201 243.59861591695503 Q 85.92387543252595 231.19723183391002 85.03806228373702 213.4809688581315 L 85.03806228373702 71.75086505190312 L 85.03806228373702 71.75086505190312 Q 85.92387543252595 54.034602076124564 97.43944636678201 41.63321799307958 Q 109.840830449827 30.11764705882353 127.55709342560553 29.231833910034602 L 198.42214532871972 29.231833910034602 L 198.42214532871972 29.231833910034602 L 198.42214532871972 137.30103806228374 L 198.42214532871972 137.30103806228374 Z M 503.1418685121107 326.8650519031142 Q 512 339.2664359861592 510.2283737024221 353.439446366782 L 510.2283737024221 353.439446366782 L 510.2283737024221 353.439446366782 Q 507.57093425605535 367.61245674740485 496.0553633217993 376.47058823529414 L 383.55709342560556 459.7370242214533 L 383.55709342560556 459.7370242214533 Q 351.66782006920414 482.7681660899654 311.8062283737024 482.7681660899654 L 170.07612456747404 482.7681660899654 L 28.346020761245676 482.7681660899654 Q 15.944636678200691 482.7681660899654 7.972318339100346 474.79584775086505 Q 0 466.8235294117647 0 454.42214532871975 L 0 397.7301038062284 L 0 397.7301038062284 Q 0 385.3287197231834 7.972318339100346 377.35640138408303 Q 15.944636678200691 369.3840830449827 28.346020761245676 369.3840830449827 L 61.121107266435985 369.3840830449827 L 61.121107266435985 369.3840830449827 L 100.98269896193771 337.4948096885813 L 100.98269896193771 337.4948096885813 Q 131.98615916955018 312.69204152249137 171.8477508650519 312.69204152249137 L 240.94117647058823 312.69204152249137 L 311.8062283737024 312.69204152249137 Q 324.2076124567474 312.69204152249137 332.1799307958477 320.6643598615917 Q 340.1522491349481 328.636678200692 340.1522491349481 341.038062283737 Q 340.1522491349481 353.439446366782 332.1799307958477 361.4117647058824 Q 324.2076124567474 369.3840830449827 311.8062283737024 369.3840830449827 L 255.11418685121106 369.3840830449827 L 240.94117647058823 369.3840830449827 Q 227.65397923875432 370.2698961937716 226.7681660899654 383.55709342560556 Q 227.65397923875432 396.84429065743944 240.94117647058823 397.7301038062284 L 348.12456747404843 397.7301038062284 L 348.12456747404843 397.7301038062284 L 453.5363321799308 319.7785467128028 L 453.5363321799308 319.7785467128028 Q 465.93771626297575 310.9204152249135 480.11072664359864 312.69204152249137 Q 494.28373702422147 315.34948096885813 503.1418685121107 326.8650519031142 L 503.1418685121107 326.8650519031142 Z M 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 L 170.96193771626298 369.3840830449827 L 170.96193771626298 369.3840830449827 Q 170.96193771626298 369.3840830449827 170.96193771626298 369.3840830449827 Q 170.96193771626298 369.3840830449827 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 Z" />
                    </svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Urgent QC Tasks --}}
    <div class="mb-6 md:mb-8">
        <div class="flex items-center mb-4">
            <div class="w-1.5 h-8 bg-red-600 rounded-full mr-4"></div>
            <h2 class="text-xl font-extrabold text-gray-800 tracking-tight">Perlu Tindakan QC</h2>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">
            <!-- Pengiriman Butuh QC -->
            <div class="group relative bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-gray-100 transition-all duration-500 hover:shadow-2xl hover:border-red-100 hover:-translate-y-2 overflow-hidden">
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Pengiriman Menunggu QC</p>
                        <h3 class="text-5xl font-black text-red-600 tracking-tighter">{{ $pengirimanButuhQC }}</h3>
                        <p class="mt-2 text-sm text-gray-500 font-medium italic">"Menunggu Pengecekan dari QC"</p>
                    </div>
                    <div class="p-5 bg-red-50 text-red-600 rounded-3xl group-hover:bg-red-600 group-hover:text-white transition-all duration-500 transform group-hover:rotate-12">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-8 flex items-center">
                    <a href="{{ route('qc.pengiriman.index') }}" role="button" aria-label="Mulai Cek Sekarang" class="px-6 py-2 inline-block bg-red-600 text-white text-xs font-black rounded-xl shadow-lg shadow-red-100 hover:bg-red-700 transition-colors uppercase tracking-widest">Mulai Cek Sekarang</a>
                </div>
            </div>

            <!-- Produksi Perlu QC -->
            <div class="group relative bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-gray-100 transition-all duration-500 hover:shadow-2xl hover:border-amber-100 hover:-translate-y-2 overflow-hidden">
                <div class="relative z-10 flex justify-between items-start">
                    <div>
                        <p class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-1">Produksi Perlu QC</p>
                        <h3 class="text-5xl font-black text-amber-500 tracking-tighter">{{ $produksiPerluQC }}</h3>
                        <p class="mt-2 text-sm text-gray-500 font-medium italic text-balance">"Barang diterima dari supplier"</p>
                    </div>
                    <div class="p-5 bg-amber-50 text-amber-500 rounded-3xl group-hover:bg-amber-500 group-hover:text-white transition-all duration-500">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- Production Monitoring Section --}}
    <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-8 pt-6 pb-4 border-b border-gray-50 flex justify-between items-center bg-gray-50/30">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center mr-4 shadow-inner">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 512 512" fill="currentColor">
                        <path d="M195.1 9.5C198.1-5.3 211.2-16 226.4-16l59.8 0c15.2 0 28.3 10.7 31.3 25.5L332 79.5c14.1 6 27.3 13.7 39.3 22.8l67.8-22.5c14.4-4.8 30.2 1.2 37.8 14.4l29.9 51.8c7.6 13.2 4.9 29.8-6.5 39.9L447 233.3c.9 7.4 1.3 15 1.3 22.7s-.5 15.3-1.3 22.7l53.4 47.5c11.4 10.1 14 26.8 6.5 39.9l-29.9 51.8c-7.6 13.1-23.4 19.2-37.8 14.4l-67.8-22.5c-12.1 9.1-25.3 16.7-39.3 22.8l-14.4 69.9c-3.1 14.9-16.2 25.5-31.3 25.5l-59.8 0c-15.2 0-28.3-10.7-31.3-25.5l-14.4-69.9c-14.1-6-27.2-13.7-39.3-22.8L73.5 432.3c-14.4 4.8-30.2-1.2-37.8-14.4L5.8 366.1c-7.6-13.2-4.9-29.8 6.5-39.9l53.4-47.5c-.9-7.4-1.3-15-1.3-22.7s.5-15.3 1.3-22.7L12.3 185.8c-11.4-10.1-14-26.8-6.5-39.9L35.7 94.1c7.6-13.2 23.4-19.2 37.8-14.4l67.8 22.5c12.1-9.1 25.3-16.7 39.3-22.8L195.1 9.5zM256.3 336a80 80 0 1 0 -.6-160 80 80 0 1 0 .6 160z"/>
                     </svg>
                </div>
                <div>
                    <h3 class="text-lg font-extrabold text-gray-800">Status Progres Produksi</h3>
                    <p class="text-xs text-gray-400 font-bold uppercase">Pantau Kualitas di Setiap Tahapan</p>
                </div>
            </div>
        </div>
        <div class="px-8 pt-4 pb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-6">
                <div class="flex items-center justify-between p-4 md:p-6 bg-gradient-to-br from-emerald-50 to-white rounded-[1.5rem] border border-emerald-50">
                    <div>
                        <span class="text-[10px] font-black text-emerald-600 uppercase">Produksi Selesai</span>
                        <h3 class="text-4xl font-black text-gray-900 leading-none mt-1">{{ $produksiSelesai }}</h3>
                    </div>
                    <div class="w-16 h-16 rounded-full border-4 border-emerald-200 flex items-center justify-center">
                        <span class="text-xs font-black text-emerald-600">PASS</span>
                    </div>
                </div>
                <div class="flex items-center justify-between p-4 md:p-6 bg-gradient-to-br from-amber-50 to-white rounded-[1.5rem] border border-amber-50">
                    <div>
                        <span class="text-[10px] font-black text-amber-600 uppercase">Dalam Proses</span>
                        <h3 class="text-4xl font-black text-gray-900 leading-none mt-1">{{ $produksiDalamProses }}</h3>
                    </div>
                    <div class="flex flex-col items-end">
                        <span class="text-[10px] font-black text-amber-500 uppercase">Pre-Check</span>
                        <div class="flex space-x-1 mt-1">
                            <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                            <div class="w-2 h-2 rounded-full bg-amber-400"></div>
                            <div class="w-2 h-2 rounded-full bg-gray-200"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection