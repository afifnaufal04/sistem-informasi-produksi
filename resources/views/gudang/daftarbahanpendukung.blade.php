@extends('layouts.allApp')

@section('title', 'Daftar Bahan Pendukung')
@section('role', 'Gudang')

@section('content')
<div class="w-full mx-auto mt-4 mb-5 px-6">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Daftar Bahan Pendukung</h2>

    <div class="mb-4 text-right">
        <a href="{{ route('gudang.bahanpendukung.create') }}"
           class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-800 transition">
            Tambah Bahan Pendukung
        </a>
    </div>

    <form method="GET" action="{{ route('gudang.daftarbahanpendukung.search') }}" class="mb-4">
        <input type="text" name="query" value="{{ request('query') }}" placeholder="Cari bahan pendukung..."
               class="w-full md:w-1/3 p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-300"
               onfocus="this.placeholder=''" onblur="this.placeholder='Cari bahan pendukung...'">
    </form>

    <div class="overflow-x-auto">
        <div class="overflow-x-auto">
            {{-- ================= MOBILE CARD MODE ================= --}}
            <div class="grid grid-cols-1 gap-5 md:hidden">
                @forelse($bahanPendukung as $index => $bahan)
                    <div class="bg-white rounded-2xl shadow-md p-5 border relative">

                        {{-- Judul & Stok --}}
                        <div class="flex justify-between items-start mb-3">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">
                                    {{ $bahan->nama_bahan_pendukung }}
                                </h3>
                                <span class="text-xs text-gray-500">#{{ $index + 1 }}</span>
                            </div>

                            @php
                                $stok = $bahan->stok_bahan_pendukung;

                                if ($stok <= 5) {
                                    $warna = 'bg-red-600';
                                } elseif ($stok <= 29) {
                                    $warna = 'bg-yellow-400';
                                } else {
                                    $warna = 'bg-green-600';
                                }
                            @endphp

                            <div class="{{ $warna }} text-white rounded-full w-14 h-14 flex flex-col items-center justify-center text-xs font-bold">
                                <span>{{ $stok }}</span>
                                <span class="text-[10px]">Stok</span>
                            </div>
                        </div>

                        <div class="mb-3 flex justify-between items-start gap-4">
                            <div class="flex-1">
                                <p class="text-xs font-semibold text-gray-500 uppercase">Catatan</p>
                                <p class="italic text-sm text-gray-700">
                                    {{ $bahan->catatan ?? '-' }}
                                </p>
                            </div>

                            <div class="w-1/3 text-right">
                                <p class="text-xs font-semibold text-gray-500 uppercase">Satuan</p>
                                <p class="italic font-bold text-sm text-gray-700">
                                    {{ $bahan->satuan ?? '-' }}
                                </p>
                            </div>
                        </div>

                        {{-- Info --}}
                        <div class="space-y-2 text-sm text-gray-700">
                            <div class="flex justify-between">
                                <span>Harga/Unit</span>
                                <span class="font-bold">Rp {{ number_format($bahan->harga_bahan_pendukung, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Update Terakhir</span>
                                <span>{{ \Carbon\Carbon::parse($bahan->updated_at)->format('Y-m-d') }}</span>
                            </div>
                        </div>

                        {{-- Aksi --}}
                        <div class="flex justify-end gap-4 mt-4 pt-3 border-t">

                            <a href="{{ route('gudang.daftarbahanpendukung.edit', $bahan->bahan_pendukung_id) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white text-sm font-semibold rounded-lg shadow hover:bg-blue-700 transition-all duration-200">
                                ✏️ Edit
                            </a>

                            <form action="{{ route('gudang.daftarbahanpendukung.destroy', $bahan->bahan_pendukung_id) }}" 
                                method="POST" 
                                data-swal-delete="{{ $bahan->nama_bahan_pendukung }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 text-white text-sm font-semibold rounded-lg shadow hover:bg-red-700 transition-all duration-200">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10 text-gray-500">
                        Belum ada data bahan pendukung.
                    </div>
                @endforelse
            </div>

            {{-- ================= DESKTOP TABLE MODE ================= --}}
            <div class="hidden md:block overflow-x-auto">
                <table class="w-full bg-white text-sm text-gray-700 border border-gray-200 rounded-lg overflow-hidden">
                    <thead class="bg-green-600 text-white text-center uppercase text-xs tracking-wider">
                        <tr>
                            <th class="py-3 px-4">No</th>
                            <th class="py-3 px-4">Nama Bahan Pendukung</th>
                            <th class="py-3 px-4">Stok</th>
                            <th class="py-3 px-4">Satuan</th>
                            <th class="py-3 px-4">Harga</th>
                            <th class="py-3 px-4">Update</th>
                            <th class="py-3 px-4">Catatan</th>
                            <th class="py-3 px-4">Aksi</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-gray-200 text-center">
                        @forelse($bahanPendukung as $index => $bahan)
                            <tr class="hover:bg-gray-100 transition">
                                <td class="py-3 px-4 font-medium">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 font-semibold">{{ $bahan->nama_bahan_pendukung }}</td>

                                <td class="py-3 px-4">
                                    @php
                                        $stok = $bahan->stok_bahan_pendukung;

                                        if ($stok <= 5) {
                                            $warna = 'bg-red-600 text-white';
                                        } elseif ($stok <= 29) {
                                            $warna = 'bg-yellow-400 text-white';
                                        } else {
                                            $warna = 'bg-teal-100 text-teal-700';
                                        }
                                    @endphp
                                    <span class="{{ $warna }} px-3 py-1 rounded-full text-xs font-bold">
                                        {{ $stok }}
                                    </span>
                                </td>

                                <td class="py-3 px-4 font-semibold">
                                    {{ $bahan->satuan }}
                                </td>

                                <td class="py-3 px-4 font-semibold">
                                    Rp {{ number_format($bahan->harga_bahan_pendukung, 0, ',', '.') }}
                                </td>

                                <td class="py-3 px-4">
                                    {{ \Carbon\Carbon::parse($bahan->updated_at)->format('Y-m-d') }}
                                </td>

                                <td class="py-3 px-4 italic">
                                    {{ Str::limit($bahan->catatan, 25) }}
                                </td>

                                <td class="py-3 px-4">
                                    <div class="flex flex-col gap-2 items-start">

                                        <a href="{{ route('gudang.daftarbahanpendukung.edit', $bahan->bahan_pendukung_id) }}"
                                        class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-blue-500 text-white text-sm font-semibold rounded-lg shadow hover:bg-blue-700 transition-all duration-200 w-full">
                                            ✏️ Edit
                                        </a>

                                        <form action="{{ route('gudang.daftarbahanpendukung.destroy', $bahan->bahan_pendukung_id) }}" 
                                            method="POST" 
                                            data-swal-delete="{{ $bahan->nama_bahan_pendukung }}"
                                            class="w-full">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" 
                                                    class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-all duration-150 w-full">
                                                🗑️ Hapus
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-6 text-center text-gray-500">
                                    Belum ada data bahan pendukung.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection