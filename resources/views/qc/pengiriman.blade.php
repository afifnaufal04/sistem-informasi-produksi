{{-- resources/views/qc/pengiriman/index.blade.php --}}
@extends('layouts.allApp')

@section('title', 'Daftar Pengiriman')
@section('role', 'Quality Control')

@section('content')
<script src="https://unpkg.com/@phosphor-icons/web"></script>

<div class="max-w-7xl mx-auto px-6 py-3 bg-slate-50/50 min-h-screen">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-8 gap-4">
        <div class="space-y-2">
            <h1 class="text-4xl font-black text-slate-900 tracking-tighter">Daftar Pengiriman</h1>
            <p class="text-slate-500 font-medium max-w-2xl text-base leading-relaxed">
                Validasi kualitas produk dan pantau logistik pengiriman barang secara real-time.
            </p>
        </div>
    </div>

    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="mb-6 p-4 rounded-[1.5rem] bg-emerald-50 border border-emerald-200 text-emerald-900 flex items-center justify-between shadow-sm shadow-emerald-100">
            <div class="flex items-center gap-4">
                <div class="h-10 w-10 bg-emerald-600 text-white rounded-xl flex items-center justify-center shadow-lg">
                    <i class="ph-fill ph-check-circle text-2xl"></i>
                </div>
                <div class="flex flex-col">
                    <span class="text-xs font-black uppercase tracking-tight">Berhasil!</span>
                    <span class="text-sm font-medium opacity-80">{{ session('success') }}</span>
                </div>
            </div>
            <button onclick="this.parentElement.remove()" class="h-8 w-8 hover:bg-emerald-200/50 rounded-full transition-all flex items-center justify-center">
                <i class="ph-bold ph-x text-emerald-900"></i>
            </button>
        </div>
    @endif

    {{-- Main Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($pengiriman as $item)
            <div class="group bg-white rounded-[2.5rem] p-6 flex flex-col relative overflow-hidden border border-slate-200 hover:border-blue-500 hover:shadow-xl hover:shadow-blue-500/10 transition-all duration-500 hover:-translate-y-1">
                
                {{-- Decor --}}
                <div class="absolute -right-6 -top-6 h-20 w-20 bg-slate-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                {{-- Card Header --}}
                <div class="flex justify-between items-start mb-6 relative z-10">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="h-2 w-2 rounded-full bg-blue-600 animate-ping"></span>
                            <h3 class="text-xl font-black text-slate-900 tracking-tight">#{{ $loop->iteration }}</h3>
                        </div>
                        <div class="flex items-center gap-2 mt-2 px-2 py-0.5 bg-slate-100 rounded-md w-fit">
                            <i class="ph ph-calendar-blank text-slate-500 text-[10px]"></i>
                            <span class="text-[10px] font-bold text-slate-600">{{ \Carbon\Carbon::parse($item->tanggal_pengiriman)->format('d M Y') }}</span>
                        </div>
                    </div>

                    @switch($item->status)
                        @case('Selesai')
                            <span class="px-3 py-1.5 bg-emerald-100 text-emerald-700 text-[9px] font-black uppercase tracking-wider rounded-xl border border-emerald-200 flex items-center gap-1.5">
                                <i class="ph-fill ph-check-circle text-xs"></i> Selesai
                            </span>
                            @break
                        @case('Dalam Pengiriman')
                            <span class="px-3 py-1.5 bg-blue-100 text-blue-700 text-[9px] font-black uppercase tracking-wider rounded-xl border border-blue-200 flex items-center gap-1.5">
                                <i class="ph-fill ph-truck text-xs"></i> Transit
                            </span>
                            @break
                        @case('Sedang Dipersiapkan')
                            <span class="px-3 py-1.5 bg-amber-100 text-amber-700 text-[9px] font-black uppercase tracking-wider rounded-xl border border-amber-200 flex items-center gap-1.5">
                                <i class="ph-fill ph-package text-xs"></i> Proses
                            </span>
                            @break
                        @default
                            <span class="px-3 py-1.5 bg-slate-100 text-slate-600 text-[9px] font-black uppercase tracking-wider rounded-xl border border-slate-200">
                                {{ $item->status }}
                            </span>
                    @endswitch
                </div>

                {{-- Info Utama --}}
                <div class="space-y-3 mb-6 bg-slate-50/50 p-4 rounded-[1.5rem] border border-slate-100">
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 bg-white shadow-sm rounded-xl flex items-center justify-center text-slate-400 group-hover:text-blue-600 transition-all duration-300">
                            <i class="ph-bold ph-identification-card text-xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Petugas QC</span>
                            <span class="text-xs font-bold text-slate-800 leading-tight">
                            {{ $item->qc->name ?? '-' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="h-9 w-9 bg-white shadow-sm rounded-xl flex items-center justify-center text-slate-400 group-hover:text-blue-600 transition-all duration-300">
                            <i class="ph-bold ph-van text-xl"></i>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Supir & Kendaraan</span>
                            <span class="text-xs font-bold text-slate-800 leading-tight">{{ $item->supir->name ?? '-' }} <span class="text-slate-300 mx-0.5">|</span>{{ $item->kendaraan->nama ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Detail Button --}}
                <button type="button"
                            onclick="openDetailModal({{ $loop->index }})"
                            class="openDetailModal w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-3 rounded-lg transition-all duration-200 flex items-center justify-between shadow-md hover:shadow-lg text-sm">
                        <span class="flex items-center gap-1.5">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                            </svg>
                            Lihat Detail
                        </span>
                        <span class="bg-white/20 px-2 py-0.5 rounded-full text-xs font-bold">
                            {{ count($item->detailPengiriman) }}
                        </span>
                    </button>

                {{-- Detail Muatan --}}
                {{-- <div class="mb-6">
                    <button type="button" 
                        onclick="this.nextElementSibling.classList.toggle('hidden'); this.querySelector('.arrow').classList.toggle('rotate-180')"
                        class="w-full flex items-center justify-between p-3 bg-white border-2 border-slate-100 rounded-2xl hover:bg-slate-50 transition-all">
                        <div class="flex items-center gap-2">
                            <div class="h-7 w-7 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center">
                                <i class="ph-bold ph-list-bullets text-sm"></i>
                            </div>
                            <span class="text-[10px] font-black text-slate-700 uppercase tracking-wide">Lihat Muatan</span>
                        </div>
                        <i class="ph-bold ph-caret-down arrow transition-transform duration-300 text-slate-400 text-xs"></i>
                    </button>
                    
                    <div class="hidden mt-2 space-y-1.5 max-h-40 overflow-y-auto pr-1">
                        @foreach($item->detailPengiriman ?? [] as $detail)
                            <div class="flex items-center justify-between p-3 bg-white border border-slate-100 rounded-xl shadow-sm">
                                <div class="flex flex-col">
                                    <span class="text-[11px] font-extrabold text-slate-800 leading-none mb-1">{{ $detail->produksi->barang->nama_barang ?? '-' }}</span>
                                    <span class="text-[8px] font-bold text-blue-500 uppercase">{{ $detail->supplier->name ?? 'Internal' }}</span>
                                </div>
                                <div class="px-2 py-0.5 bg-blue-600 text-white rounded-md text-[9px] font-black">
                                    {{ $detail->jumlah_pengiriman }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div> --}}



                {{-- Footer Action --}}
                <div class="mt-auto pt-4 border-t border-slate-100">
                    @if ($item->status === 'Menunggu QC')
                        <form action="{{ route('qc.pengiriman.updateStatus', $item->pengiriman_id) }}" 
                            method="POST" 
                            data-swal-confirm="1" data-swal-title="Selesaikan validasi?" data-swal-text="Selesaikan validasi kualitas untuk pengiriman ini?">
                            @csrf
                            @method('PUT')
                            <button type="submit" 
                                class="w-full py-3 bg-slate-900 text-white rounded-xl font-extrabold text-xs hover:bg-blue-600 hover:shadow-lg hover:shadow-blue-200 transition-all flex items-center justify-center gap-2 active:scale-95">
                                <i class="ph-bold ph-seal-check text-lg"></i>
                                Validasi Selesai
                            </button>
                        </form>
                    @elseif ($item->status === 'Menunggu Gudang')
                        <div class="w-full py-3 bg-red-600 text-white rounded-xl font-extrabold text-xs flex items-center justify-center gap-2 opacity-90 cursor-not-allowed">
                            <i class="ph-bold ph-clock text-lg"></i>
                            Menunggu Gudang
                        </div>
                    @else
                        <div class="w-full py-3 bg-emerald-600 text-white rounded-xl font-extrabold text-xs flex items-center justify-center gap-2 opacity-60">
                            <i class="ph-bold ph-lock-keyhole text-lg"></i>
                            Sudah Valid
                        </div>
                    @endif
                </div>

                   {{-- Modal Detail Barang --}}
                
            </div>
            <div id="modal-{{ $loop->index }}" class="hidden fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4">
                    <div class="bg-white rounded-[24px] shadow-2xl max-w-sm w-full max-h-[80vh] flex flex-col overflow-hidden">
                        {{-- Modal Header --}}
                        <div class="p-4 border-b border-gray-100 flex justify-between items-center">
                            <div>
                                <h3 class="text-base font-bold text-gray-800">Detail Barang</h3>
                                <p class="text-[10px] text-gray-500">Pengiriman • {{ $item->tipe_pengiriman }}</p>
                            </div>
                            <button type="button" onclick="closeDetailModal({{ $loop->index }})"
                                class="w-8 h-8 flex items-center justify-center rounded-full bg-gray-50 text-gray-400 hover:text-gray-600 transition-colors text-xl">&times;</button>
                        </div>

                        {{-- Modal Body --}}
                        <div class="p-4 overflow-y-auto custom-scrollbar flex-grow">
                            <div class="space-y-2">
                                @forelse($item->detailPengiriman as $detail)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-xl border border-gray-100">
                                        
                                            <div class="min-w-0 flex-1">
                                                <p class="font-bold text-gray-800 text-xs truncate">
                                                    {{ $detail->produksi->barang->nama_barang ?? '-' }}
                                                </p>
                                                <p class="text-[9px] text-blue-400 uppercase mt-0.5 font-medium tracking-tight truncate">
                                                    {{ $detail->subProses->proses->nama_proses ?? '-' }}
                                                </p>
                                                <p class="text-[9px] text-gray-400 uppercase mt-0.5 font-medium tracking-tight truncate">
                                                    {{ $detail->supplier->name ?? '-' }}
                                                </p>
                                                <div class="flex items-center gap-2 mb-1">
                                                    <svg class="w-4 h-4 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                                    </svg>
                                                    <p class="text-[11px] uppercase text-gray-500"><span class="text-[10px] font-bold text-teal-600 uppercase">{{ $detail->subProses->nama_sub_proses ?? '-' }}</span></p>
                                                </div>
                                                
                                                
                                            </div>
                                        
                                            <div class="ml-3 text-right">
                                                <p class="text-base font-black text-indigo-600 leading-none">{{ $detail->jumlah_pengiriman }}</p>
                                                <p class="text-[10px] font-bold text-gray-400 uppercase mt-0.5">pcs</p>
                                                <p class="text-[10px] text-gray-500 mt-1">{{ $detail->butuh_bp ? '✓ Bahan Pendukung' : '✗ Tanpa Bahan Pendukung' }}</p>
                                            </div>
                                            
                                            
                                    </div>
                                    
                                @empty
                                    <p class="text-center text-gray-400 py-6 text-xs">Tidak ada detail barang</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Modal Footer --}}
                        <div class="p-4 border-t border-gray-50 bg-gray-50/50">
                            <button type="button" onclick="closeDetailModal({{ $loop->index }})"
                                class="w-full py-3 bg-white border border-gray-200 text-gray-600 font-bold text-xs rounded-xl hover:bg-gray-100 transition shadow-sm">
                                Tutup
                            </button>
                        </div>
                    </div>
                </div>
        @empty
            <div class="col-span-full py-24 bg-white rounded-[3rem] border-4 border-dashed border-slate-100 flex flex-col items-center justify-center text-center">
                <div class="h-20 w-20 bg-slate-50 rounded-full flex items-center justify-center mb-4 shadow-inner">
                    <i class="ph-fill ph-package text-5xl text-slate-200"></i>
                </div>
                <h3 class="text-2xl font-black text-slate-800 tracking-tighter">Antrean Validasi Kosong</h3>
                <p class="text-slate-400 font-medium mt-1 max-w-sm text-sm">Semua jadwal pengiriman telah divalidasi oleh tim QC.</p>
            </div>
        @endforelse
    </div>

</div>
@endsection

@push('scripts')
<script>
function openDetailModal(index) {
            const modal = document.getElementById('modal-' + index);
            if (modal) {
                modal.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }
        }
        function closeDetailModal(index) {
            const modal = document.getElementById('modal-' + index);
            if (modal) {
                modal.classList.add('hidden');
                document.body.style.overflow = 'auto';
            }
        }

        // Close modal when clicking outside of it
        document.addEventListener('DOMContentLoaded', function () {
            document.addEventListener('click', function (event) {
                if (event.target.id.startsWith('modal-')) {
                    const index = event.target.id.replace('modal-', '');
                    closeDetailModal(index);
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function (event) {
                if (event.key === 'Escape') {
                    document.querySelectorAll('[id^="modal-"]').forEach(modal => {
                        if (!modal.classList.contains('hidden')) {
                            const index = modal.id.replace('modal-', '');
                            closeDetailModal(index);
                        }
                    });
                }
            });
        });
</script>
@endpush