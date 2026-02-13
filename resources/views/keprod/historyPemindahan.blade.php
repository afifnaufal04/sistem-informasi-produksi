@extends('layouts.allApp')

@section('title', 'History Pemindahan')
@section('role', 'Kepala Produksi')

@section('content')

    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3">
        {{-- Header Section --}}
        <div class="flex flex-col lg:flex-row lg:items-start justify-between mb-6 gap-4">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">History Pemindahan</h1>
                <p class="text-sm text-gray-500 mt-1">Pantau riwayat pergerakan barang dari bagian produksi ke gudang.</p>
            </div>

            {{-- FILTER FORM --}}
            <form method="GET" class="w-full lg:w-auto bg-white p-4 rounded-2xl shadow-sm border border-gray-100">
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 items-end">
                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Bulan</label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <select name="bulan"
                                class="w-full pl-10 pr-3 py-2 rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Semua Bulan</option>
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase tracking-wider mb-1">Tahun</label>
                        <select name="tahun"
                            class="w-full px-3 py-2 rounded-xl border border-gray-200 bg-white text-sm font-semibold text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Semua Tahun</option>
                            @for ($y = date('Y'); $y >= date('Y') - 5; $y--)
                                <option value="{{ $y }}" {{ request('tahun') == $y ? 'selected' : '' }}>
                                    {{ $y }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white px-5 py-2 rounded-xl text-sm font-bold transition-all active:scale-95 shadow-sm">
                        Terapkan
                    </button>
                </div>
            </form>
        </div>

        {{-- STATS SUMMARY --}}
        <div class="grid grid-cols-2 md:grid-cols-6 gap-4 mb-6">
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Entri</p>
                <p class="text-2xl font-black text-gray-800">{{ $historyPemindahans->count() }}</p>
            </div>
            <div class="bg-white p-4 rounded-2xl border border-gray-100 shadow-sm">
                <p class="text-[10px] font-bold text-blue-500 uppercase tracking-widest mb-1">Total Unit</p>
                <p class="text-2xl font-black text-gray-800">{{ $historyPemindahans->sum('jumlah') }}</p>
            </div>
        </div>

        {{-- LIST VIEW (GANTI TABEL) --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse ($historyPemindahans as $item)
                <div
                    class="group bg-white rounded-2xl p-5 border border-gray-100 shadow-sm hover:shadow-md hover:border-blue-100 transition-all relative overflow-hidden">
                    {{-- Decorative Element --}}
                    <div
                        class="absolute top-0 right-0 w-16 h-16 -mr-8 -mt-8 bg-blue-50 rounded-full group-hover:bg-blue-100 transition-colors">
                    </div>

                    <div class="relative z-10">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4" />
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tight">Tanggal Pemindahan
                                    </p>
                                    <p class="text-sm font-bold text-gray-800">
                                        {{ \Carbon\Carbon::parse($item->tanggal_pemindahan)->locale('id')->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            </div>
                            <div class="bg-emerald-50 text-emerald-600 px-3 py-1 rounded-full text-xs font-black">
                                {{ $item->jumlah }} Unit
                            </div>
                        </div>

                        <div class="space-y-3 pt-3 border-t border-gray-50">
                            <div>
                                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tight mb-0.5">Nama Barang</p>
                                <p class="text-sm font-extrabold text-gray-900 group-hover:text-blue-600 transition-colors">
                                    {{ $item->barang->nama_barang ?? 'Barang Tidak Ditemukan' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full py-20 flex flex-col items-center justify-center bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center text-gray-300 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800">History Kosong</h3>
                    <p class="text-sm text-gray-500">Belum ada data pemindahan produksi pada periode ini.</p>
                </div>
            @endforelse
        </div>



    </div>

@endsection