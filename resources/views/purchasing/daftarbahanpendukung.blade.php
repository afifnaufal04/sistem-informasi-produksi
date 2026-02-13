@extends('layouts.allApp')

@section('title', 'Daftar Bahan Pendukung')
@section('role', 'Purchasing')

@section('content')
<div class="w-full mx-auto mt-4 mb-5 px-6">
    <h2 class="text-3xl font-bold text-center text-gray-800 mb-6">Daftar Bahan Pendukung</h2>

    <form method="GET" action="{{ route('purchasing.daftarbahanpendukung.search') }}" class="mb-4">
        <div class="w-full md:w-1/3 flex">
            <input type="text" name="query" value="{{ request('query') }}" placeholder="Cari bahan pendukung..."
                class="w-full p-2 border border-gray-300 rounded-l-lg focus:outline-none focus:ring-2 focus:ring-blue-300"
                onfocus="this.placeholder=''" onblur="this.placeholder='Cari bahan pendukung...'">

            <button type="submit"
                class="inline-flex items-center gap-2 px-4 border border-l-0 border-gray-300 rounded-r-lg bg-blue-600 text-white text-sm font-semibold hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <circle cx="11" cy="11" r="7" stroke-width="2" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35" />
                </svg>
                <span>Cari</span>
            </button>
        </div>
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

                        {{-- Catatan --}}
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
                            <a href="{{ route('purchasing.daftarbahanpendukung.edit', $bahan->bahan_pendukung_id) }}"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white text-sm font-semibold rounded-lg shadow hover:bg-blue-700 transition-all duration-200">
                                ✏️ Edit
                            </a>
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
                                            $warna = 'bg-yellow-600 text-white';
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
                                    <a href="{{ route('purchasing.daftarbahanpendukung.edit', $bahan->bahan_pendukung_id) }}"
                                    class="inline-flex items-center gap-2 px-4 py-2 bg-blue-500 text-white text-sm font-semibold rounded-lg shadow hover:bg-blue-700 transition-all duration-200">
                                        ✏️ Edit
                                    </a>
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
