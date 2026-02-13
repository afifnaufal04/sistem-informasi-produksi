@extends('layouts.allApp')
@section('title', 'Daftar Pemesanan')
@section('role', 'PPIC')

@section('content')
<div class="container mx-auto px-4">

    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg mb-4">
            {{ session('success') }}
        </div>
        <meta http-equiv="refresh" content="1;url={{ route('ppic.pemesanan.index') }}">
    @endif

    <h2 class="text-3xl font-extrabold mb-6 text-gray-800">Daftar Pemesanan</h2>

    {{-- Search dan Filter --}}
    <div class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
        <form action="{{ route('ppic.pemesanan.index') }}" method="GET" class="space-y-4">
            <div class="flex flex-col md:flex-row gap-4">
                {{-- Search by Nama Pembeli --}}
                <div class="flex-1">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Cari Pembeli</label>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Nama pembeli..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                </div>

                {{-- Filter by Status --}}
                <div class="w-full md:w-48">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Status</label>
                    <select name="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                        <option value="">Semua Status</option>
                        <option value="diproses" {{ $status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-2 items-end">
                    <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium whitespace-nowrap h-fit">
                        Filter
                    </button>
                    
                    @if($search || $status)
                        <a href="{{ route('ppic.pemesanan.index') }}" class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium whitespace-nowrap h-fit">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Info Hasil Filter --}}
    @if($search || $status)
        <div class="mb-4 flex items-center justify-between bg-blue-50 border border-blue-200 rounded-lg px-4 py-3">
            <div class="flex items-center gap-2 text-sm text-blue-700">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>
                    Menampilkan 
                    @if($search)
                        hasil pencarian "<strong>{{ $search }}</strong>"
                    @endif
                    @if($search && $status)
                        dengan
                    @endif
                    @if($status)
                        status <strong>{{ ucfirst($status) }}</strong>
                    @endif
                    ({{ $pesanans->count() }} pemesanan)
                </span>
            </div>
        </div>
    @endif

    {{-- ================= MODERN MOBILE (CARD VIEW) ================= --}}
    <div class="space-y-6 md:hidden px-2 py-4">
        @forelse($pesanans as $pemesanan)
            @php
                $semuaSelesai = $pemesanan->pemesananBarang->every(fn($i) => $i->status === 'selesai');
                $isDiproses = $pemesanan->status_pemesanan == 'diproses';
            @endphp

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md">
                {{-- Header Bagian Atas --}}
                <div class="p-5">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex flex-col">
                            <span class="text-xs font-bold uppercase tracking-wider text-blue-600 mb-1">Pembeli #{{ $loop->iteration }}</span>
                            <h3 class="font-bold text-gray-800 text-lg leading-tight">
                                {{ $pemesanan->pembeli->nama_pembeli }}
                            </h3>
                        </div>

                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium shadow-sm
                            {{ $isDiproses 
                                ? 'bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20' 
                                : 'bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20' }}">
                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $isDiproses ? 'bg-amber-500' : 'bg-emerald-500' }}"></span>
                            {{ ucfirst($pemesanan->status_pemesanan) }}
                        </span>
                    </div>

                    {{-- Informasi Detail dengan Ikon --}}
                    <div class="space-y-3 bg-gray-50 rounded-xl p-4 border border-gray-100">
                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <span class="font-medium mr-2">No. PO Pembeli:</span>
                            <span class="text-gray-900 ml-auto">{{ $pemesanan->no_PO }}</span>
                        </div>

                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                            <span class="font-medium mr-2">SPK KWaS:</span>
                            <span class="text-gray-900 ml-auto font-mono text-xs">{{ $pemesanan->no_SPK_kwas }}</span>
                        </div>

                        <div class="flex items-center text-sm text-gray-600">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <span class="font-medium mr-2">Tanggal:</span>
                            <span class="text-gray-900 ml-auto">{{ \Carbon\Carbon::parse($pemesanan->tanggal_pemesanan)->locale('id') ->format('d M Y') }}</span>
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex border-t border-gray-100 bg-gray-50/50 p-3 gap-3">
                    <a href="{{ route('ppic.pemesanan.show', $pemesanan->pemesanan_id) }}" class="flex-1 inline-flex justify-center items-center px-4 py-2.5 bg-blue-600 border border-gray-200 text-white font-bold rounded-xl text-sm transition-all hover:bg-blue-800 active:scale-95 shadow-sm">
                    Detail
                    </a>

                    @if($isDiproses)
                        @if($semuaSelesai)
                            <form action="{{ route('ppic.pemesanan.selesaikan', $pemesanan->pemesanan_id) }}" method="POST" class="flex-1" onsubmit="return confirm('Yakin menyelesaikan pemesanan ini?')">
                                @csrf
                                @method('PATCH')
                                <button class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-emerald-600 text-white font-semibold rounded-xl text-sm transition-all hover:bg-emerald-700 active:scale-95 shadow-sm shadow-emerald-200">
                                    Selesaikan
                                </button>
                            </form>
                        @else
                            <button disabled class="flex-1 px-4 py-2.5 bg-gray-200 text-gray-500 font-semibold rounded-xl text-sm cursor-not-allowed opacity-70">
                                Belum Siap
                            </button>
                        @endif
                    @else
                        <div class="flex-1 inline-flex justify-center items-center px-4 py-2.5 bg-gray-100 text-gray-400 font-semibold rounded-xl text-sm">
                            <svg class="w-4 h-4 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            Selesai
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center py-12">
                <div class="bg-gray-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <p class="text-gray-500 font-medium">Tidak ada pemesanan ditemukan</p>
            </div>
        @endforelse
    </div>

    {{-- ================= DESKTOP (TABLE VIEW) ================= --}}
    <div class="hidden md:block bg-white rounded-xl shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-green-600 text-white">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Pembeli</th>
                    <th class="px-4 py-3 text-left">PO Pembeli</th>
                    <th class="px-4 py-3 text-left">Tanggal</th>
                    <th class="px-4 py-3 text-left">No. SPK KWaS</th>
                    <th class="px-4 py-3 text-center">Status</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($pesanans as $pemesanan)
                    @php
                        $semuaSelesai = $pemesanan->pemesananBarang->every(fn($i) => $i->status === 'selesai');
                    @endphp

                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $loop->iteration }}</td>
                        <td class="px-4 font-bold py-3">{{ $pemesanan->pembeli->nama_pembeli }}</td>
                        <td class="px-4 py-3">{{ $pemesanan->no_PO }}</td>
                        <td class="px-4 py-3">{{ $pemesanan->tanggal_pemesanan }}</td>
                        <td class="px-4 py-3">{{ $pemesanan->no_SPK_kwas }}</td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-3 py-1 rounded-full text-xs
                                @if($pemesanan->status_pemesanan == 'diproses') bg-yellow-100 text-yellow-700
                                @else bg-green-100 text-green-700 @endif">
                                {{ ucfirst($pemesanan->status_pemesanan) }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('ppic.pemesanan.show', $pemesanan->pemesanan_id) }}"
                                   class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                    Detail
                                </a>

                                @if($pemesanan->status_pemesanan === 'diproses')
                                    @if($semuaSelesai)
                                        <form action="{{ route('ppic.pemesanan.selesaikan', $pemesanan->pemesanan_id) }}"
                                              method="POST"
                                              onsubmit="return confirm('Yakin menyelesaikan pemesanan ini?')">
                                            @csrf
                                            @method('PATCH')
                                            <button class="bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded text-xs">
                                                Selesaikan
                                            </button>
                                        </form>
                                    @else
                                        <button disabled
                                            class="bg-gray-300 text-gray-500 px-3 py-1 rounded text-xs cursor-not-allowed"
                                            title="Semua barang harus selesai">
                                            Selesaikan
                                        </button>
                                    @endif
                                @else
                                    <span class="bg-gray-200 text-gray-600 px-3 py-1 rounded text-xs">
                                        Sudah Selesai
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-6 text-gray-500">
                            Tidak ada pemesanan
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection