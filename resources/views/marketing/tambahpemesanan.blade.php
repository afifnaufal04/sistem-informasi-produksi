@extends('layouts.allApp')

@section('title', 'Tambah Pemesanan')
@section('role', 'Marketing')

@section('content')
<div class="container mx-auto px-6">
    <div class="mb-5 md:mb-8">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-2">Tambah Pemesanan</h1>
        <p class="text-gray-600">Buat pesanan baru (PO) dengan detail lengkap</p>
    </div>

    {{-- Pastikan route ini benar (sesuaikan prefix/name jika perlu) --}}
    <form action="{{ route('marketing.pemesanan.store') }}" method="POST" class="space-y-4 md:space-y-6">
        @csrf
        <!-- Section 1: Informasi Pembeli -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 md:p-8">
                <div class="flex items-center gap-3 mb-3 md:mb-5">
                    <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Informasi Pembeli</h2>
                        <p class="text-sm text-gray-400 font-medium">Data identitas klien dan nomor SPK</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Pembeli -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Pilih Pembeli <span class="text-red-500">*</span></label>
                        <select id="pembeli_id" name="pembeli_id" class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl outline-none transition-all duration-300 font-medium text-gray-700" required>
                            <option value="" disabled selected>Cari Nama Pembeli...</option>
                            @foreach($pembelis as $pembeli)
                                <option value="{{ $pembeli->pembeli_id }}" data-kode="{{ $pembeli->kode_buyer }}">
                                    {{ $pembeli->nama_pembeli }} ({{ $pembeli->kode_buyer }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Nomor SPK Pembeli -->
                    <div class="group">
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nomor PO</label>
                        <input type="text" name="no_PO" class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl outline-none transition-all duration-300 font-medium text-gray-700" placeholder="Contoh: PO/2023/001">
                    </div>

                    <!-- No SPK KWaS -->
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">No SPK KWaS (Otomatis)</label>
                        <input type="text" name="no_SPK_kwas" id="no_SPK_kwas" class="w-full px-5 py-4 bg-gray-100 border-2 border-dashed border-gray-200 rounded-2xl text-gray-500 font-bold outline-none cursor-not-allowed" readonly placeholder="Terisi setelah pilih pembeli">
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 2: Jadwal -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 md:p-8">
                <div class="flex items-center gap-3 mb-3 md:mb-5">
                    <div class="w-12 h-12 bg-orange-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-gray-800">Jadwal Produksi</h2>
                        <p class="text-sm text-gray-400 font-medium">Tentukan timeline pemesanan</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Tanggal Pemesanan <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_pemesanan" class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl outline-none transition-all duration-300 font-medium text-gray-700" required>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Periode Produksi <span class="text-red-500">*</span></label>
                        <input type="date" name="periode_produksi" class="w-full px-5 py-4 bg-gray-50 border-2 border-transparent focus:border-blue-500 focus:bg-white rounded-2xl outline-none transition-all duration-300 font-medium text-gray-700" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section 3: Pilih Barang (Dynamic) -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-5 md:p-8">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-5 md:mb-8">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-green-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">Daftar Barang</h2>
                            <p class="text-sm text-gray-400 font-medium">Tambahkan satu atau lebih item pesanan</p>
                        </div>
                    </div>
                    
                    <button type="button" id="add-item" class="inline-flex items-center gap-2 px-6 py-3 bg-green-50 text-green-600 font-bold rounded-2xl hover:bg-green-100 transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 group-hover:scale-125 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4" /></svg>
                        Tambah Barang
                    </button>
                </div>

                <!-- Container Baris Barang -->
                <div id="items-container" class="space-y-4">
                    <!-- Template Baris (Akan diisi via JS) -->
                    <div class="item-row group grid grid-cols-1 md:grid-cols-12 gap-4 p-4 bg-gray-50 rounded-2xl border border-transparent hover:border-emerald-200 transition-all">
                        <div class="md:col-span-7">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Nama Barang</label>
                            <select name="barang_id[]" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all font-medium text-gray-700" required>
                                <option value="" disabled selected>Pilih Barang...</option>
                                @foreach($barangs as $barang)
                                    <option value="{{ $barang->barang_id }}">{{ $barang->nama_barang }} ( {{ $barang->jenis_barang }} )</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-4">
                            <label class="block text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1 ml-1">Jumlah (Qty)</label>
                            <div class="relative">
                                <input type="number" name="jumlah_pemesanan[]" min="1" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 outline-none transition-all font-bold text-gray-800" placeholder="0" required>
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400 uppercase">Pcs</span>
                            </div>
                        </div>
                        <div class="md:col-span-1 flex items-end justify-center pb-1">
                            <!-- Tombol hapus disembunyikan jika hanya 1 baris (opsional) -->
                            <button type="button" class="remove-item p-3 text-gray-300 hover:text-red-500 hover:bg-red-50 rounded-xl transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Section -->
        <div class="flex flex-col md:flex-row gap-6 items-center justify-between pt-4 pb-10">
            <div class="text-gray-400 text-sm font-medium flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>Pastikan data bertanda <span class="text-red-500 font-bold">*</span> terisi sebelum menyimpan.</span>
            </div>
            <div class="flex gap-4 w-full md:w-auto">
                <a href="{{ route('marketing.pemesanan.index') }}" class="flex-1 md:flex-none px-12 py-4 bg-red-600 border-2 border-red-600 hover:bg-red-700 hover:border-red-700 text-white font-black rounded-2xl shadow-xl shadow-blue-500/20 transition-all transform hover:-translate-y-1 active:scale-95">
                    Batal
                </a>
                <button type="submit" class="flex-1 md:flex-none px-12 py-4 bg-blue-600 border-2 border-blue-600 hover:bg-blue-700 hover:border-blue-700 text-white font-black rounded-2xl shadow-xl shadow-blue-500/20 transition-all transform hover:-translate-y-1 active:scale-95">
                    Simpan Pesanan
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectPembeli = document.getElementById('pembeli_id');
        const inputSPK = document.getElementById('no_SPK_kwas');
        const addItemBtn = document.getElementById('add-item');
        const itemsContainer = document.getElementById('items-container');

        // Logic SPK Otomatis
        const base = "{{ $nomorSPKBase }}"; 
        const end = "{{ $bulanTahun }}";   

        if (selectPembeli) {
            selectPembeli.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                const kodeBuyer = selected ? selected.dataset.kode : '';
                inputSPK.value = kodeBuyer ? `${base}-${kodeBuyer}/${end}` : '';
            });
        }

        // Logic Dynamic Rows
        addItemBtn.addEventListener('click', function() {
            const firstRow = document.querySelector('.item-row');
            const newRow = firstRow.cloneNode(true);
            
            // Reset values
            newRow.querySelector('select').selectedIndex = 0;
            newRow.querySelector('input').value = '';
            
            // Add Remove Logic
            newRow.querySelector('.remove-item').addEventListener('click', function() {
                if (document.querySelectorAll('.item-row').length > 1) {
                    newRow.remove();
                }
            });

            itemsContainer.appendChild(newRow);
        });

        // Initial remove button logic for the first row
        document.querySelector('.remove-item').addEventListener('click', function() {
            if (document.querySelectorAll('.item-row').length > 1) {
                this.closest('.item-row').remove();
            }
        });
    });
</script>

@endsection