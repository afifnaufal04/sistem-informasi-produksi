@extends('layouts.allApp')

@section('title', 'Daftar Order Bahan Pendukung')
@section('role', 'Gudang')

@section('content')
    <div class="max-w-6xl mx-auto pt-3 pb-8 px-4">

        <div class="mb-6 flex justify-between items-center">
            <h1 class="text-4xl font-bold text-gray-800">Daftar Order Bahan Pendukung</h1>
            <form method="GET" action="{{ route('gudang.daftarorderbahanpendukung') }}">
                <select name="tahun" onchange="this.form.submit()"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                    <option value="semua" {{ $tahunDipilih == 'semua' ? 'selected' : '' }}>Semua Tahun</option>
                    @foreach($tahunList as $tahun)
                        <option value="{{ $tahun }}" {{ $tahun == $tahunDipilih ? 'selected' : '' }}>{{ $tahun }}</option>
                    @endforeach
                </select>
            </form>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 rounded-md bg-green-100 text-green-700 border border-green-300">
                {{ session('success') }}
            </div>
        @endif

        @php
            $urutanStatus = [
                'Proses Pembelian' => 1,      // paling atas              
                'Barang Diterima' => 2       // paling bawah
            ];

            $pembeliansSorted = $pembelians->sortBy(function ($item) use ($urutanStatus) {
                return $urutanStatus[$item->status_order] ?? 99;
            });
        @endphp

        @forelse($pembeliansSorted as $index => $item)
            <div
                class="bg-white rounded-xl shadow-md p-5 mb-5 hover:shadow-lg transition-shadow duration-300 border border-gray-100">
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-3">
                    <div>
                        <h2 class="text-lg font-bold text-gray-800">Order #{{ $index + 1 }}</h2>
                        <p class="text-sm text-gray-500">Tanggal:
                            {{ \Carbon\Carbon::parse($item->tanggal_pembelian)->format('d M Y') }}</p>
                        @if(!empty($item->catatan))
                            <p class="text-sm text-gray-600 mt-1">
                                <span class="font-semibold">Catatan:</span> {{ $item->catatan }}
                            </p>
                        @endif
                    </div>
                    <div class="mt-2 md:mt-0">
                        @if($item->status_order == 'Proses Pembelian')
                            <span class="bg-yellow-100 text-yellow-800 text-sm px-3 py-1.5 rounded-full font-medium">Proses
                                Pembelian</span>
                        @elseif($item->status_order == 'Barang Diterima')
                            <span class="bg-green-100 text-green-800 text-sm px-3 py-1.5 rounded-full font-medium">Barang
                                Diterima</span>
                        @endif
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-3 mb-3">
                    @foreach($item->detailpembelianbahanpendukung as $detail)
                        <div class="flex justify-between items-center py-2 border-b border-gray-200 last:border-0">
                            <div>
                                <p class="font-semibold text-gray-800">{{ $detail->bahanpendukung->nama_bahan_pendukung ?? '-' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    {{ $detail->jumlah_pembelian }} ×
                                    Rp{{ number_format($detail->harga_bahan_pendukung, 0, ',', '.') }}
                                </p>
                            </div>
                            <p class="text-blue-700 font-semibold text-sm">Rp{{ number_format($detail->subtotal, 0, ',', '.') }}</p>
                        </div>
                    @endforeach
                </div>

                <div class="flex flex-col md:flex-row md:items-center justify-between">
                    <p class="text-gray-800 font-semibold text-base">
                        Total Harga: <span class="text-green-700">Rp{{ number_format($item->total_harga, 0, ',', '.') }}</span>
                    </p>

                    <div class="mt-3 md:mt-0">
                        @if($item->status_order == 'Proses Pembelian')
                            <form
                                action="{{ route('gudang.orderbahanpendukung.konfirmasi-sampai', $item->pembelian_bahan_pendukung_id) }}"
                                method="POST" class="inline">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium">
                                    Konfirmasi Sampai
                                </button>
                            </form>
                        @elseif($item->status_order == 'Barang Diterima')
                            <div class="flex items-center text-green-600 font-semibold text-sm">
                                <svg class="h-5 w-5 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Barang Diterima
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 py-6 bg-white rounded-xl shadow">
                Belum ada data pembelian bahan pendukung.
            </div>
        @endforelse
    </div>
@endsection