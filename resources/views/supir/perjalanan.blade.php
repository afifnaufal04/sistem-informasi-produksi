{{-- resources/views/supir/pengiriman/perjalanan.blade.php --}}
@extends('layouts.allApp')

@section('title', 'Perjalanan Pengiriman')
@section('role', 'Supir')

@section('content')
    <div class="container mx-auto px-6 py-3">
        <script src="https://unpkg.com/@phosphor-icons/web"></script>

        <div class="flex items-center justify-between mb-6">
            <div>
                <h2 class="text-xl md:text-3xl font-extrabold text-gray-900 flex items-center gap-2 md:gap-3">
                    <i class="ph-bold ph-truck text-lg md:text-2xl text-blue-600"></i>
                    Perjalanan Pengiriman
                </h2>
                <p class="text-xs md:text-sm text-gray-500 mt-1">
                    Ringkasan estimasi waktu, dan status penerimaan
                </p>
            </div>
        </div>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- Info Pengiriman --}}
        <div class="bg-white shadow-sm rounded-xl p-6 mb-6 border border-gray-100">
            <div class="flex items-center gap-4">
                <div
                    class="flex-shrink-0 h-16 w-16 rounded-xl bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center text-blue-700">
                    <i class="ph-thin ph-truck text-2xl"></i>
                </div>
                <div class="flex-1">
                    <h3 class="text-base md:text-lg font-extrabold text-gray-900">Informasi Pengiriman</h3>
                    <p class="text-xs md:text-sm text-gray-500 mt-1">Detail kendaraan, penanggung jawab QC, dan waktu mulai.
                    </p>
                    <div class="mt-3 md:mt-4 grid grid-cols-1 md:grid-cols-3 gap-2 text-xs md:text-sm">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase">Kendaraan</p>
                            <p class="font-medium text-gray-700">{{ $pengiriman->kendaraan->nama ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase">QC</p>
                            <p class="font-medium text-gray-700">{{ $pengiriman->qc->name ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase">Waktu Mulai</p>
                            <p class="font-medium text-gray-700">{{ $pengiriman->waktu_mulai->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Countdown Timer --}}
        <div id="countdown-container"
            class="bg-white shadow-md rounded-xl p-6 mb-6 transition-all duration-500 border border-gray-100">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <h3 id="countdown-title" class="text-base md:text-lg font-semibold mb-1 text-gray-800">Estimasi Waktu
                        Pengiriman</h3>
                    <p class="text-xs md:text-sm text-gray-500"> Total waktu:<span
                            class="font-semibold text-gray-700">{{ $pengiriman->total_waktu_antar }} menit</span></p>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex flex-col items-center bg-blue-50 text-blue-700 rounded-lg px-4 py-3">
                        <span class="text-xs uppercase font-black text-blue-600">Sisa Waktu</span>
                        <div id="countdown" class="text-xl md:text-3xl font-extrabold mt-1">--:--:--</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Daftar Supplier dengan Progress --}}
        <div class="space-y-4">
            @foreach ($supplierGroups as $index => $group)
                @php
                    $progress = $group['status'] === 'Diterima' ? 100 : ($group['status'] === 'Sampai' ? 70 : ($group['status'] === 'Dalam Perjalanan' ? 40 : 10));
                @endphp
                <div class="bg-white shadow-sm rounded-xl p-6 border border-gray-100">
                    <div class="flex items-start justify-between mb-4">
                        <div>
                            <h4 class="text-base md:text-lg font-extrabold text-gray-800 flex items-center gap-2 md:gap-3">
                                <span class="text-xs text-gray-400">{{ $index + 1 }}.</span>
                                {{ $group['supplier_name'] }}
                            </h4>
                            <p class="text-sm text-gray-500 mt-1">Waktu antar: <span
                                    class="font-semibold text-gray-700">{{ $group['waktu_antar'] }} menit</span></p>
                            <div class="mt-3 w-full max-w-md">
                                <div class="w-full bg-gray-100 h-2 rounded-full overflow-hidden">
                                    <div class="h-2 rounded-full"
                                        style="width: {{ $progress }}%; background: {{ $progress >= 100 ? 'linear-gradient(90deg,#16a34a,#059669)' : ($progress >= 70 ? 'linear-gradient(90deg,#f59e0b,#f97316)' : 'linear-gradient(90deg,#3b82f6,#60a5fa)') }};">
                                    </div>
                                </div>
                                <p class="text-[10px] md:text-xs text-gray-400 mt-1">Progress: {{ $progress }}%</p>
                            </div>
                        </div>

                        <div class="text-right">
                            <span class="inline-flex items-center px-2 md:px-3 py-0.5 md:py-1 rounded-full text-[10px] md:text-xs font-medium
                                                                    @if($group['status'] === 'Diterima') bg-green-100 text-green-800
                                                                    @elseif($group['status'] === 'Sampai') bg-yellow-100 text-yellow-800
                                                                    @elseif($group['status'] === 'Dalam Perjalanan') bg-blue-100 text-blue-800
                                                                    @else bg-gray-100 text-gray-800
                                                                    @endif">
                                @if($group['status'] === 'Diterima') Diterima
                                @elseif($group['status'] === 'Sampai') Menunggu Konfirmasi
                                @elseif($group['status'] === 'Dalam Perjalanan') Dalam Perjalanan
                                @else Belum Diantar
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <p class="text-xs md:text-sm font-medium text-gray-700 mb-2">Barang yang dikirim:</p>
                        <ul class="space-y-2">
                            @foreach ($group['items'] as $item)
                                <li class="flex justify-between items-center">
                                    <div class="text-xs md:text-sm text-gray-700">
                                        <span class="font-medium">• {{ $item->produksi->barang->nama_barang }}</span>
                                        <span
                                            class="text-[10px] md:text-xs text-gray-500 ml-2">({{ $item->subProses->nama_sub_proses ?? '-' }})</span>
                                    </div>
                                    <div
                                        class="text-[10px] md:text-xs font-semibold text-gray-700 bg-gray-50 border border-gray-100 px-2 md:px-3 py-0.5 md:py-1 rounded-full">
                                        {{ $item->jumlah_pengiriman }} pcs
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mt-4 flex justify-end">
                        @if($group['status'] === 'Dalam Perjalanan')
                            <form action="{{ route('supir.pengiriman.sampai', $pengiriman->pengiriman_id) }}" method="POST"
                                data-swal-confirm="1" data-swal-title="Konfirmasi sampai?"
                                data-swal-text="Konfirmasi sudah Sampai di supplier {{ $group['supplier_name'] }}?">
                                @csrf
                                <input type="hidden" name="supplier_id" value="{{ $group['supplier_id'] }}">
                                <button type="submit"
                                    class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white font-medium px-3 md:px-4 py-2 rounded-lg shadow transition">
                                    <span class="text-xs md:text-sm font-black">✓</span> <span class="text-xs md:text-sm">Konfirmasi
                                        Sampai</span>
                                </button>
                            </form>

                        @elseif($group['status'] === 'Sampai')
                            <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 px-4 py-2 rounded-lg text-center">
                                <p class="font-medium">⏳ Menunggu supplier menerima</p>
                                <p class="text-xs mt-1">Sampai: {{ $group['waktu_sampai']->format('H:i') }}</p>
                            </div>

                        @elseif($group['status'] === 'Diterima')
                            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-2 rounded-lg text-center">
                                <p class="font-medium">✓ Barang sudah Diterima</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Tombol Selesaikan Pengiriman --}}
        <div class="mt-6">
            @php
                $semuaDiterima = $supplierGroups->every(fn($group) => $group['status'] === 'Diterima');
            @endphp

            @if($semuaDiterima)
                <form action="{{ route('supir.pengiriman.selesai', $pengiriman->pengiriman_id) }}" method="POST"
                    data-swal-confirm="1" data-swal-title="Selesaikan pengiriman?" data-swal-text="Selesaikan pengiriman ini?">
                    @csrf
                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 md:px-6 py-2.5 md:py-3 rounded-lg shadow-lg transition text-sm md:text-base">
                        🏁 Selesaikan Pengiriman
                    </button>
                </form>
            @else
                <button class="w-full bg-gray-300 text-gray-600 font-bold px-6 py-3 rounded-lg cursor-not-allowed">
                    🏁 Selesaikan Pengiriman (Menunggu semua barang Diterima)
                </button>
                <p class="text-sm text-gray-400 mt-2">Keterangan: Tunggu hingga setiap supplier mengkonfirmasi penerimaan.</p>
            @endif
        </div>

    </div>

    {{-- Script Countdown --}}
    <script>
        // Waktu mulai dari server (format ISO 8601)
        const waktuMulai = new Date("{{ $pengiriman->waktu_mulai->toIso8601String() }}").getTime();
        const totalMenit = {{ $pengiriman->total_waktu_antar }};
        const waktuSelesai = waktuMulai + (totalMenit * 60 * 1000);

        let isTerlambat = false;

        function updateCountdown() {
            const sekarang = new Date().getTime();
            const sisa = waktuSelesai - sekarang;

            const countdownElement = document.getElementById('countdown');
            const containerElement = document.getElementById('countdown-container');
            const titleElement = document.getElementById('countdown-title');

            // Jika waktu habis (terlambat)
            if (sisa < 0) {
                // Ubah ke mode terlambat
                if (!isTerlambat) {
                    isTerlambat = true;
                    containerElement.className = 'bg-gradient-to-r from-red-500 to-red-600 text-white shadow-lg rounded-xl p-6 mb-6 transition-all duration-500';
                    titleElement.innerHTML = '⚠️ TERLAMBAT';
                }

                // Hitung waktu keterlambatan (absolute value)
                const terlambat = Math.abs(sisa);

                const jam = Math.floor(terlambat / (1000 * 60 * 60));
                const menit = Math.floor((terlambat % (1000 * 60 * 60)) / (1000 * 60));
                const detik = Math.floor((terlambat % (1000 * 60)) / 1000);

                countdownElement.innerHTML =
                    '+ ' + String(jam).padStart(2, '0') + ':' +
                    String(menit).padStart(2, '0') + ':' +
                    String(detik).padStart(2, '0');

                return;
            }

            // Mode normal (masih dalam waktu)
            const jam = Math.floor(sisa / (1000 * 60 * 60));
            const menit = Math.floor((sisa % (1000 * 60 * 60)) / (1000 * 60));
            const detik = Math.floor((sisa % (1000 * 60)) / 1000);

            countdownElement.innerHTML =
                String(jam).padStart(2, '0') + ':' +
                String(menit).padStart(2, '0') + ':' +
                String(detik).padStart(2, '0');
        }

        // Update setiap detik
        updateCountdown();
        setInterval(updateCountdown, 1000);
    </script>
@endsection