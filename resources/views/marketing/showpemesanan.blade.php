@extends('layouts.allApp')
@section('title', 'Daftar Pemesanan')
@section('role', 'Marketing')

@section('content')
    <div class="container mx-auto px-4">

        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg mb-4">
                {{ session('success') }}
            </div>
            <meta http-equiv="refresh" content="1;url={{ route('marketing.pemesanan.index') }}">
        @endif

        <div class="flex items-center justify-between mb-6">
            <h2 class="text-3xl font-bold text-gray-900">Daftar Pemesanan</h2>
        </div>

        {{-- Search dan Filter --}}
        <div class="mb-6 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
            <form action="{{ route('marketing.pemesanan.index') }}" method="GET" class="space-y-4">
                <div class="flex flex-col md:flex-row gap-4">
                    {{-- Search by Nama Pembeli --}}
                    <div class="flex-1">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Cari
                            Pembeli</label>
                        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Nama pembeli..."
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">

                    </div>

                    {{-- Filter by Status --}}
                    <div class="w-full md:w-48">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Status</label>
                        <select name="status"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                            <option value="">Semua Status</option>
                            <option value="diproses" {{ $status == 'diproses' ? 'selected' : '' }}>Diproses</option>
                            <option value="selesai" {{ $status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex gap-2 items-end">
                        <button type="submit"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium whitespace-nowrap h-fit">
                            Filter
                        </button>

                        @if($search || $status)
                            <a href="{{ route('marketing.pemesanan.index') }}"
                                class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-medium whitespace-nowrap h-fit">
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
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
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

        {{-- Tombol Tambah (hanya untuk marketing) --}}
        @if(optional(Auth::user())->role === 'marketing')
            <div class="flex justify-end mb-4">
                <a href="{{ route('marketing.pemesanan.create') }}"
                    class="flex bg-green-600 items-center p-2 rounded-lg text-white hover:bg-green-700 transition-colors">
                    <svg class="w-5 h-5 transition duration-75" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                        fill="currentColor" viewBox="0 0 640 512">
                        <path
                            d="M0 8C0-5.3 10.7-16 24-16l45.3 0c27.1 0 50.3 19.4 55.1 46l.4 2 412.7 0c20 0 35.1 18.2 31.4 37.9L537.8 235.8c-5.7 30.3-32.1 52.2-62.9 52.2l-303.6 0 5.1 28.3c2.1 11.4 12 19.7 23.6 19.7L456 336c13.3 0 24 10.7 24 24s-10.7 24-24 24l-255.9 0c-34.8 0-64.6-24.9-70.8-59.1L77.2 38.6c-.7-3.8-4-6.6-7.9-6.6L24 32C10.7 32 0 21.3 0 8zM160 464a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zm224 0a48 48 0 1 1 96 0 48 48 0 1 1 -96 0zM336 78.4c-13.3 0-24 10.7-24 24l0 33.6-33.6 0c-13.3 0-24 10.7-24 24s10.7 24 24 24l33.6 0 0 33.6c0 13.3 10.7 24 24 24s24-10.7 24-24l0-33.6 33.6 0c13.3 0 24-10.7 24-24s-10.7-24-24-24l-33.6 0 0-33.6c0-13.3-10.7-24-24-24z" />
                    </svg>
                    <span class="flex-1 ms-3 whitespace-nowrap">Tambah Pemesanan</span>
                </a>
            </div>
        @endif

        {{-- ================= MOBILE VIEW (CARD) ================= --}}
        <div class="space-y-4 md:hidden">
            @forelse($pesanans as $pemesanan)
                <div
                    class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all duration-200 active:scale-[0.98]">
                    <div class="p-5">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex flex-col">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Nama
                                    Pembeli</span>
                                <h3 class="font-bold text-gray-900 text-lg leading-tight">
                                    {{ $pemesanan->pembeli->nama_pembeli }}
                                </h3>
                            </div>

                            @if($pemesanan->status_pemesanan == 'diproses')
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase bg-amber-50 text-amber-700 border border-amber-100">
                                    Diproses
                                </span>
                            @elseif($pemesanan->status_pemesanan == 'selesai')
                                <span
                                    class="inline-flex items-center px-2.5 py-1 rounded-lg text-[10px] font-bold uppercase bg-emerald-50 text-emerald-700 border border-emerald-100">
                                    Selesai
                                </span>
                            @endif
                        </div>

                        <div class="grid grid-cols-2 gap-4 mb-5">
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">No. SPK KWaS</span>
                                <p class="text-sm font-semibold text-blue-600 truncate">{{ $pemesanan->no_SPK_kwas }}</p>
                            </div>
                            <div class="space-y-1">
                                <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">No. PO
                                    Pembeli</span>
                                <p class="text-sm font-semibold text-gray-700 truncate">{{ $pemesanan->no_PO }}</p>
                            </div>
                        </div>

                        <div
                            class="flex items-center text-xs text-gray-500 mb-5 bg-gray-50 p-2.5 rounded-xl border border-gray-100">
                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                </path>
                            </svg>
                            <span class="font-medium">Tanggal:
                                {{ \Carbon\Carbon::parse($pemesanan->tanggal_pemesanan)->locale('id')->format('d F Y') }}</span>
                        </div>

                        <div class="flex gap-3">
                            <a href="{{ route('marketing.pemesanan.show', $pemesanan->pemesanan_id) }}"
                                class="flex-1 inline-flex justify-center items-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl text-xs transition-all shadow-sm shadow-blue-100">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                    </path>
                                </svg>
                                Lihat Detail
                            </a>

                            @if(optional(Auth::user())->role === 'marketing')
                                <form action="{{ route('marketing.pemesanan.destroy', $pemesanan->pemesanan_id) }}" method="POST"
                                    class="flex-none" data-swal-delete="{{ $pemesanan->pembeli->nama_pembeli }}"
                                    data-swal-text="Yakin ingin menghapus data pemesanan ini?">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        class="inline-flex justify-center items-center bg-red-50 hover:bg-red-600 text-red-600 hover:text-white w-12 h-12 rounded-xl transition-all border border-red-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200 p-8 text-center">
                    <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                        </path>
                    </svg>
                    <p class="text-gray-500 font-bold">
                        @if($search || $status)
                            Tidak ada pemesanan yang sesuai dengan filter
                        @else
                            Tidak ada pemesanan
                        @endif
                    </p>
                </div>
            @endforelse
        </div>

        {{-- ================= DESKTOP VIEW (TABLE) ================= --}}
        <div class="hidden md:block overflow-x-auto bg-white rounded-xl shadow">
            <table class="w-full">
                <thead>
                    <tr class="bg-green-600 text-white text-sm">
                        <th class="px-4 py-3 text-left">No</th>
                        <th class="px-4 py-3 text-left">Nama Pembeli</th>
                        <th class="px-4 py-3 text-left">No PO Pembeli</th>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">No SPK KWaS</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @forelse($pesanans as $pemesanan)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $loop->iteration }}</td>
                            <td class="px-4 py-3">{{ $pemesanan->pembeli->nama_pembeli }}</td>
                            <td class="px-4 py-3">{{ $pemesanan->no_PO }}</td>
                            <td class="px-4 py-3">{{ $pemesanan->tanggal_pemesanan }}</td>
                            <td class="px-4 py-3">{{ $pemesanan->no_SPK_kwas }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-3 py-1 rounded-full text-xs
                                                        @if($pemesanan->status_pemesanan == 'diproses') bg-yellow-100 text-yellow-700
                                                        @elseif($pemesanan->status_pemesanan == 'selesai') bg-green-100 text-green-700
                                                        @endif">
                                    {{ ucfirst($pemesanan->status_pemesanan) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('marketing.pemesanan.show', $pemesanan->pemesanan_id) }}"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                        Detail
                                    </a>

                                    @if(optional(Auth::user())->role === 'marketing')
                                        <form action="{{ route('marketing.pemesanan.destroy', $pemesanan->pemesanan_id) }}" method="POST"
                                            data-swal-delete="{{ $pemesanan->pembeli->nama_pembeli }}"
                                            data-swal-text="Yakin hapus pemesanan ini?">
                                            @csrf
                                            @method('DELETE')
                                            <button class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-6 text-gray-500">
                                @if($search || $status)
                                    Tidak ada pemesanan yang sesuai dengan filter
                                @else
                                    Tidak ada pemesanan
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
@endsection