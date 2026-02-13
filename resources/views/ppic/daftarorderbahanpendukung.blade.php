@extends('layouts.allApp')

@section('title', 'Daftar Order Bahan Pendukung')
@section('role', 'PPIC')

@section('content')
    <div class="max-w-6xl mx-auto pt-3 pb-8 px-4">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-black">Daftar Order Bahan Pendukung</h2>
        </div>

        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center space-x-3">
                <form method="GET" action="{{ route('ppic.daftarorderbahanpendukung') }}">
                    <select name="tahun" onchange="this.form.submit()"
                        class="border border-gray-300 rounded-lg px-3 py-2 text-sm">
                        <option value="semua" {{ $tahunDipilih == 'semua' ? 'selected' : '' }}>Semua Tahun</option>
                        @foreach($tahunList as $tahun)
                            <option value="{{ $tahun }}" {{ $tahun == $tahunDipilih ? 'selected' : '' }}>
                                {{ $tahun }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <a href="{{ route('ppic.daftarorderbahanpendukung.create') }}"
                    class="flex bg-green-600 items-center p-2 rounded-lg text-white hover:bg-white hover:text-gray-900">
                    <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 640 512">
                        <path
                            d="M0 8C0-5.3 10.7-16 24-16l45.3 0c27.1 0 50.3 19.4 55.1 46l.4 2 412.7 0c20 0 35.1 18.2 31.4 37.9L537.8 235.8c-5.7 30.3-32.1 52.2-62.9 52.2l-303.6 0 5.1 28.3c2.1 11.4 12 19.7 23.6 19.7L456 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-255.9 0c-34.8 0-64.6-24.9-70.8-59.1L77.2 38.6c-.7-3.8-4-6.6-7.9-6.6L24 32C10.7 32 0 21.3 0 8zM160 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm224 0a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zM336 78.4c-13.3 0-24 10.7-24 24l0 33.6-33.6 0c-13.3 0-24 10.7-24 24s10.7 24 24 24l33.6 0 0 33.6c0 13.3 10.7 24 24 24s24-10.7 24-24l0-33.6 33.6 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-33.6 0 0-33.6c0-13.3-10.7-24-24-24z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap font-semibold">Tambah Pembelian</span>
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-3 rounded-md bg-green-100 text-green-700 border border-green-300">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid gap-4 grid-cols-1 lg:grid-cols-2">
            @forelse($pembelians as $index => $item)
                <div class="bg-white rounded-xl shadow-md border border-gray-100 hover:shadow-lg transition p-5">

                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <p class="text-xl font-bold text-green-700">Pembelian #{{ $index + 1 }}</p>
                            <p class="text-xm text-gray-400">
                                {{ \Carbon\Carbon::parse($item->tanggal_pembelian)->format('d M Y') }}
                            </p>
                            @if(!empty($item->catatan))
                                <p class="text-sm text-gray-600 mt-2">
                                    <span class="font-semibold">Catatan:</span> {{ $item->catatan }}
                                </p>
                            @endif
                        </div>

                        {{-- Status --}}
                        <span
                            class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xm font-semibold {{ $item->status_order == 'Menunggu' ? 'bg-yellow-100 text-yellow-700' : 'bg-green-100 text-green-700' }}">
                            {{ $item->status_order }}
                        </span>
                    </div>

                    {{-- Daftar Bahan --}}
                    <div class="space-y-2 border-t border-gray-100 pt-3">
                        @foreach($item->detailpembelianbahanpendukung as $detail)
                            <div class="flex justify-between items-center text-sm">
                                <div>
                                    <p class="font-semibold text-xm text-gray-800">
                                        {{ $detail->bahanpendukung->nama_bahan_pendukung ?? '-' }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $detail->jumlah_pembelian }} ×
                                        Rp{{ number_format($detail->harga_bahan_pendukung, 0, ',', '.') }}
                                    </p>
                                </div>
                                <div class="font-semibold text-blue-600">
                                    Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div
                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between border-t border-gray-100 mt-4 pt-4">
                        <div class="text-lg font-bold text-gray-900">
                            Total Harga : Rp{{ number_format($item->total_harga, 0, ',', '.') }}
                        </div>
                        @if($item->status_order == 'Menunggu')
                            <a href="{{ route('ppic.daftarorderbahanpendukung.edit', $item->pembelian_bahan_pendukung_id) }}"
                                class="mt-3 sm:mt-0 inline-flex items-center justify-center bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition text-sm font-semibold">✏️
                                Edit</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-10">
                    Belum ada data pembelian bahan pendukung.
                </div>
            @endforelse
        </div>
    </div>
@endsection