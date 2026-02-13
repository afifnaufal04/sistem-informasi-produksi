@extends('layouts.allApp')

@section('title', 'Barang Masuk')
@section('role', 'Supplier')

@section('content')
    <div class="container mx-auto px-6 py-3">

        <h2 class="text-3xl font-bold mb-6">Barang yang Sedang Dikirim</h2>

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

        @if ($pengirimanList->isEmpty())
            <div class="text-center text-gray-500 py-10">
                <p class="text-lg">Tidak ada barang yang sedang dikirim untuk Anda.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ($pengirimanList as $pengirimanId => $details)
                    @php
                        $firstDetail = $details->first();
                        $pengiriman = $firstDetail->pengiriman;
                    @endphp

                    <div class="bg-white shadow-md rounded-xl p-6 border border-gray-100 hover:shadow-lg transition">

                        {{-- Header --}}
                        <div class="mb-4 pb-4 border-b">
                            <h3 class="text-lg font-semibold text-gray-800">
                                Pengiriman #{{ $pengiriman->pengiriman_id }}
                            </h3>
                            <p class="text-sm text-gray-600 mt-1">
                                Supir: <span class="font-medium">{{ $pengiriman->supir->name ?? '-' }}</span>
                            </p>
                            <p class="text-sm text-gray-600">
                                Kendaraan: <span class="font-medium">{{ $pengiriman->kendaraan->nama ?? '-' }}</span>
                            </p>
                            <p class="text-sm text-gray-600">
                                Tipe Pengiriman: <span class="font-medium">{{ $pengiriman->tipe_pengiriman ?? '-' }}</span>
                            </p>

                        </div>

                        {{-- Daftar Barang --}}
                        <div class="mb-4">
                            <p class="text-sm font-medium text-gray-700 mb-2">Barang untuk Anda:</p>
                            <div class="space-y-3">
                                @foreach ($details as $detail)
                                    <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                                        <div class="flex justify-between items-start mb-2">
                                            <div class="flex-1">
                                                <p class="font-semibold text-gray-800">
                                                    {{ $detail->produksi->barang->nama_barang }}
                                                </p>
                                                <p class="text-xs text-gray-600">
                                                    Proses: {{ $detail->subProses->nama_sub_proses ?? '-' }}
                                                </p>
                                                <p class="text-sm font-medium text-blue-600 mt-1">
                                                    Jumlah: {{ $detail->jumlah_pengiriman }} pcs
                                                </p>
                                            </div>

                                            {{-- Status Badge --}}
                                            <span class="px-2 py-1 text-xs font-medium rounded-full whitespace-nowrap
                                                            @if($detail->status_pengiriman === 'Diterima') bg-green-100 text-green-800
                                                            @elseif($detail->status_pengiriman === 'Sampai') bg-yellow-100 text-yellow-800
                                                            @else bg-blue-100 text-blue-800
                                                            @endif">
                                                @if($detail->status_pengiriman === 'Diterima') ✓ Diterima
                                                @elseif($detail->status_pengiriman === 'Sampai') 📦 Sampai
                                                @else 🚚 Dalam Perjalanan
                                                @endif
                                            </span>
                                        </div>

                                        {{-- Tombol Terima per Item --}}
                                        @if($detail->status_pengiriman === 'Sampai')
                                            <form action="{{ route('supplier.pengiriman.terima', $detail->detail_pengiriman_id) }}"
                                                method="POST" class="mt-2" data-swal-confirm="1" data-swal-title="Konfirmasi terima?"
                                                data-swal-text="Konfirmasi terima barang {{ $detail->produksi->barang->nama_barang }}?">
                                                @csrf
                                                <button type="submit"
                                                    class="w-full bg-green-500 hover:bg-green-600 text-white text-sm font-medium px-3 py-2 rounded-lg shadow transition">
                                                    ✓ Terima Barang
                                                </button>
                                            </form>
                                        @elseif($detail->status_pengiriman === 'Diterima')
                                            <div class="mt-2 text-center text-xs text-green-600 font-medium">
                                                Diterima: {{ $detail->waktu_diterima->format('d/m/Y H:i') }}
                                            </div>
                                        @else
                                            <div class="mt-2 text-center text-xs text-gray-500">
                                                Barang sedang dalam perjalanan...
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Summary Status --}}
                        <div class="mt-4 pt-4 border-t">
                            @php
                                $totalBarang = $details->count();
                                $totalDiterima = $details->where('status_pengiriman', 'Diterima')->count();
                                $totalSampai = $details->where('status_pengiriman', 'Sampai')->count();
                            @endphp

                            <div class="text-sm text-gray-600">
                                <p class="font-medium mb-1">Status Penerimaan:</p>
                                <div class="flex justify-between text-xs">
                                    <span>✓ Diterima: {{ $totalDiterima }}/{{ $totalBarang }}</span>
                                    <span>📦 Sampai: {{ $totalSampai }}/{{ $totalBarang }}</span>
                                </div>

                                @if($totalDiterima === $totalBarang)
                                    <div class="mt-2 bg-green-50 border border-green-200 text-green-700 px-3 py-2 rounded text-center">
                                        <p class="font-medium">✓ Semua barang sudah Diterima</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                @endforeach

            </div>
        @endif

    </div>
@endsection