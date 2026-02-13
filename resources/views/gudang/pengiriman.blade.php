{{-- resources/views/gudang/pengiriman/index.blade.php --}}
@extends('layouts.allApp')

@section('title', 'Daftar Pengiriman')
@section('role', 'Gudang')

@section('content')
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .modern-card {
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }

        .modern-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 25px 30px -10px rgba(0, 0, 0, 0.06);
            border-color: #3b82f6;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background-color: #e2e8f0;
            border-radius: 10px;
        }

        /* Style tabel kustom untuk modal */
        .modal-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .modal-table thead th {
            padding: 12px 16px;
            text-align: left;
            font-size: 10px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #64748b;
        }

        .modal-table tbody tr {
            background-color: #ffffff;
            transition: transform 0.2s;
        }

        .modal-table tbody td {
            padding: 16px;
            border-top: 1px solid #f1f5f9;
            border-bottom: 1px solid #f1f5f9;
            font-size: 13px;
        }

        .modal-table tbody td:first-child {
            border-left: 1px solid #f1f5f9;
            border-top-left-radius: 16px;
            border-bottom-left-radius: 16px;
        }

        .modal-table tbody td:last-child {
            border-right: 1px solid #f1f5f9;
            border-top-right-radius: 16px;
            border-bottom-right-radius: 16px;
        }

        .swal2-container {
            z-index: 11000 !important;
        }
    </style>

    <div class="max-w-7xl mx-auto px-6 py-3">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-6">
            <div class="space-y-3">
                <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Daftar Pengiriman</h1>
                <p class="text-slate-500 font-medium">Pantau dan konfirmasi alokasi pengiriman barang keluar.</p>
            </div>
        </div>

        {{-- Notifikasi --}}
        @if(session('success'))
            <div
                class="mb-8 p-5 rounded-2xl bg-emerald-50 border border-emerald-100 text-emerald-800 flex items-center justify-between shadow-sm">
                <div class="flex items-center gap-3">
                    <i class="ph-fill ph-check-circle text-2xl text-emerald-500"></i>
                    <span class="text-sm font-bold">{{ session('success') }}</span>
                </div>
                <button onclick="this.parentElement.remove()"
                    class="h-8 w-8 hover:bg-emerald-100 rounded-full font-bold transition-colors">&times;</button>
            </div>
        @endif

        {{-- Error --}}
        @if ($errors->any())
            <div class="mb-8 p-5 rounded-2xl bg-rose-50 border border-rose-100 text-rose-800 shadow-sm">
                <div class="flex items-center gap-2 mb-2">
                    <i class="ph-bold ph-warning-circle text-xl"></i>
                    <span class="font-bold">Terjadi Kesalahan:</span>
                </div>
                <ul class="list-disc ml-8 text-sm font-medium">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            @forelse ($detail as $row)
                <div class="modern-card bg-white rounded-[2.5rem] p-6 flex flex-col relative overflow-hidden group">

                    {{-- Card Top: Status & ID --}}
                    <div class="flex justify-between items-start mb-5">
                        <div class="flex items-center gap-3">
                            <div
                                class="h-10 w-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white transition-colors duration-500">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                </svg>
                            </div>
                        </div>

                        @if($row->gudang_konfirmasi)
                            <span
                                class="px-3 py-1.5 rounded-lg bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-wider border border-emerald-100 flex items-center gap-1">
                                <i class="ph-bold ph-check-circle"></i> Terkonfirmasi
                            </span>
                        @else
                            <span
                                class="px-3 py-1.5 rounded-lg bg-amber-50 text-amber-600 text-[9px] font-black uppercase tracking-wider border border-amber-100 flex items-center gap-1">
                                <i class="ph-bold ph-clock"></i> Menunggu
                            </span>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="space-y-5 mb-7">
                        <div class="px-1">
                            <h4
                                class="font-extrabold text-slate-900 leading-tight text-lg group-hover:text-blue-600 transition-colors capitalize">
                                {{ $row->produksi->barang->nama_barang }}
                            </h4>
                            <div class="flex gap-2 mt-2">
                                <span class="text-[10px] font-bold bg-blue-50 text-blue-600 px-2 py-0.5 rounded-md">
                                    {{ $row->jumlah_pengiriman }} Unit
                                </span>
                                <span class="text-[10px] font-bold bg-slate-100 item-right text-slate-700 px-2 py-0.5 capitalize rounded-md">
                                    {{ $row->produksi->barang->jenis_barang }}
                                </span>
                            </div>
                        </div>

                        <div class="h-px bg-slate-100 w-full"></div>

                        <div class="grid grid-cols-1 gap-4 px-1">
                            <div class="flex items-center gap-3">
                                <div
                                    class="h-9 w-9 bg-white shadow-sm rounded-xl flex items-center justify-center text-slate-400 group-hover:text-blue-600 transition-all duration-300">
                                    <i class="ph-bold ph-identification-card text-xl"></i>
                                </div>
                                <div class="text-xs">
                                    <p class="text-[8px] font-black text-slate-400 uppercase leading-none mb-1">
                                        Supplier
                                    </p>
                                    <p class="font-bold text-slate-700 leading-none">
                                        {{ $row->supplier->name ?? 'Internal' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 bg-white shadow-sm rounded-xl flex items-center justify-center text-slate-400 group-hover:text-blue-600 transition-all duration-300">
                                    <i class="ph-bold ph-van text-lg"></i>
                                </div>
                                <div class="text-xs">
                                    <p class="text-[8px] font-black text-slate-400 uppercase leading-none mb-1">
                                        Sopir & Kendaraan
                                    </p>
                                    <p class="font-bold text-slate-700 leading-none">
                                        {{ $row->pengiriman->supir->name ?? '-' }} |
                                        {{ $row->pengiriman->kendaraan->nama ?? '-' }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3 pt-1">
                                <div
                                    class="h-9 w-9 bg-white shadow-sm rounded-xl flex items-center justify-center text-slate-400 group-hover:text-blue-600 transition-all duration-300">
                                    <i class="ph-bold ph-calendar text-xl"></i>
                                </div>
                                <div class="text-xs">
                                    <p class="text-[8px] font-black text-slate-400 uppercase leading-none mb-1">
                                        Rencana Pengiriman
                                    </p>
                                    <p class="font-extrabold text-slate-800 leading-none">
                                        {{ \Carbon\Carbon::parse($row->pengiriman->tanggal_pengiriman)->format('d F Y') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action --}}
                    <div class="mt-auto pt-1">
                        <button
                            class="btnDetail w-full py-4 bg-slate-900 text-white rounded-2xl font-bold text-sm hover:bg-blue-600 transition-all shadow-lg hover:shadow-blue-200 flex items-center justify-center gap-2"
                            data-id="{{ $row->detail_pengiriman_id }}" data-status="{{ $row->pengiriman->status }}">
                            <i class="ph-bold ph-magnifying-glass text-lg"></i>
                            Detail & Konfirmasi
                        </button>
                    </div>
                </div>
            @empty
                <div
                    class="col-span-full py-16 bg-white rounded-[3rem] border border-dashed border-slate-200 flex flex-col items-center justify-center text-center">
                    <i class="ph ph-package text-4xl text-slate-300 mb-4"></i>
                    <h3 class="text-xl font-bold text-slate-800">Antrean Kosong</h3>
                </div>
            @endforelse
        </div>

    </div>

    {{-- MODAL DETAIL --}}
    <div id="modalDetail"
        class="hidden fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[9999] items-center justify-center p-4">
        <div
            class="bg-white rounded-[2.5rem] shadow-2xl max-w-3xl w-full overflow-hidden flex flex-col max-h-[90vh] animate-in fade-in zoom-in-95 duration-200">

            <!-- Header Modal -->
            <div class="p-8 border-b border-slate-100 flex justify-between items-center bg-slate-50/50">
                <div class="flex items-center gap-4">
                    <div
                        class="h-12 w-12 bg-blue-600 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-blue-100">
                        <i class="ph-bold ph-list-checks text-2xl"></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-900">Validasi Kebutuhan Bahan</h3>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Inventory Check</p>
                    </div>
                </div>
                <button id="closeModal"
                    class="h-10 w-10 flex items-center justify-center rounded-xl hover:bg-rose-50 hover:text-rose-500 transition-all text-slate-400 border border-slate-100">
                    <i class="ph-bold ph-x text-lg"></i>
                </button>
            </div>

            <!-- Body Modal -->
            <div class="p-8 overflow-y-auto custom-scrollbar bg-slate-50/30">
                <div class="mb-6">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Item Produksi</p>
                    <h4 id="namaBarang" class="text-lg font-extrabold text-slate-800">Memuat data...</h4>
                </div>

                <div class="overflow-x-auto">
                    <table class="modal-table">
                        <thead>
                            <tr>
                                <th>Nama Bahan Pendukung</th>
                                <th>Stok Saat Ini</th>
                                <th class="text-center">Kebutuhan</th>
                                <th class="text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody id="tableBahan">
                            <!-- Data di-inject via JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="p-8 bg-white border-t border-slate-100 flex gap-4">
                <button id="closeBtn"
                    class="flex-1 py-4 bg-slate-100 text-slate-600 rounded-2xl font-bold text-sm hover:bg-slate-200 transition-all">
                    Tutup
                </button>
                <button id="btnKonfirmasi"
                    class="flex-[2] py-4 bg-emerald-600 text-white rounded-2xl font-bold text-sm hover:bg-emerald-700 transition-all shadow-xl shadow-emerald-100 flex items-center justify-center gap-2">
                    <i class="ph-bold ph-check-circle text-xl"></i>
                    Konfirmasi Penggunaan Bahan
                </button>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('modalDetail');
            const closeModal = document.getElementById('closeModal');
            const closeBtn = document.getElementById('closeBtn');
            const namaBarang = document.getElementById('namaBarang');
            const tableBahan = document.getElementById('tableBahan');
            const btnKonfirmasi = document.getElementById('btnKonfirmasi');

            let currentDetailId = null;
            let currentStatus = null;

            document.querySelectorAll('.btnDetail').forEach(btn => {
                btn.addEventListener('click', () => {
                    currentDetailId = btn.dataset.id;
                    currentStatus = btn.dataset.status;

                    tableBahan.innerHTML = `<tr><td colspan="4" class="text-center py-10 opacity-50"><i class="ph-bold ph-circle-notch animate-spin text-2xl"></i></td></tr>`;

                    fetch(`/gudang/pengiriman/detail/${currentDetailId}`)
                        .then(res => res.json())
                        .then(data => {
                            namaBarang.textContent = `${data.nama_barang} (${data.jumlah_pengiriman} Unit)`;
                            tableBahan.innerHTML = '';

                            if (data.bahan.length === 0) {
                                tableBahan.innerHTML = `<tr><td colspan="4" class="text-center py-8 text-slate-400 italic">Tidak ada bahan pendukung.</td></tr>`;
                            }

                            data.bahan.forEach(item => {
                                const isAman = (item.stok - item.total_butuh) >= 0;

                                tableBahan.innerHTML += `
                                        <tr class="shadow-sm">
                                            <td>
                                                <div class="font-bold text-slate-800">${item.nama_bahan}</div>
                                                <div class="text-[10px] text-slate-400">Rasio: ${item.jumlah_per_unit}/unit</div>
                                            </td>
                                            <td class="font-semibold text-slate-600">${item.stok}</td>
                                            <td class="text-center font-black text-blue-600 bg-blue-50/30">${item.total_butuh}</td>
                                            <td class="text-right">
                                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-tighter ${isAman ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700'}">
                                                    <i class="ph-fill ${isAman ? 'ph-check-circle' : 'ph-warning-circle'}"></i>
                                                    ${isAman ? 'Ready' : 'Kurang'}
                                                </span>
                                            </td>
                                        </tr>
                                    `;
                            });

                            // Update UI Tombol
                            if (currentStatus !== 'Menunggu Gudang') {
                                btnKonfirmasi.disabled = true;
                                btnKonfirmasi.innerHTML = '<i class="ph ph-lock"></i> Selesai Diproses';
                                btnKonfirmasi.className = 'flex-[2] py-4 bg-slate-100 text-slate-400 rounded-2xl font-bold text-sm cursor-not-allowed';
                            } else {
                                btnKonfirmasi.disabled = false;
                                btnKonfirmasi.innerHTML = '<i class="ph-bold ph-check-circle text-xl"></i> Konfirmasi';
                                btnKonfirmasi.className = 'flex-[2] py-4 bg-emerald-600 text-white rounded-2xl font-bold text-sm hover:bg-emerald-700 shadow-xl shadow-emerald-100 flex items-center justify-center gap-2';
                            }

                            modal.classList.remove('hidden');
                            modal.classList.add('flex');
                        });
                });
            });

            // ... Event listeners lainnya (closeModal, closeBtn, btnKonfirmasi) tetap sama seperti versi sebelumnya
            btnKonfirmasi.addEventListener('click', () => {
                if (currentStatus !== 'Menunggu Gudang') return;
                if (!window.Swal) {
                    if (!confirm('Konfirmasi penggunaan bahan? Stok akan berkurang otomatis.')) return;
                }

                const proceed = window.Swal
                    ? window.Swal.fire({
                        title: 'Konfirmasi Penggunaan Bahan',
                        text: 'Stok akan berkurang otomatis. Lanjutkan?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#059669',
                        cancelButtonColor: '#6b7280',
                        confirmButtonText: 'Ya, konfirmasi',
                        cancelButtonText: 'Batal',
                    }).then(result => result.isConfirmed)
                    : Promise.resolve(true);

                proceed.then((isConfirmed) => {
                    if (!isConfirmed) return;

                    btnKonfirmasi.disabled = true;
                    btnKonfirmasi.innerHTML = '<i class="ph-bold ph-circle-notch animate-spin"></i> Memproses...';

                    fetch(`/gudang/pengiriman/konfirmasi/${currentDetailId}`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        }
                    })
                        .then(res => res.json())
                        .then(data => {
                            if (!window.Swal) {
                                alert(data.message);
                                window.location.reload();
                                return;
                            }

                            modal.classList.add('hidden');
                            modal.classList.remove('flex');

                            window.Swal.fire({
                                title: 'Berhasil',
                                text: data.message,
                                icon: 'success',
                                confirmButtonColor: '#059669',
                                confirmButtonText: 'OK',
                            }).then(() => window.location.reload());
                        })
                        .catch(() => {
                            if (!window.Swal) {
                                alert('Gagal memproses konfirmasi.');
                                btnKonfirmasi.disabled = false;
                                return;
                            }

                            window.Swal.fire({
                                title: 'Gagal',
                                text: 'Gagal memproses konfirmasi.',
                                icon: 'error',
                                confirmButtonColor: '#dc2626',
                                confirmButtonText: 'OK',
                            });
                            btnKonfirmasi.disabled = false;
                        });
                });
            });

            [closeModal, closeBtn].forEach(el => {
                el.addEventListener('click', () => {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                });
            });
        });
    </script>
@endpush