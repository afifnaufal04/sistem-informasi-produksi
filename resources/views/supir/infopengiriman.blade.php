{{-- resources/views/supir/pengiriman/index.blade.php --}}
@extends('layouts.allApp')

@section('title', 'Daftar Pengiriman')
@section('role', 'Supir')

@section('content')
    <div class="container mx-auto px-6 py-3">

        <h2 class="text-[25px] font-extrabold mb-3">Daftar Pengiriman Anda</h2>

        {{-- Notifikasi --}}
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if ($pengirimanList->isEmpty())
            <div class="bg-white shadow-lg rounded-xl p-6 text-center border border-gray-200">
                <p class="text-gray-500 text-lg">Tidak ada Pengiriman yang tersedia saat ini.</p>
                <p class="text-sm text-gray-400 mt-2">Silakan periksa kembali nanti.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                @foreach ($pengirimanList as $item)
                    <div class="bg-white shadow-md rounded-xl p-6 border border-gray-100 hover:shadow-lg transition">

                        {{-- Judul: tampilkan beberapa barang --}}
                        <h3 class="text-lg font-semibold mb-3 text-gray-800">
                            @forelse ($item->detailPengiriman as $detail)
                                {{ $detail->produksi->barang->nama_barang }}@if (!$loop->last), @endif
                            @empty
                                -
                            @endforelse
                        </h3>

                        {{-- Informasi --}}
                        <table class="w-full text-sm text-gray-600 mb-4">
                            <tr>
                                <td class="py-1 w-32 font-medium text-gray-700">Kendaraan</td>
                                <td> : </td>
                                <td class="py-1">{{ $item->kendaraan->nama ?? '-' }}</td>
                            </tr>

                            <tr>
                                <td class="py-1 font-medium text-gray-700">QC</td>
                                <td> : </td>
                                <td class="py-1">{{ $item->qc->name ?? '-' }}</td>
                            </tr>

                            <tr>
                                <td class="py-1 font-medium text-gray-700">Tanggal Kirim</td>
                                <td> : </td>
                                <td class="py-1">{{ $item->tanggal_pengiriman }}</td>
                            </tr>
                            <tr>
                                <td class="py-1 font-medium text-gray-700">Tanggal Selesai</td>
                                <td> : </td>
                                <td class="py-1">{{ $item->tanggal_selesai }}</td>
                            </tr>
                        </table>

                        {{-- Detail Barang dan Supplier --}}
                        <div class="border-t pt-3 mb-3">
                            <p class="text-sm font-medium text-gray-700 mb-2">Detail Pengiriman:</p>
                            @foreach ($item->detailPengiriman as $detail)
                                <div class="bg-gray-50 rounded p-2 mb-2">
                                    <p class="text-sm font-semibold text-gray-800">
                                        {{ $detail->produksi->barang->nama_barang }}
                                        <span class="text-blue-600">({{ $detail->jumlah_pengiriman }} pcs)</span>
                                    </p>
                                    <p class="text-xs text-gray-600">
                                        Supplier: {{ $detail->supplier->name ?? '-' }}
                                    </p>
                                </div>
                            @endforeach
                        </div>

                        {{-- Status Badge --}}
                        <div class="mb-4">
                            <p class="w-full px-3 py-2 text-center text-sm font-medium rounded-lg
                                        @if($item->status == 'Menunggu Gudang') bg-red-500 text-white
                                        @elseif($item->status == 'Menunggu QC') bg-yellow-700 text-white
                                        @elseif($item->status == 'Sedang Dipersiapkan') bg-yellow-200 text-white
                                        @elseif($item->status == 'Dalam Pengiriman') bg-blue-500 text-white 
                                        @elseif($item->status == 'Selesai') bg-green-500 text-white
                                        @else bg-gray-100 text-gray-800
                                        @endif">
                                {{ $item->status }}
                            </p>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="space-y-2">
                            @if ($item->status === 'Sedang Dipersiapkan')
                                <form action="{{ route('supir.pengiriman.mulai', $item->pengiriman_id) }}" method="POST"
                                    data-swal-confirm="1" data-swal-title="Mulai antar?" data-swal-text="Mulai antar barang ini?">
                                    @csrf
                                    <button type="submit"
                                        class="w-full bg-green-500 hover:bg-green-600 text-white font-medium text-sm px-4 py-2 rounded-lg shadow transition">
                                        🚚 Antar Barang
                                    </button>
                                </form>

                            @elseif ($item->status === 'Dalam Pengiriman')
                                <a href="{{ route('supir.pengiriman.perjalanan', $item->pengiriman_id) }}"
                                    class="block w-full bg-blue-500 hover:bg-blue-600 text-white font-medium text-sm px-4 py-2 rounded-lg shadow text-center transition">
                                    📍 Lihat Perjalanan
                                </a>

                            @elseif ($item->status === 'Selesai')
                                <button class="w-full bg-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg cursor-not-allowed">
                                    ✓ Pengiriman Selesai
                                </button>

                            @else
                                <button class="w-full bg-gray-300 text-gray-600 text-sm px-4 py-2 rounded-lg cursor-not-allowed">
                                    Antar Barang
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection