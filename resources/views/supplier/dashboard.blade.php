{{-- resources/views/supplier/dashboard.blade.php --}}
@extends('layouts.allApp')

@section('title', 'Dashboard Supplier')
@section('role', 'Supplier')

@section('content')
    <div class="container mx-auto px-6 py-6 max-w-7xl">
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between mb-10">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tight leading-none">Dashboard Supplier
                </h1>
                <p class="text-gray-500 mt-2 text-base md:text-lg font-medium">Selamat datang, <span
                        class="text-indigo-600 font-bold">{{ Auth::user()->name }}</span>.</p>
            </div>
        </div>

        {{-- Main Stats Grid --}}
        <div class="grid grid-cols-3 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-10">
            {{-- Barang Masuk --}}
            <div
                class="relative overflow-hidden bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 group hover:shadow-xl transition-all">
                <div
                    class="relative z-10 flex flex-col sm:flex-row sm:justify-between items-center sm:items-start gap-4 text-center sm:text-left">
                    <div
                        class="p-4 bg-indigo-50 text-indigo-600 rounded-2xl order-1 sm:order-2 mb-2 sm:mb-0 flex items-center justify-center">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4">
                            </path>
                        </svg>
                    </div>
                    <div class="order-2 sm:order-1 flex flex-col items-center sm:items-start">
                        <p class="text-[11px] font-black text-indigo-500 uppercase tracking-widest mb-2">
                            Barang Masuk
                        </p>
                        <h3 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tighter">
                            {{ $barangMasuk }}
                        </h3>
                    </div>
                </div>
            </div>


            {{-- Total Produksi --}}
            <div
                class="relative overflow-hidden bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 group hover:shadow-xl transition-all">
                <div
                    class="relative z-10 flex flex-col sm:flex-row sm:justify-between items-center sm:items-start gap-4 text-center sm:text-left">
                    <div
                        class="p-4 bg-indigo-50 text-indigo-600 rounded-2xl order-1 sm:order-2 mb-2 sm:mb-0 flex items-center justify-center">
                        <svg class="w-8 h-8 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 470.744 470.743">
                            <path
                                d="M65.317,188.515l-29.328,44.379l19.202,19.145l43.375-28.765c7.306,4.562,15.271,8.549,23.734,10.997l10.566,52.604 h27.072l10.308-51.331c8.616-1.989,16.792-5.279,24.337-9.438l44.379,29.356l19.145-19.191l-28.765-43.366 c4.562-7.306,7.718-15.271,10.165-23.734l51.771-10.567v-27.071l-50.5-10.309c-1.988-8.616-4.867-16.792-9.017-24.327 l29.568-44.38l-19.088-19.144l-43.317,28.764c-7.306-4.562-15.243-7.812-23.706-10.27L164.671,0H137.6l-10.309,50.595 c-8.616,1.989-16.792,4.915-24.336,9.075l-44.38-29.539L39.431,49.228l28.764,43.318c-4.562,7.315-8.645,15.243-11.093,23.706 L4.404,126.799v27.071l51.427,10.309C57.82,172.794,61.157,180.97,65.317,188.515z M148.769,101.889 c22.539,0,40.812,18.273,40.812,40.812s-18.274,40.813-40.812,40.813c-22.539,0-40.813-18.274-40.813-40.813 S126.23,101.889,148.769,101.889z" />
                            <path
                                d="M263.834,202.361l9.228,51.188c-7.268,5.029-13.722,10.939-19.201,17.585l-52.106-10.996l-10.729,24.853l42.726,29.682 c-1.549,8.482-1.979,17.203-1.128,25.972l-44.667,29.09l9.983,25.158l51.169-9.218c5.029,7.268,10.93,13.731,17.575,19.201 l-11.007,52.106l24.854,10.729l29.682-42.725c8.482,1.549,17.184,1.95,25.962,1.109l29.08,44.647l25.159-9.983l-9.209-51.15 c7.268-5.029,13.731-10.92,19.211-17.566l52.116,11.007l10.729-24.853l-42.725-29.673c1.549-8.481,1.979-17.193,1.138-25.972 l44.666-29.089l-9.983-25.159l-51.169,9.218c-5.029-7.267-10.93-13.731-17.575-19.201l11.006-52.106l-24.853-10.729 l-29.682,42.726c-8.482-1.55-17.203-1.999-25.981-1.157l-29.099-44.686L263.834,202.361z M312.086,293.674 c20.952-8.319,44.677,1.932,52.996,22.883c8.319,20.952-1.932,44.677-22.884,52.996c-20.951,8.319-44.676-1.932-52.995-22.884 C280.894,325.708,291.135,301.983,312.086,293.674z" />
                        </svg>
                    </div>
                    <div class="order-2 sm:order-1 flex flex-col items-center sm:items-start">
                        <p class="text-[11px] font-black text-blue-500 uppercase tracking-widest mb-2">
                            Total Produksi
                        </p>
                        <h3 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tighter">
                            {{ $produksi }}
                        </h3>
                    </div>
                </div>
            </div>

            {{-- Selesai --}}
            <div
                class="relative overflow-hidden bg-white p-6 rounded-[2rem] shadow-sm border border-gray-100 group hover:shadow-xl transition-all">
                <div
                    class="relative z-10 flex flex-col sm:flex-row sm:justify-between items-center sm:items-start gap-4 text-center sm:text-left">
                    <div class="p-4 bg-emerald-50 text-emerald-600 rounded-2xl order-1 sm:order-2 mb-2 sm:mb-0">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="order-2 sm:order-1 flex flex-col items-center sm:items-start">
                        <p class="text-[11px] font-black text-emerald-500 uppercase tracking-widest mb-2">
                            Selesai
                        </p>
                        <h3 class="text-4xl md:text-5xl font-black text-gray-900 tracking-tighter">
                            {{ $produksiSelesai }}
                        </h3>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ringkasan Performa - Deep Dark Mode Style --}}
        <div
            class="relative group p-8 md:p-10 bg-[#0A0C10] rounded-[3rem] shadow-2xl overflow-hidden border border-white/10 text-white">
            <div class="relative z-10">
                <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-8">
                    <div class="flex items-center mb-6 lg:mb-0">
                        <div class="w-12 h-1.5 bg-indigo-500 rounded-full mr-4 shadow-[0_0_20px_rgba(99,102,241,0.4)]">
                        </div>
                        <h3 class="text-xl md:text-2xl font-black tracking-tight">Status & Progress Produksi</h3>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Left Side: Status Cards --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-6 bg-white/[0.03] rounded-3xl border border-white/5">
                            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Sedang Diproses
                            </p>
                            <p class="text-4xl font-black text-white">{{ $produksiDalamProses }}</p>
                        </div>
                        <div class="p-6 bg-white/[0.03] rounded-3xl border border-white/5">
                            <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Progres Selesai
                            </p>
                            <p class="text-4xl font-black text-white">{{ $produksiSelesai }}</p>
                        </div>
                    </div>

                    {{-- Right Side: Visual Progress --}}
                    <div class="flex flex-col justify-center space-y-4">
                        <div>
                            <div class="flex justify-between mb-2 items-center">
                                <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Alokasi
                                    Produksi Selesai</span>
                                <span
                                    class="text-lg font-black text-emerald-400">{{ $produksi > 0 ? round(($produksiSelesai / $produksi) * 100) : 0 }}%</span>
                            </div>
                            <div class="w-full bg-white/5 h-3.5 rounded-full overflow-hidden p-0.5">
                                <div class="h-full rounded-full bg-emerald-400 shadow-[0_0_30px_rgba(16,185,129,0.85),0_0_60px_rgba(16,185,129,0.4)] transition-all duration-1000"
                                    style="width: {{ $produksi > 0 ? ($produksiSelesai / $produksi) * 100 : 0 }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between mb-2 items-center">
                                <span class="text-[11px] font-black text-gray-400 uppercase tracking-widest">Beban Produksi
                                    Aktif</span>
                                <span
                                    class="text-lg font-black text-amber-400">{{ $produksi > 0 ? round(($produksiDalamProses / $produksi) * 100) : 0 }}%</span>
                            </div>
                            <div class="w-full bg-white/5 h-3.5 rounded-full overflow-hidden p-0.5">
                                <div class="h-full rounded-full bg-orange-400 shadow-[0_0_30px_rgba(249,115,22,0.9),0_0_60px_rgba(249,115,22,0.45)] transition-all duration-1000"
                                    style="width: {{ $produksi > 0 ? ($produksiDalamProses / $produksi) * 100 : 0 }}%">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Decorative Elements --}}
            <div class="absolute -right-24 -bottom-24 w-96 h-96 bg-indigo-600/[0.05] rounded-full blur-[120px]"></div>
            <div class="absolute -left-24 -top-24 w-72 h-72 bg-white/[0.02] rounded-full blur-[100px]"></div>
        </div>
    </div>
@endsection