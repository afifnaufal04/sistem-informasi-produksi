@extends('layouts.allApp')

@section('title', 'Daftar Pengambilan')
@section('role', 'Kepala Produksi')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-3">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
    <!-- Judul -->
    <div>
        <h1 class="text-3xl sm:text-4xl font-bold text-gray-900">Daftar Pengambilan</h1>
        <p class="text-gray-500 text-sm sm:text-base mt-2">
            Kelola dan pantau seluruh proses pengambilan barang dari supplier dengan mudah.
        </p>
    </div>

    <!-- Tombol Wrapper -->
    <div class="flex gap-3 w-full sm:w-auto">
        <!-- Pengambilan Internal -->
        <a href="{{ route('keprod.pengambilanInternal.create') }}"
           class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-orange-600 hover:bg-orange-700 text-white font-medium text-sm sm:text-base px-4 sm:px-6 py-3 rounded-lg sm:rounded-xl shadow-md hover:shadow-lg transition duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Internal
        </a>

        <!-- Pengambilan External -->
        <a href="{{ route('keprod.pengambilan.create') }}"
           class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium text-sm sm:text-base px-4 sm:px-6 py-3 rounded-lg sm:rounded-xl shadow-md hover:shadow-lg transition duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            External
        </a>
    </div>
</div>

    {{-- Search & Filter Section --}}
    <div class="mb-6 bg-white rounded-2xl shadow-md border border-gray-100 p-4 sm:p-6">
        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Search Box -->
            <div class="flex-1">
                <div class="relative">
                    <input type="text" 
                           id="searchInput" 
                           placeholder="Cari berdasarkan kendaraan, supir, QC, atau barang..." 
                           class="w-full px-4 py-3 pl-11 pr-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                    <svg class="absolute left-3 top-3.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>

            <!-- Status Filter -->
            <div class="">
                <select id="statusFilter" 
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                    <option value="">Semua Status</option>
                    <option value="Selesai">Selesai</option>
                    <option value="Dalam Perjalanan">Dalam Perjalanan</option>
                    <option value="Siap Diambil">Siap Diambil</option>
                    <option value="Menunggu QC Lagi">Menunggu QC</option>
                    <option value="Menunggu Konfirmasi Supir">Menunggu Supir</option>
                </select>
            </div>

            <!-- Reset Button -->
            <button id="resetBtn" 
                    class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-700 font-medium rounded-xl transition duration-200 flex items-center justify-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reset
            </button>
        </div>
    </div>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
    <div class="mb-6 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 flex flex-col sm:flex-row sm:items-center sm:justify-between shadow-sm">
        <div class="flex items-center mb-2 sm:mb-0">
            <svg class="h-5 w-5 mr-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span class="text-sm sm:text-base">{{ session('success') }}</span>
        </div>
        <button type="button" onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 font-semibold text-lg leading-none">&times;</button>
    </div>
    @endif

    @if($pengambilan->count() > 0)
    <div id="pengambilanGrid" class="grid gap-4 md:gap-6 grid-cols-1 md:grid-cols-2 lg:grid-cols-4">
        @foreach ($pengambilan as $item)
            <div class="pengambilan-card bg-white rounded-2xl md:rounded-3xl shadow-md hover:shadow-lg transition duration-300 border border-gray-100 overflow-hidden"
                data-kendaraan="{{ strtolower($item->kendaraan->nama ?? '') }}"
                data-plat="{{ strtolower($item->kendaraan->plat_nomor ?? '') }}"
                data-supir="{{ strtolower($item->supir->name ?? '') }}"
                data-qc="{{ strtolower($item->qc->name ?? '') }}"
                data-status="{{ $item->status }}"
                data-barang="{{ strtolower(collect($item->detailPengambilan ?? [])->pluck('detailPengiriman.produksi.barang.nama_barang')->filter()->implode(' ')) }}"
                data-supplier="{{ strtolower(collect($item->detailPengambilan ?? [])->pluck('detailPengiriman.supplier.name')->filter()->implode(' ')) }}">
                    
                {{-- MOBILE LIST VIEW --}}
                <div class="md:hidden">
                    <div class="flex items-center gap-3 p-4">
                    <!-- Left: Icon & Number -->
                        <div class="flex-shrink-0">
                            <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" viewBox="0 0 512 512" fill="currentColor">
                                    <path d="M 198.42214532871972 137.30103806228374 Q 199.30795847750866 141.73010380622839 203.73702422145328 142.6159169550173 Q 205.50865051903114 142.6159169550173 206.39446366782008 141.73010380622839 L 248.02768166089965 118.69896193771626 L 248.02768166089965 118.69896193771626 Q 255.11418685121106 114.26989619377163 262.2006920415225 118.69896193771626 L 303.83391003460207 141.73010380622839 L 303.83391003460207 141.73010380622839 Q 304.719723183391 142.6159169550173 306.49134948096884 142.6159169550173 Q 310.9204152249135 141.73010380622839 311.8062283737024 137.30103806228374 L 311.8062283737024 29.231833910034602 L 311.8062283737024 29.231833910034602 L 382.6712802768166 29.231833910034602 L 382.6712802768166 29.231833910034602 Q 400.38754325259515 30.11764705882353 412.78892733564015 41.63321799307958 Q 424.3044982698962 54.034602076124564 425.1903114186851 71.75086505190312 L 425.1903114186851 213.4809688581315 L 425.1903114186851 213.4809688581315 Q 424.3044982698962 231.19723183391002 412.78892733564015 243.59861591695503 Q 400.38754325259515 255.11418685121106 382.6712802768166 256 L 127.55709342560553 256 L 127.55709342560553 256 Q 109.840830449827 255.11418685121106 97.43944636678201 243.59861591695503 Q 85.92387543252595 231.19723183391002 85.03806228373702 213.4809688581315 L 85.03806228373702 71.75086505190312 L 85.03806228373702 71.75086505190312 Q 85.92387543252595 54.034602076124564 97.43944636678201 41.63321799307958 Q 109.840830449827 30.11764705882353 127.55709342560553 29.231833910034602 L 198.42214532871972 29.231833910034602 L 198.42214532871972 29.231833910034602 L 198.42214532871972 137.30103806228374 L 198.42214532871972 137.30103806228374 Z M 503.1418685121107 326.8650519031142 Q 512 339.2664359861592 510.2283737024221 353.439446366782 L 510.2283737024221 353.439446366782 L 510.2283737024221 353.439446366782 Q 507.57093425605535 367.61245674740485 496.0553633217993 376.47058823529414 L 383.55709342560556 459.7370242214533 L 383.55709342560556 459.7370242214533 Q 351.66782006920414 482.7681660899654 311.8062283737024 482.7681660899654 L 170.07612456747404 482.7681660899654 L 28.346020761245676 482.7681660899654 Q 15.944636678200691 482.7681660899654 7.972318339100346 474.79584775086505 Q 0 466.8235294117647 0 454.42214532871975 L 0 397.7301038062284 L 0 397.7301038062284 Q 0 385.3287197231834 7.972318339100346 377.35640138408303 Q 15.944636678200691 369.3840830449827 28.346020761245676 369.3840830449827 L 61.121107266435985 369.3840830449827 L 61.121107266435985 369.3840830449827 L 100.98269896193771 337.4948096885813 L 100.98269896193771 337.4948096885813 Q 131.98615916955018 312.69204152249137 171.8477508650519 312.69204152249137 L 240.94117647058823 312.69204152249137 L 311.8062283737024 312.69204152249137 Q 324.2076124567474 312.69204152249137 332.1799307958477 320.6643598615917 Q 340.1522491349481 328.636678200692 340.1522491349481 341.038062283737 Q 340.1522491349481 353.439446366782 332.1799307958477 361.4117647058824 Q 324.2076124567474 369.3840830449827 311.8062283737024 369.3840830449827 L 255.11418685121106 369.3840830449827 L 240.94117647058823 369.3840830449827 Q 227.65397923875432 370.2698961937716 226.7681660899654 383.55709342560556 Q 227.65397923875432 396.84429065743944 240.94117647058823 397.7301038062284 L 348.12456747404843 397.7301038062284 L 348.12456747404843 397.7301038062284 L 453.5363321799308 319.7785467128028 L 453.5363321799308 319.7785467128028 Q 465.93771626297575 310.9204152249135 480.11072664359864 312.69204152249137 Q 494.28373702422147 315.34948096885813 503.1418685121107 326.8650519031142 L 503.1418685121107 326.8650519031142 Z M 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 L 170.96193771626298 369.3840830449827 L 170.96193771626298 369.3840830449827 Q 170.96193771626298 369.3840830449827 170.96193771626298 369.3840830449827 Q 170.96193771626298 369.3840830449827 171.8477508650519 369.3840830449827 L 171.8477508650519 369.3840830449827 Z" />
                                </svg>
                            </div>
                        </div>

                            <!-- Middle: Info -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="font-bold text-gray-900 text-base truncate">Pengambilan {{ str_pad($loop->iteration, STR_PAD_LEFT) }}</h3>
                                {{-- Status Badge --}}
                                @switch($item->status)
                                    @case('Selesai')
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 whitespace-nowrap">Selesai</span>
                                    @break
                                    @case('Dalam Perjalanan')
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 whitespace-nowrap">Perjalanan</span>
                                    @break
                                    @case('Siap Diambil')
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700 whitespace-nowrap">Siap</span>
                                    @break
                                    @case('Menunggu QC Lagi')
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700 whitespace-nowrap">QC</span>
                                    @break
                                    @case('Menunggu Konfirmasi Supir')
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 whitespace-nowrap">Supir</span>
                                    @break
                                    @default
                                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 whitespace-nowrap">{{ $item->status }}</span>
                                @endswitch
                            </div>
                                
                            <div class="space-y-1 text-sm text-gray-600">
                                <p class="flex items-center gap-1">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400" viewBox="0 0 640 512" fill="currentColor">
                                        <path d="M64 96c0-35.3 28.7-64 64-64l288 0c35.3 0 64 28.7 64 64l0 32 50.7 0c17 0 33.3 6.7 45.3 18.7L621.3 192c12 12 18.7 28.3 18.7 45.3L640 384c0 35.3-28.7 64-64 64l-3.3 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-102.6 0c-10.4 36.9-44.4 64-84.7 64s-74.2-27.1-84.7-64l-3.3 0c-35.3 0-64-28.7-64-64l0-48-40 0c-13.3 0-24-10.7-24-24s10.7-24 24-24l112 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 240c-13.3 0-24-10.7-24-24s10.7-24 24-24l176 0c13.3 0 24-10.7 24-24s-10.7-24-24-24L24 144c-13.3 0-24-10.7-24-24S10.7 96 24 96l40 0zM576 288l0-50.7-45.3-45.3-50.7 0 0 96 96 0zM256 424a40 40 0 1 0 -80 0 40 40 0 1 0 80 0zm232 40a40 40 0 1 0 0-80 40 40 0 1 0 0 80z" />
                                    </svg>
                                    <span class="truncate">{{ $item->supir->name ?? '-' }} | {{ $item->kendaraan->nama ?? '-' }}</span>
                                </p>
                                <p class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span class="truncate">QC : {{ $item->qc->name ?? '-' }}</span>
                                </p>
                                <p class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-orange-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-gray-500 text-sm">Pengambilan :</span>
                                    <span class="font-semibold text-sm text-gray-800">
                                        {{ $item->tanggal_pengambilan ? \Carbon\Carbon::parse($item->tanggal_pengambilan)->format('d/m/Y') : '-' }}
                                    </span>
                                </p>
                                <p class="flex items-center gap-1">
                                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-gray-500 text-sm">Selesai :</span>
                                    <span class="font-semibold text-sm text-gray-800">
                                        {{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') : '-' }}
                                    </span>
                                </p>
                            </div>
                        </div>

                            <!-- Right: Detail Button -->
                        <button type="button" class="toggleDetailMobile flex-shrink-0 p-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>
                    </div>
                </div>

            {{-- DESKTOP CARD VIEW --}}
                <div class="hidden md:block">
                {{-- Card Header --}}
                    <div class="px-5 py-4 {{ !$item->kendaraan ? 'bg-orange-400' : 'bg-indigo-400' }}">
                        <div class="flex items-start justify-between ">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-md font-bold text-white">Pengambilan #{{ str_pad($loop->iteration, STR_PAD_LEFT) }}</span>
                                </div>
                                <span class="px-3 py-1 text-xs text-white  {{ !$item->kendaraan ? 'bg-orange-500' : 'bg-indigo-500' }} rounded-full">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }}</span>
                            </div>
                        
                            {{-- Status & Tipe Badge --}}
                            <div class="text-right space-y-1">
                                <div>
                                    @switch($item->status)
                                            @case('Selesai')
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700 whitespace-nowrap">Selesai</span>
                                                @break
                                            @case('Dalam Perjalanan')
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 whitespace-nowrap">Perjalanan</span>
                                                @break
                                            @case('Siap Diambil')
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-purple-100 text-purple-700 whitespace-nowrap">Siap</span>
                                                @break
                                            @case('Menunggu QC Lagi')
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-orange-100 text-orange-700 whitespace-nowrap">QC</span>
                                                @break
                                            @case('Menunggu Konfirmasi Supir')
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700 whitespace-nowrap">Supir</span>
                                                @break
                                            @default
                                                <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700 whitespace-nowrap">{{ $item->status }}</span>
                                    @endswitch
                                </div>
                                <div>
                                    @php $value = null; @endphp

                                    @foreach($item->detailPengambilan as $detail)
                                        @if(!empty($detail->subProses->proses->nama_proses))
                                            @php $value = $detail->subProses->proses->nama_proses; @endphp
                                            @break
                                        @endif
                                    @endforeach

                                    @if($value )
                                    <span class="px-2.5 py-0.5 bg-white text-xs font-semibold rounded-full {{ $value == 'finishing' ? 'text-green-600' : 'text-blue-600' }}">
                                        {{ $value == 'finishing' ? 'Finishing' : 'WW' }}
                                    </span>
                                    @endif

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="p-4 space-y-3">
                        {{-- Kendaraan & Supir --}}
                        <div class="grid grid-cols-2 gap-2">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-2">
                                <div class="flex items-center gap-1 mb-1">
                                    <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                    </svg>
                                    <p class="text-xs font-bold text-blue-600">Kendaraan</p>
                                </div>
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $item->kendaraan->nama ?? '-' }}</p>
                            </div>

                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-2">
                                <div class="flex items-center gap-1 mb-1">
                                    <svg class="w-3.5 h-3.5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <p class="text-xs font-bold text-purple-600">Supir</p>
                                </div>
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $item->supir->name ?? '-' }}</p>
                            </div>
                        </div>

                        {{-- QC --}}
                        <div class="bg-green-50 border border-green-200 rounded-lg p-2">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-1">
                                    <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <p class="text-xs font-bold text-green-600">Quality Control</p>
                                </div>
                                <p class="text-sm font-semibold text-gray-800">{{ $item->qc->name ?? '-' }}</p>
                            </div>
                        </div>

                        {{-- Tanggal Kirim & Selesai --}}
                        <div class="flex items-center justify-between p-2 bg-orange-50 border border-orange-200 rounded-lg">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-xs font-bold text-orange-600">Tgl Pengambilan</p>
                            </div>
                            <p class="text-xs font-semibold text-gray-800">
                                {{ $item->tanggal_pengambilan ? \Carbon\Carbon::parse($item->tanggal_pengambilan)->format('d/m/Y') : '-' }}
                            </p>
                        </div>

                        <div class="flex items-center justify-between p-2 bg-gray-50 border border-gray-200 rounded-lg">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <p class="text-xs font-bold text-gray-600">Tgl Selesai</p>
                            </div>
                            <p class="text-xs font-semibold text-gray-800">
                                {{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') : '-' }}
                            </p>
                        </div>

                    {{-- Detail Button --}}
                    <button type="button"
                            data-pengiriman-id="{{ $item->id }}"
                            class="openDetailMobile w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-3 rounded-lg transition-all duration-200 flex items-center justify-between shadow-md hover:shadow-lg text-sm">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            Lihat Detail
                        </span>
                        <span class="bg-white/20 px-2 py-0.5 rounded-full text-xs font-bold">
                            {{ count($item->detailPengambilan) }}
                        </span>
                    </button>
                </div>
            </div>

                {{-- Detail Barang Data (Hidden, for modal) --}}
                <div class="detailData hidden" data-details='@json($item->detailPengambilan ?? [])'></div>
                </div>
                @endforeach
            </div>

            {{-- No Results Message --}}
            <div id="noResults" class="hidden bg-white rounded-2xl shadow-md border border-gray-100 p-12 text-center">
                <div class="flex flex-col items-center">
                    <div class="bg-gray-100 rounded-full p-4 mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Tidak Ada Hasil</h3>
                    <p class="text-gray-500 text-sm mb-6">Tidak ditemukan pengambilan yang sesuai dengan pencarian Anda.</p>
                </div>
            </div>
            @else
                {{-- Empty State --}}
                <div class="bg-white rounded-2xl shadow-md border border-gray-100 p-12 text-center">
                    <div class="flex flex-col items-center">
                        <div class="bg-gray-100 rounded-full p-4 mb-4">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                            </svg>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Belum Ada Data Pengambilan</h3>
                        <p class="text-gray-500 text-sm mb-6">Tidak ada pengambilan yang terdaftar. Mulai dengan menambahkan data pengambilan baru.</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Modal Detail Barang --}}
        <div id="detailModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center p-4">
            <div class="bg-white rounded-2xl max-w-2xl w-full max-h-[90vh] overflow-hidden flex flex-col">
                {{-- Modal Header --}}
                <div class="bg-green-600 px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-white">📦 Detail Barang</h3>
                    <button id="closeModal" class="text-white hover:text-gray-200 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div id="modalContent" class="flex-1 overflow-y-auto p-6">
                    <div class="divide-y divide-gray-200">
                        <!-- Content will be inserted here -->
                    </div>
                </div>
            </div>
        </div>
    @endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const resetBtn = document.getElementById('resetBtn');
    const cards = document.querySelectorAll('.pengambilan-card');
    const noResults = document.getElementById('noResults');
    const pengambilanGrid = document.getElementById('pengambilanGrid');
    const resultCount = document.getElementById('resultCount');
    const totalCount = document.getElementById('totalCount');
    const detailModal = document.getElementById('detailModal');
    const modalContent = document.getElementById('modalContent');
    const closeModal = document.getElementById('closeModal');

    // Set total count
    if (totalCount) {
        totalCount.textContent = cards.length;
    }

    // Filter function
    function filterCards() {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const selectedStatus = statusFilter.value;
        let visibleCount = 0;

        cards.forEach(card => {
            const kendaraan = card.dataset.kendaraan || '';
            const plat = card.dataset.plat || '';
            const supir = card.dataset.supir || '';
            const qc = card.dataset.qc || '';
            const status = card.dataset.status || '';
            const barang = card.dataset.barang || '';
            const supplier = card.dataset.supplier || '';

            // Check search match
            const searchMatch = searchTerm === '' || 
                kendaraan.includes(searchTerm) ||
                plat.includes(searchTerm) ||
                supir.includes(searchTerm) ||
                qc.includes(searchTerm) ||
                barang.includes(searchTerm) ||
                supplier.includes(searchTerm);

            // Check status match
            const statusMatch = selectedStatus === '' || status === selectedStatus;

            // Show/hide card
            if (searchMatch && statusMatch) {
                card.classList.remove('hidden');
                visibleCount++;
            } else {
                card.classList.add('hidden');
            }
        });

        // Update result count
        if (resultCount) {
            resultCount.textContent = visibleCount;
        }

        // Show/hide no results message
        if (noResults && pengambilanGrid) {
            if (visibleCount === 0) {
                pengambilanGrid.classList.add('hidden');
                noResults.classList.remove('hidden');
            } else {
                pengambilanGrid.classList.remove('hidden');
                noResults.classList.add('hidden');
            }
        }
    }

    // Event listeners
    if (searchInput) {
        searchInput.addEventListener('input', filterCards);
    }

    if (statusFilter) {
        statusFilter.addEventListener('change', filterCards);
    }

    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            searchInput.value = '';
            statusFilter.value = '';
            filterCards();
        });
    }

    // Modal functions
    function openModal(details) {
        if (!details || details.length === 0) {
            modalContent.innerHTML = '<div class="text-center text-gray-500 py-8">Tidak ada detail barang</div>';
        } else {
            let html = '';
            details.forEach(detail => {
                const detailPengiriman = detail.detail_pengiriman || {};
                const produksi = detailPengiriman.produksi || {};
                const barang = produksi.barang || {};
                const supplier = detailPengiriman.supplier || {};
                
                html += `
                    <div class="py-4 first:pt-0 last:pb-0">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 font-semibold mb-1">Barang</p>
                                <p class="text-gray-800 font-semibold">${barang.nama_barang || '-'}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-semibold mb-1">Supplier</p>
                                <p class="text-gray-800 font-semibold">${supplier.name || '-'}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-semibold mb-1">Jumlah Diambil</p>
                                <p class="text-blue-600 font-bold text-lg">${detail.jumlah_diambil || '-'} pcs</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-semibold mb-1">Harga/Pcs</p>
                                <p class="text-green-600 font-semibold">Rp ${formatNumber(detail.harga_produksi || 0)}</p>
                            </div>
                        </div>
                    </div>
                `;
            });
            modalContent.innerHTML = html;
        }
        
        detailModal.classList.remove('hidden');
        detailModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModalFunc() {
        detailModal.classList.add('hidden');
        detailModal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    function formatNumber(num) {
        return new Intl.NumberFormat('id-ID').format(num);
    }

    // Close modal events
    if (closeModal) {
        closeModal.addEventListener('click', closeModalFunc);
    }

    if (detailModal) {
        detailModal.addEventListener('click', function(e) {
            if (e.target === detailModal) {
                closeModalFunc();
            }
        });
    }

    // Mobile detail button (opens modal)
    document.querySelectorAll('.toggleDetailMobile').forEach(btn => {
        btn.addEventListener('click', function() {
            const card = this.closest('.pengambilan-card');
            const detailDataEl = card.querySelector('.detailData');
            if (detailDataEl) {
                const details = JSON.parse(detailDataEl.dataset.details || '[]');
                openModal(details);
            }
        });
    });

    // Desktop detail button (still opens modal for consistency)
    document.querySelectorAll('.toggleDetail').forEach(btn => {
        btn.addEventListener('click', function() {
            const card = this.closest('.pengambilan-card');
            const detailDataEl = card.querySelector('.detailData');
            if (detailDataEl) {
                const details = JSON.parse(detailDataEl.dataset.details || '[]');
                openModal(details);
            }
        });
    });

    // Initial filter to update counts
    filterCards();
});
</script>
@endpush