@extends('layouts.allApp')

@section('title', 'Daftar Order Bahan Pendukung')
@section('role', 'Purchasing')

@section('content')
    <div class="max-w-7xl mx-auto pt-4 pb-10 px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 id="judulHalaman" class="text-3xl sm:text-4xl font-extrabold tracking-tight text-center text-gray-900">
                Daftar Order Bahan Pendukung
            </h1>
            <p class="mt-2 text-center text-sm text-gray-500">
                Kelola order bahan pendukung dan lihat riwayat pembelian.
            </p>
        </div>

        {{-- Filter Tahun dan Tab --}}
        <div class="flex items-center space-x-3 mb-4">
            <form method="GET" action="">
                <select name="tahun" onchange="this.form.submit()"
                    class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-green-600 focus:outline-none">
                    <option value="semua" {{ $tahunDipilih == 'semua' ? 'selected' : '' }}>Semua Tahun</option>
                    @foreach($tahunList as $tahun)
                        <option value="{{ $tahun }}" {{ $tahun == $tahunDipilih ? 'selected' : '' }}>
                            {{ $tahun }}
                        </option>
                    @endforeach
                </select>
            </form>
        </div>
        <div class="flex items-center space-x-3 mb-4">
            {{-- Tombol Tab --}}
            <div class="flex w-full justify-center mb-0">
                {{-- Tombol Tab (dibungkus agar tombol berada di tengah) --}}
                <div class="flex space-x-2 relative">

                {{-- ================== TOMBOL DAFTAR ORDER BP ================== --}}
                <button id="tabOrder" class="relative px-2 py-1.5 text-xs sm:px-3 sm:py-2 sm:text-sm md:px-4 md:py-2 md:text-base rounded-md font-semibold text-white bg-green-600 shadow-md focus:outline-none">
                    Daftar Order BP

                        {{-- Tambahan Notifikasi --}}
                        @php
                            $jumlahOrderMenunggu = $pembelians->where('status_order', 'Menunggu')->count();
                        @endphp
                        @if($jumlahOrderMenunggu > 0)
                            <span
                                class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center shadow-md animate-bounce">
                                {{ $jumlahOrderMenunggu }}
                            </span>
                        @endif
                    </button>

                    {{-- ================== TOMBOL RIWAYAT ================== --}}
                    <button id="tabRiwayat"
                        class="px-2 py-1.5 text-xs sm:px-3 sm:py-2 sm:text-sm md:px-4 md:py-2 md:text-base rounded-md font-semibold text-gray-800 bg-white shadow-md focus:outline-none">
                        Riwayat Pembelian BP
                    </button>
                </div>
            </div>
        </div>


        {{-- ================== DAFTAR ORDER ================== --}}
        <div id="daftarOrder" class="grid grid-cols-1 md:grid-cols-2 gap-5">
            @forelse($pembelians as $item)
                <div class="bg-white w-full rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100">
                    {{-- Header --}}
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">
                        <div class="min-w-0">
                            <h2 class="text-lg font-semibold text-gray-900 truncate">
                                Order #{{ $item->pembelian_bahan_pendukung_id }}
                            </h2>
                            <p class="text-sm text-gray-600">
                                Tanggal Pembelian:
                                <span
                                    class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($item->tanggal_pembelian)->format('d M Y') }}</span>
                            </p>
                            @if(!empty($item->catatan))
                                <p class="text-sm text-gray-600 mt-2">
                                    <span class="font-semibold text-gray-700">Catatan:</span>
                                    {{ \Illuminate\Support\Str::limit($item->catatan, 120) }}
                                </p>
                            @endif
                        </div>
                        <span class="
                            inline-flex items-center justify-center px-3 py-1 text-xs font-bold rounded-full whitespace-nowrap
                            @if($item->status_order == 'Menunggu') bg-yellow-100 text-yellow-700 
                            @elseif($item->status_order == 'Proses Pembelian') bg-blue-100 text-blue-700
                            @elseif($item->status_order == 'Barang Diterima') bg-green-100 text-green-700 
                            @else bg-gray-100 text-gray-600 
                            @endif">
                            {{ $item->status_order }}
                        </span>
                    </div>

                    {{-- Detail Bahan --}}
                    <div class="bg-gray-50 rounded-xl p-4 mb-4 border border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Rincian Bahan Pendukung</h3>
                        <div class="space-y-3">
                            @foreach($item->detailpembelianbahanpendukung as $detail)
                                <div class="flex items-start justify-between gap-4 text-sm">
                                    <div class="min-w-0">
                                        <div class="font-semibold text-gray-900 break-words">
                                            {{ $detail->bahanpendukung->nama_bahan_pendukung ?? '-' }}
                                        </div>
                                        <div class="text-gray-600">
                                            {{ $detail->jumlah_pembelian }} ×
                                            Rp{{ number_format($detail->harga_bahan_pendukung, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="font-semibold text-blue-700 whitespace-nowrap">
                                        Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Total dan Aksi --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold text-gray-500">Total Harga</p>
                            <p class="font-extrabold text-2xl text-red-600 leading-tight">
                                Rp{{ number_format($item->total_harga, 0, ',', '.') }}
                            </p>
                        </div>

                        @if($item->status_order == 'Menunggu')
                            <form
                                action="{{ route('purchasing.orderbahanpendukung.konfirmasi-pembelian', $item->pembelian_bahan_pendukung_id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="inline-flex items-center justify-center px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-600 w-full sm:w-auto">
                                    Konfirmasi
                                </button>
                            </form>
                        @elseif($item->status_order == 'Barang Diterima')
                            <div
                                class="inline-flex items-center text-green-700 font-semibold text-sm bg-green-50 border border-green-100 px-3 py-2 rounded-xl">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Barang Diterima
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div
                    class="md:col-span-2 text-center text-gray-500 py-10 bg-white rounded-2xl border border-dashed border-gray-200">
                    Belum ada data pembelian bahan pendukung.
                </div>
            @endforelse
        </div>

        {{-- ================== RIWAYAT PEMBELIAN ================== --}}
        <div id="riwayatOrder" class="hidden space-y-5">
            @php
                // Jika controller tidak mengirimkan $historyPembelian, buat fallback
                // ambil item dari $pembelians yang status bukan 'Menunggu'
                $riwayat = $historyPembelian ?? (
                    isset($pembelians)
                    ? $pembelians->filter(fn($it) => $it->status_order !== 'Menunggu')->values()
                    : collect()
                );
            @endphp
            @forelse($riwayat as $item)
                <div class="bg-white w-full rounded-2xl shadow-sm hover:shadow-md transition-all p-5 border border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-4">
                        <div class="min-w-0">
                            <h2 class="text-lg font-semibold text-gray-900 truncate">
                                Order #{{ $item->pembelian_bahan_pendukung_id }}
                            </h2>
                            <p class="text-sm text-gray-600">
                                Tanggal Pembelian:
                                <span
                                    class="font-medium text-gray-800">{{ \Carbon\Carbon::parse($item->tanggal_pembelian)->format('d M Y') }}</span>
                            </p>
                            @if(!empty($item->catatan))
                                <p class="text-sm text-gray-600 mt-2">
                                    <span class="font-semibold text-gray-700">Catatan:</span>
                                    {{ \Illuminate\Support\Str::limit($item->catatan, 120) }}
                                </p>
                            @endif
                        </div>
                        <span class="
                                                                        inline-flex items-center justify-center px-3 py-1 text-xs font-bold rounded-full whitespace-nowrap
                                                                        @if($item->status_order == 'Barang Diterima') bg-green-100 text-green-700
                                                                        @elseif($item->status_order == 'Proses Pembelian') bg-blue-100 text-blue-700
                                                                        @else bg-gray-100 text-gray-600 
                                                                        @endif">
                            {{ $item->status_order }}
                        </span>
                    </div>

                    <div class="bg-gray-50 rounded-xl p-4 mb-4 border border-gray-100">
                        <h3 class="text-sm font-semibold text-gray-700 mb-3">Rincian Bahan Pendukung</h3>
                        <div class="space-y-3">
                            @foreach($item->detailpembelianbahanpendukung as $detail)
                                <div class="flex items-start justify-between gap-4 text-sm">
                                    <div class="min-w-0">
                                        <div class="font-semibold text-gray-900 break-words">
                                            {{ $detail->bahanpendukung->nama_bahan_pendukung ?? '-' }}
                                        </div>
                                        <div class="text-gray-600">
                                            {{ $detail->jumlah_pembelian }} ×
                                            Rp{{ number_format($detail->harga_bahan_pendukung, 0, ',', '.') }}
                                        </div>
                                    </div>
                                    <div class="font-semibold text-blue-700 whitespace-nowrap">
                                        Rp{{ number_format($detail->subtotal, 0, ',', '.') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <p class="text-xs font-semibold text-gray-500">Total Harga</p>
                        <p class="font-extrabold text-xl text-gray-900">
                            Rp{{ number_format($item->total_harga, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 py-10 bg-white rounded-2xl border border-dashed border-gray-200">
                    Belum ada riwayat pembelian bahan pendukung.
                </div>
            @endforelse
        </div>

    </div>

    <script>
       const tabOrder = document.getElementById('tabOrder');
        const tabRiwayat = document.getElementById('tabRiwayat');
        const daftarOrder = document.getElementById('daftarOrder');
        const riwayatOrder = document.getElementById('riwayatOrder');
        const judulHalaman = document.getElementById('judulHalaman');
        tabOrder.addEventListener('click', () => {
            tabOrder.classList.add('bg-green-600', 'text-white');
            tabOrder.classList.remove('bg-white', 'text-gray-800', 'border');
            tabRiwayat.classList.remove('bg-green-600', 'text-white');
            tabRiwayat.classList.add('bg-white', 'text-gray-800', 'border');
            daftarOrder.classList.remove('hidden');
            riwayatOrder.classList.add('hidden');
            judulHalaman.textContent = "Daftar Order Bahan Pendukung";
        });

        tabRiwayat.addEventListener('click', () => {
            tabRiwayat.classList.add('bg-green-600', 'text-white');
            tabRiwayat.classList.remove('bg-white', 'text-gray-800', 'border');
            tabOrder.classList.remove('bg-green-600', 'text-white');
            tabOrder.classList.add('bg-white', 'text-gray-800', 'border');
            daftarOrder.classList.add('hidden');
            riwayatOrder.classList.remove('hidden');
            judulHalaman.textContent = "Riwayat Pembelian Bahan Pendukung";
        });

        tabOrder.addEventListener('click', () => setActiveTab('order'));
        tabRiwayat.addEventListener('click', () => setActiveTab('riwayat'));
    </script>
@endsection