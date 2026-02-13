@extends('layouts.allApp')

@section('title', 'Daftar Pengiriman')
@section('role', 'Kepala Produksi')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-gray-800">Daftar Pengiriman</h1>
            <p class="text-gray-500 text-sm sm:text-base mt-1">Kelola dan pantau seluruh proses pengiriman barang dengan mudah.</p>
        </div>

        <div class="w-full sm:w-auto mt-3 sm:mt-0">
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                <a href="{{ route('keprod.pengirimanInternal.index') }}"
                   aria-label="Pengiriman Internal"
                   class="flex items-center justify-center gap-2 px-4 py-2.5 bg-orange-500 hover:bg-orange-600 text-white font-semibold text-sm rounded-xl shadow-md hover:shadow-lg transition duration-200 w-full">
                    <span class="truncate">Internal</span>
                </a>

                <a href="{{ route('keprod.pengiriman.create', ['proses' => 1]) }}"
                   aria-label="Pengiriman WW"
                   class="flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold text-sm rounded-xl shadow-md hover:shadow-lg transition duration-200 w-full">
                    <span class="truncate">WW</span>
                </a>

                <a href="{{ route('keprod.pengiriman.create', ['proses' => 2]) }}"
                   aria-label="Pengiriman Finishing"
                   class="flex items-center justify-center gap-2 px-4 py-2.5 bg-green-600 hover:bg-green-700 text-white font-semibold text-sm rounded-xl shadow-md hover:shadow-lg transition duration-200 w-full">
                    <span class="truncate">Finishing</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div class="mb-5 p-4 rounded-xl bg-green-50 border border-green-200 text-green-800 flex flex-col sm:flex-row sm:items-center sm:justify-between shadow-sm">
            <div class="flex items-center mb-2 sm:mb-0">
                <svg class="h-5 w-5 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.707a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                </svg>
                <span class="text-sm sm:text-base">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-green-600 hover:text-green-800 font-semibold text-lg leading-none">&times;</button>
        </div>
    @endif

      {{-- Filter Section --}}
        <div class="bg-white shadow-sm rounded-xl border border-gray-200 p-4 mb-6">
            <form method="GET" action="{{ route('keprod.pengiriman.index') }}" class="flex flex-col lg:flex-row lg:items-end gap-4">
                <div class="grid grid-cols-2 sm:grid-cols-2 gap-3 flex-grow">
                    {{-- Filter Bulan --}}
                    <div class="relative">
                        <label for="bulan" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1 ml-1">Bulan</label>
                        <div class="relative">
                            <select name="bulan" id="bulan"
                                class="appearance-none w-full bg-gray-50 border border-gray-300 text-gray-700 py-2.5 pl-4 pr-10 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 sm:text-sm">
                                <option value="">Semua Bulan</option>
                                @php
                                    $months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                                @endphp
                                @foreach($months as $index => $name)
                                    <option value="{{ $index + 1 }}" {{ request('bulan') == ($index + 1) ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Filter Tahun --}}
                    <div class="relative">
                        <label for="tahun" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1 ml-1">Tahun</label>
                        <div class="relative">
                            <select name="tahun" id="tahun"
                                class="appearance-none w-full bg-gray-50 border border-gray-300 text-gray-700 py-2.5 pl-4 pr-10 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 sm:text-sm">
                                <option value="">Semua Tahun</option>
                                @foreach($tahunList as $thn)
                                    <option value="{{ $thn }}" {{ request('tahun') == $thn ? 'selected' : '' }}>{{ $thn }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-gray-400">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grup Tombol Aksi -->
                <div class="flex items-center gap-2 lg:min-w-[240px]">
                    <button type="submit" class="flex-1 inline-flex justify-center items-center bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition-colors duration-200 shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filter
                    </button>
                    <a href="{{ route('keprod.pengiriman.index') }}" class="flex-1 inline-flex justify-center items-center bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-semibold py-2.5 px-4 rounded-lg transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Reset
                    </a>
                </div>

                <!-- Status Badge (Desktop: Muncul di akhir, Mobile: Baris baru) -->
                <div class="flex items-center justify-center lg:justify-start">
                    @if(request('bulan') || request('tahun'))
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                            <span class="w-2 h-2 mr-2 bg-blue-500 rounded-full animate-pulse"></span>
                            Filter Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 border border-gray-200">
                            Semua Data
                        </span>
                    @endif
                </div>
            </form>
        </div>

    {{-- Card Grid / List View --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
        @forelse ($pengiriman as $item)
        <div class="bg-white rounded-xl md:rounded-2xl shadow-md hover:shadow-xl border border-gray-100 overflow-hidden transition-all duration-300 md:transform md:hover:-translate-y-1">
            
            {{-- MOBILE LIST VIEW --}}
            <div class="md:hidden">
                <div class="flex items-center gap-3 p-4 ">
                    <!-- Middle: Main Info -->
                    <div class="flex-1 min-w-0 ">
                        <div class="flex items-start justify-between mb-2 ">
                            <div class="flex-1 min-w-0 pr-2 ">
                                <div class="flex items-center gap-2 mb-1 flex-wrap">
                                                                   {{-- Status Badge Mobile --}}
                                    @switch($item->status)
                                        @case('Selesai')
                                            <span class="px-3 py-1 bg-green-100 text-green-700 text-xs font-bold rounded-full">✓ Selesai</span>
                                            @break
                                        @case('Dalam Pengiriman')
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-xs font-bold rounded-full">Pengiriman</span>
                                            @break
                                        @case('Sedang Dipersiapkan')
                                            <span class="px-3 py-1 bg-blue-100 text-blue-700 text-xs font-bold rounded-full">Persiapan</span>
                                            @break
                                        @default
                                            <span class="px-3 py-1 bg-red-100 text-red-700 text-xs font-bold rounded-full">{{ $item->status }}</span>
                                    @endswitch

                                    @php $value = null; @endphp

                                    @foreach($item->detailPengiriman as $detail)
                                        @if(!empty($detail->subProses->proses->nama_proses))
                                            @php $value = $detail->subProses->proses->nama_proses; @endphp
                                            @break
                                        @endif
                                    @endforeach

                                    @if($value && $item->tipe_pengiriman === 'eksternal')
                                    <span class="px-2.5 py-0.5 {{ $value == 'finishing' ? 'bg-green-200' : 'bg-blue-200' }} text-xs font-semibold rounded-full {{ $value == 'finishing' ? 'text-green-700' : 'text-blue-700' }}">
                                        {{ $value == 'finishing' ? 'Finishing' : 'WW' }}
                                    </span>
                                    @endif
                                </div>
                                <h3 class="font-bold text-gray-900 text-base capitalize">Pengiriman #{{ str_pad($loop->iteration, STR_PAD_LEFT) }} - <span class="{{$item->tipe_pengiriman === 'internal' ? 'text-orange-400' : 'text-indigo-400'}}">{{$item->tipe_pengiriman}}</span> </h3>
                                <p class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                        
                        <div class="space-y-1.5 text-xs text-gray-600">
                            <p class="flex items-center gap-1.5 font-bold">
                                <svg class="w-3.5 h-3.5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1m-4 0h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <span class="truncate"><span class="font-normal">Kendaraan:</span> {{ $item->kendaraan->nama ?? '-' }}</span>
                            </p>
                            <p class="flex items-center gap-1.5 font-bold">
                                <svg class="w-3.5 h-3.5 text-purple-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="truncate"><span class="font-normal">Supir:</span> {{ $item->supir->name ?? '-' }}</span>
                            </p>
                            <p class="flex items-center gap-1.5 font-bold">
                                <svg class="w-3.5 h-3.5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="truncate"><span class="font-normal">QC:</span> {{ $item->qc->name ?? '-' }}</span>
                            </p>
                            <p class="flex items-center gap-1.5 font-bold">
                                <svg class="w-3.5 h-3.5 text-orange-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span><span class="font-normal">Tgl Kirim:</span> {{ $item->tanggal_pengiriman ? \Carbon\Carbon::parse($item->tanggal_pengiriman)->format('d/m/Y') : '-' }}</span>
                            </p>
                            <p class="flex items-center gap-1.5 font-bold">
                                <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                                <span><span class="font-normal">Tgl Selesai:</span> {{ $item->tanggal_selesai ? \Carbon\Carbon::parse($item->tanggal_selesai)->format('d/m/Y') : '-' }}</span>
                            </p>
                        </div>
                    </div>

                    <!-- Right: Detail Button -->
                    <button type="button"
                            onclick="openDetailModal({{ $loop->index }})"
                            class="openDetailModal flex-shrink-0 p-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition shadow-md">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>

            {{-- DESKTOP CARD VIEW --}}
            <div class="hidden md:block">
                {{-- Card Header --}}
                <div class="px-5 py-4 {{ $item->tipe_pengiriman === 'internal' ? 'bg-orange-400' : 'bg-indigo-400' }} flex flex-col">
                    <div class="flex items-start justify-between ">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-md font-bold text-white capitalize">#{{ str_pad($loop->iteration, STR_PAD_LEFT) }} - {{$item->tipe_pengiriman}}</span>
                            </div>
                            <span class="px-3 py-1 text-[10px] text-white {{ $item->tipe_pengiriman === 'internal' ? 'bg-orange-500' : 'bg-indigo-500' }} rounded-full">{{ \Carbon\Carbon::parse($item->created_at)->format('d M Y, H:i') }}</span>
                        </div>
                        
                        {{-- Status & Tipe Badge --}}
                        <div class="text-right space-y-1 truncate">
                            <div>
                                @switch($item->status)
                                    @case('Selesai')
                                        <span class="px-3 py-1 bg-green-100 text-green-700 text-[10px] font-bold rounded-full">✓ Selesai</span>
                                        @break
                                    @case('Dalam Pengiriman')
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded-full">🚚 Pengiriman</span>
                                        @break
                                    @case('Sedang Dipersiapkan')
                                        <span class="px-3 py-1 bg-blue-100 text-blue-700 text-[10px] font-bold rounded-full">📦 Persiapan</span>
                                        @break
                                    @default
                                        <span class="px-3 py-1 bg-red-100 text-red-700 text-[10px] font-bold rounded-full">{{ $item->status }}</span>
                                @endswitch
                            </div>
                            <div>
                                @php $value = null; @endphp
                                @foreach($item->detailPengiriman as $detail)
                                    @if(!empty($detail->subProses->proses->nama_proses))
                                        @php $value = $detail->subProses->proses->nama_proses; @endphp
                                        @break
                                    @endif
                                @endforeach
                                @if($value && $item->tipe_pengiriman === 'eksternal')
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1m-4 0h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
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
                        <div class="flex items-center gap-1 mb-1">
                            <svg class="w-3.5 h-3.5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <p class="text-xs font-bold text-green-600">Quality Control</p>
                        </div>
                        <p class="text-sm font-semibold text-gray-800">{{ $item->qc->name ?? '-' }}</p>
                    </div>

                    {{-- Tanggal Kirim & Selesai --}}
                    <div class="flex items-center justify-between p-2 bg-orange-50 border border-orange-200 rounded-lg">
                        <div class="flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-xs font-bold text-orange-600">Tgl Kirim</p>
                        </div>
                        <p class="text-xs font-semibold text-gray-800">
                            {{ $item->tanggal_pengiriman ? \Carbon\Carbon::parse($item->tanggal_pengiriman)->format('d/m/Y') : '-' }}
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
                            onclick="openDetailModal({{ $loop->index }})"
                            class="openDetailModal w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-3 rounded-lg transition-all duration-200 flex items-center justify-between shadow-md hover:shadow-lg text-sm">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            Lihat Detail
                        </span>
                        <span class="bg-white/20 px-2 py-0.5 rounded-full text-xs font-bold">
                            {{ count($item->detailPengiriman) }}
                        </span>
                    </button>

                </div>
            </div>
        </div>


        {{-- Modal Detail Barang --}}
                <div id="modal-{{ $loop->index }}" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-[24px] shadow-2xl max-w-sm w-full max-h-[80vh] flex flex-col overflow-hidden">
                        {{-- Modal Header --}}
                        <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                            <div>
                                <h3 class="text-base font-bold text-gray-800">Detail Barang</h3>
                                <p class="text-[10px] text-gray-500">Pengiriman • {{ $item->tipe_pengiriman }}</p>
                            </div>
                            <button type="button" onclick="closeDetailModal({{ $loop->index }})"
                                class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:text-gray-600 transition-colors text-xl">&times;</button>
                        </div>

                        {{-- Modal Body --}}
                        <div class="p-4 overflow-y-auto custom-scrollbar flex-grow">
                            <div class="space-y-2">
                                @forelse($item->detailPengiriman as $detail)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl border border-gray-100">
                                        
                                            <div class="min-w-0 flex-1">
                                                <p class="font-bold text-gray-800 text-xs truncate">
                                                    {{ $detail->produksi->barang->nama_barang ?? '-' }}
                                                </p>
                                                <p class="text-[9px] text-blue-400 uppercase mt-0.5 font-medium tracking-tight truncate">
                                                    {{ $detail->subProses->proses->nama_proses ?? '-' }}
                                                </p>
                                                <p class="text-[9px] text-gray-400 uppercase mt-0.5 font-medium tracking-tight truncate">
                                                    {{ $detail->supplier->name ?? '-' }}
                                                </p>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                                    </svg>
                                                    <p class="text-[11px] uppercase text-gray-500"><span class="text-[10px] font-bold text-teal-600 uppercase">{{ $detail->subProses->nama_sub_proses ?? '-' }}</span></p>
                                                </div>
                                                
                                                
                                            </div>
                                        
                                            <div class="ml-3 text-right">
                                                <p class="text-base font-black text-indigo-600 leading-none">{{ $detail->jumlah_pengiriman }}</p>
                                                <p class="text-[10px] font-bold text-gray-400 uppercase mt-0.5">pcs</p>
                                                <p class="text-[10px] text-gray-500 mt-1">{{ $detail->butuh_bp ? '✓ Butuh Bahan Pendukung' : '✗ Tanpa Bahan Pendukung' }}</p>
                                            </div>
                                            
                                            
                                    </div>
                                    
                                @empty
                                    <p class="text-center text-gray-400 py-6 text-xs">Tidak ada detail barang</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="p-4 border-t border-gray-50 bg-gray-50/50">
                            <button type="button" onclick="closeDetailModal({{ $loop->index }})"
                                class="w-full py-3 bg-white border border-gray-200 text-gray-600 font-bold text-xs rounded-xl hover:bg-gray-100 transition shadow-sm">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>

        @empty
        <div class="col-span-full bg-white rounded-2xl shadow-md border border-gray-100 p-12 text-center">
            <div class="flex flex-col items-center">
                <div class="bg-gray-100 rounded-full p-4 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mb-2">Tidak Ada Data Pengiriman</h3>
                <p class="text-gray-500 text-sm mb-4">
                    @if(request()->hasAny(['search', 'status', 'tipe', 'bulan', 'tahun']))
                        Tidak ditemukan pengiriman yang sesuai dengan filter Anda.
                    @else
                        Belum ada pengiriman yang terdaftar.
                    @endif
                </p>
                @if(request()->hasAny(['search', 'status', 'tipe', 'bulan', 'tahun']))
                    <a href="{{ route('keprod.pengiriman.index') }}" class="text-blue-600 hover:text-blue-700 font-semibold">
                        Reset Filter
                    </a>
                @endif
            </div>
        </div>
        @endforelse
    </div>
</div>


@endsection

@push('scripts')
<script>
function openDetailModal(index) {
            const modal = document.getElementById('modal-' + index);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }
        function closeDetailModal(index) {
            const modal = document.getElementById('modal-' + index);
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // Close modal when clicking outside of it
        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('click', function (event) {
                if (event.target.id.startsWith('modal-')) {
                    const index = event.target.id.replace('modal-', '');
                    closeDetailModal(index);
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    document.querySelectorAll('[id^="modal-"]').forEach(modal => {
                        if (!modal.classList.contains('hidden')) {
                            const index = modal.id.replace('modal-', '');
                            closeDetailModal(index);
                        }
                    });
                }
            });
        });
</script>
@endpush