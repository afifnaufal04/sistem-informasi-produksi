{{-- resources/views/purchasing/pembayaran/index.blade.php --}}
@extends('layouts.allApp')

@section('title', 'Daftar Pembayaran')
@section('role', 'Purchasing')

@section('content')
    <!-- Impor Ikon -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>

    <style>
        body {
            background: radial-gradient(circle at top left, #f8fafc, #f1f5f9);
        }

        .payment-card {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .payment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            border-color: #3b82f6;
        }

        .currency-font {
            font-feature-settings: "tnum";
        }
    </style>

    <div class="max-w-7xl mx-auto px-6 py-3">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-6">
            <div class="space-y-2">
                <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Daftar Pembayaran</h1>
                <p class="text-slate-500 font-medium">Kelola kewajiban pembayaran kepada supplier secara efisien.</p>
            </div>
        </div>

        {{-- Notifikasi Sukses --}}
        @if(session('success'))
            <div
                class="mb-8 p-4 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-800 flex items-center justify-between shadow-sm animate-in fade-in slide-in-from-top-4">
                <div class="flex items-center gap-3">
                    <div class="h-8 w-8 bg-emerald-500 rounded-full flex items-center justify-center text-white shadow-lg">
                        <i class="ph-bold ph-check text-sm"></i>
                    </div>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()"
                    class="h-8 w-8 hover:bg-emerald-100 rounded-full transition-colors font-bold">&times;</button>
            </div>
        @endif

        <!-- Main Grid Content (Ganti Tabel) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 md:gap-4">
            @forelse($detailPengambilan as $detail)
                <div
                    class="payment-card bg-white rounded-[2rem] p-4 md:p-5 flex flex-col justify-between relative overflow-hidden group">

                    <!-- Status Badge -->
                    <div class="absolute top-6 right-6">
                        @if($detail->status_bayar == 'Belum Dibayar')
                            <span
                                class="px-4 py-1.5 rounded-full bg-rose-50 text-rose-600 text-[10px] font-black uppercase tracking-widest border border-rose-100">
                                Belum Dibayar
                            </span>
                        @else
                            <span
                                class="px-4 py-1.5 rounded-full bg-emerald-50 text-emerald-600 text-[10px] font-black uppercase tracking-widest border border-emerald-100">
                                Sudah Dibayar
                            </span>
                        @endif
                    </div>

                    <!-- Card Header -->
                    <div class="mb-5">
                        <div class="flex items-center gap-3 mb-4">
                            <div
                                class="h-10 w-10 bg-slate-100 rounded-xl flex items-center justify-center text-slate-500 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-300">
                                <i class="ph-bold ph-cube text-xl"></i>
                            </div>
                            <div>
                                <h3 class="font-black text-slate-800 leading-tight">
                                    {{ $detail->detailPengiriman->produksi->barang->nama_barang ?? 'Unknown Item' }}
                                </h3>
                                <p class="text-xs font-bold text-slate-400 flex items-center gap-1">
                                    <i class="ph-bold ph-storefront"></i>
                                    {{ $detail->detailPengiriman->supplier->name ?? 'Internal Supplier' }}
                                </p>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-400 font-medium">Volume Produksi</span>
                                <span class="font-bold text-slate-700">{{ number_format($detail->jumlah_diambil, 0, ',', '.') }}
                                    Pcs</span>
                            </div>
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-slate-400 font-medium">Unit Price</span>
                                <span class="font-bold text-slate-700">Rp
                                    {{ number_format($detail->harga_produksi, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Payment Section -->
                    <div class="bg-slate-50 rounded-xl p-4 mb-4 border border-slate-100">
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Total Pembayaran</p>
                        <h4 class="text-2xl font-black text-blue-600 currency-font tracking-tight">
                            Rp {{ number_format($detail->total_pembayaran, 0, ',', '.') }}
                        </h4>
                    </div>

                    <!-- Action Button -->
                    <div>
                        @if($detail->status_bayar == 'Belum Dibayar')
                            <form action="{{ route('purchasing.pembayaran.konfirmasibayar', $detail->detail_pengambilan_id) }}"
                                method="POST" data-swal-confirm="1" data-swal-title="Konfirmasi pembayaran?"
                                data-swal-text="Konfirmasi pembayaran untuk {{ $detail->detailPengiriman->produksi->barang->nama_barang ?? '-' }}?">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="w-full py-4 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:bg-blue-600 transition-all shadow-xl shadow-slate-200 flex items-center justify-center gap-2">
                                    <i class="ph-bold ph-check-circle text-lg"></i>
                                    Konfirmasi Bayar
                                </button>
                            </form>
                        @else
                            <div
                                class="w-full py-4 bg-emerald-50 text-emerald-600 rounded-2xl font-bold text-sm border border-emerald-100 flex items-center justify-center gap-2">
                                <i class="ph-bold ph-seal-check text-lg"></i>
                                Pembayaran Lunas
                            </div>
                        @endif
                    </div>

                    <!-- Background Decoration -->
                    <div
                        class="absolute -left-4 -bottom-4 opacity-[0.03] group-hover:opacity-[0.08] transition-opacity pointer-events-none">
                        <i class="ph ph-bank text-[120px]"></i>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 flex flex-col items-center justify-center text-center opacity-40">
                    <div class="h-24 w-24 bg-slate-200 rounded-full flex items-center justify-center mb-4">
                        <i class="ph ph-money text-5xl text-slate-400"></i>
                    </div>
                    <p class="text-sm font-medium text-slate-500 max-w-xs mx-auto">Tidak ada antrean pembayaran yang tertunda
                        saat ini.</p>
                </div>
            @endforelse
        </div>
    </div>
@endsection