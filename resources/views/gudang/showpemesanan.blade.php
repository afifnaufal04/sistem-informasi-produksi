@extends('layouts.allApp')

@section('title', 'Daftar Pemesanan')
@section('role', 'Gudang')

@section('content')
<div class="container mx-auto px-4 py-6">
{{-- Header Section --}}
<div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
<div>
<h2 class="text-3xl font-extrabold text-gray-900 tracking-tight">Daftar Pemesanan</h2>
<p class="text-sm text-gray-500">Kelola dan pantau status pemesanan masuk.</p>
</div>
</div>

    {{-- Search dan Filter --}}
    <div class="mb-6 bg-white p-5 rounded-xl shadow-sm border border-gray-100">
        <form action="{{ route('gudang.pemesanan.index') }}" method="GET" class="space-y-4">
            <div class="flex flex-col md:flex-row gap-4">
                {{-- Search by Nama Pembeli --}}
                <div class="flex-1">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Cari Pembeli</label>
                    <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Masukkan nama pembeli..."
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all">
                </div>

                {{-- Filter by Status --}}
                <div class="w-full md:w-48">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Status</label>
                    <select name="status"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all">
                        <option value="">Semua Status</option>
                        <option value="diproses" {{ (isset($status) && $status == 'diproses') ? 'selected' : '' }}>Diproses</option>
                        <option value="selesai" {{ (isset($status) && $status == 'selesai') ? 'selected' : '' }}>Selesai</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-2 items-end">
                    <button type="submit"
                        class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium h-[42px]">
                        Filter
                    </button>

                    @if(request('search') || request('status'))
                        <a href="{{ route('gudang.pemesanan.index') }}"
                            class="px-6 py-2 bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors font-medium h-[42px] flex items-center">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Info Hasil Filter --}}
    @if(request('search') || request('status'))
        <div class="mb-6 flex items-center justify-between bg-blue-50 border border-blue-100 rounded-lg px-4 py-3">
            <div class="flex items-center gap-2 text-sm text-blue-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>
                    Menampilkan
                    @if(request('search')) hasil pencarian "<strong>{{ request('search') }}</strong>" @endif
                    @if(request('search') && request('status')) dan @endif
                    @if(request('status')) status <strong>{{ ucfirst(request('status')) }}</strong> @endif
                    ({{ $pesanans->count() }} data ditemukan)
                </span>
            </div>
        </div>
    @endif

    {{-- Alert Success --}}
    @if (session('success'))
        <div id="success-alert" class="flex items-center p-4 mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-800 rounded-r-xl shadow-sm transition-opacity duration-500">
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
        <script>
            setTimeout(() => {
                const alert = document.getElementById('success-alert');
                if(alert) alert.style.opacity = '0';
            }, 3000);
        </script>
    @endif

    {{-- Main Content - Grid View --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($pesanans as $pemesanan)
            @php $isDiproses = $pemesanan->status_pemesanan == 'diproses'; @endphp

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden transition-all hover:shadow-md hover:border-blue-200 flex flex-col">
                <div class="flex flex-1">
                    {{-- Status Indicator Sidebar --}}
                    <div class="w-1.5 {{ $isDiproses ? 'bg-amber-400' : 'bg-emerald-500' }}"></div>

                    {{-- Card Content --}}
                    <div class="flex-1 p-5">
                        <div class="flex justify-between items-start mb-4">
                            <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $isDiproses ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ $pemesanan->status_pemesanan }}
                            </span>
                            <span class="text-[10px] text-gray-400 font-medium">{{ $pemesanan->created_at->format('d M Y') }}</span>
                        </div>

                        <h3 class="text-lg font-bold text-gray-900 mb-1 truncate" title="{{ $pemesanan->pembeli->nama_pembeli ?? 'Pembeli' }}">
                            {{ $pemesanan->pembeli->nama_pembeli ?? 'Unknown Buyer' }}
                        </h3>

                        {{-- Info Grid --}}
                        <div class="grid grid-cols-2 gap-4 py-3 border-y border-gray-50 my-4">
                            <div>
                                <p class="text-[9px] font-semibold text-gray-400 uppercase mb-0.5 tracking-wider">No P.O</p>
                                <p class="text-xs font-medium text-gray-700 truncate">{{ $pemesanan->no_PO }}</p>
                            </div>
                            <div>
                                <p class="text-[9px] font-semibold text-gray-400 uppercase mb-0.5 tracking-wider">No SPK KWaS</p>
                                <p class="text-xs font-mono font-bold text-blue-600">{{ $pemesanan->no_SPK_kwas }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-[9px] font-semibold text-gray-400 uppercase mb-0.5 tracking-wider">No SPK Pembeli</p>
                                <p class="text-xs font-medium text-gray-700">{{ $pemesanan->no_SPK_pembeli }}</p>
                            </div>
                        </div>

                        {{-- Action Area --}}
                        <div class="mt-auto flex items-center justify-between gap-2">
                            <a href="{{ route('gudang.pemesanan.show', $pemesanan->pemesanan_id)}}"
                                class="flex-1 inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-xs transition-all shadow-sm">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Detail Pesanan
                            </a>

                            @if(Auth::user()->role === 'marketing')
                                <form action="{{ route('marketing.pemesanan.destroy', $pemesanan->pemesanan_id) }}" method="POST"
                                    onsubmit="return confirm('Yakin hapus pemesanan ini?')" class="flex-none">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="p-2.5 text-red-500 hover:bg-red-50 rounded-xl transition-colors border border-gray-100 hover:border-red-100"
                                        title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.895-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full flex flex-col items-center justify-center py-20 bg-white rounded-3xl border-2 border-dashed border-gray-100">
                <div class="p-6 bg-gray-50 rounded-full mb-4 text-gray-300">
                    <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Tidak Ada Pemesanan</h3>
                <p class="text-sm text-gray-500 max-w-xs text-center mt-2">Coba ubah kata kunci pencarian atau filter status Anda.</p>
            </div>
        @endforelse
    </div>
</div>


@endsection