@extends('layouts.allApp')

@section('title', 'Daftar Packing')
@section('role', 'Gudang')

@section('content')
    <div class="container mx-auto px-6 w-full">

        <h1 class="text-2xl font-bold mb-6">Daftar Packing</h1>

        {{-- Alert Success --}}
        @if (session('success'))
            <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 relative">
                {{ session('success') }}
                <span onclick="this.parentElement.remove()" class="absolute right-3 top-2 cursor-pointer text-xl">&times;</span>
            </div>
        @endif

        {{-- Alert Error --}}
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        {{-- CARD LIST PACKING --}}
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
            @forelse ($packings as $packing)
                <div class="bg-white rounded-xl shadow border p-5 flex flex-col justify-between">

                    {{-- Header --}}
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <p class="text-xs text-gray-500">No SPK</p>
                            <p class="font-semibold text-gray-800">
                                {{ $packing->pemesananBarang->pemesanan->no_SPK_kwas ?? '-' }}
                            </p>
                        </div>

                        @if($packing->gudang_konfirmasi)
                            <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-semibold">
                                ✔ Terkonfirmasi
                            </span>
                        @else
                            <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full font-semibold">
                                ⏳ Menunggu
                            </span>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="space-y-2 text-sm">
                        <div>
                            <p class="text-gray-500 text-xs">Nama Pembeli</p>
                            <p class="font-medium">
                                {{ $packing->pemesananBarang->pemesanan->pembeli->nama_pembeli ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500 text-xs">Nama Barang</p>
                            <p class="font-medium">
                                {{ $packing->pemesananBarang->barang->nama_barang ?? '-' }}
                            </p>
                        </div>

                        <div class="flex justify-between">
                            <div>
                                <p class="text-gray-500 text-xs">Jumlah</p>
                                <p class="font-semibold">{{ $packing->jumlah_packing }} pcs</p>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs">Tanggal</p>
                                <p class="text-xs">
                                    {{ $packing->created_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Action --}}
                    <div class="mt-4">
                        <button onclick="openDetailModal({{ $packing->packing_id }})"
                            class="w-full text-sm px-4 py-2 rounded-lg font-medium text-white
                                {{ $packing->gudang_konfirmasi ? 'bg-gray-500 hover:bg-gray-600' : 'bg-blue-600 hover:bg-blue-700' }}">
                            {{ $packing->gudang_konfirmasi ? 'Lihat Detail' : 'Detail & Konfirmasi' }}
                        </button>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500">
                    Belum ada data packing
                </div>
            @endforelse
        </div>
    </div>

    {{-- MODAL DETAIL --}}
    <div id="detailModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-xl max-w-3xl w-full p-6 max-h-[90vh] overflow-y-auto">

            {{-- Header --}}
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Detail Packing</h3>
                <button onclick="closeDetailModal()" class="text-gray-600 text-xl">&times;</button>
            </div>

            {{-- Info --}}
            <div class="grid grid-cols-2 gap-4 bg-blue-50 p-4 rounded-lg mb-4 text-sm">
                <div>
                    <p class="text-gray-500">No SPK</p>
                    <p class="font-semibold" id="detail_no_spk">-</p>
                </div>
                <div>
                    <p class="text-gray-500">Pembeli</p>
                    <p class="font-semibold" id="detail_pembeli">-</p>
                </div>
                <div>
                    <p class="text-gray-500">Barang</p>
                    <p class="font-semibold" id="detail_barang">-</p>
                </div>
                <div>
                    <p class="text-gray-500">Jumlah</p>
                    <p class="font-semibold" id="detail_jumlah">-</p>
                </div>
            </div>

            {{-- KEBUTUHAN BAHAN (CARD) --}}
            <h4 class="font-semibold text-sm mb-2">Kebutuhan Bahan Pendukung</h4>
            <div id="detailBahanTableBody" class="space-y-3 mb-4">
                <p class="text-sm text-gray-500">Loading...</p>
            </div>

            {{-- Status --}}
            <div id="detailStatusInfo" class="hidden mb-4 p-4 rounded-lg"></div>

            {{-- Action --}}
            <div class="flex gap-3">
                <button onclick="closeDetailModal()" class="flex-1 bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg">
                    Tutup
                </button>
                <button id="confirmBtn" onclick="confirmPacking()"
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg">
                    Konfirmasi & Kurangi Stok
                </button>
            </div>
        </div>
    </div>

    {{-- SCRIPT --}}
    <script>
        let currentPackingId = null;
        let bahanKebutuhanData = [];

        async function openDetailModal(id) {
            currentPackingId = id;
            document.getElementById('detailModal').classList.remove('hidden');

            const container = document.getElementById('detailBahanTableBody');
            container.innerHTML = '<p class="text-sm text-gray-500">Loading...</p>';

            const res = await fetch(`/gudang/packing/${id}/detail`);
            const data = await res.json();

            document.getElementById('detail_no_spk').innerText = data.packing.no_spk;
            document.getElementById('detail_pembeli').innerText = data.packing.nama_pembeli;
            document.getElementById('detail_barang').innerText = data.packing.nama_barang;
            document.getElementById('detail_jumlah').innerText = data.packing.jumlah_packing + ' pcs';

            bahanKebutuhanData = data.kebutuhan_bahan;
            displayKebutuhanBahan();

            const btn = document.getElementById('confirmBtn');
            btn.disabled = data.packing.gudang_konfirmasi;
        }

        function displayKebutuhanBahan() {
            const container = document.getElementById('detailBahanTableBody');
            container.innerHTML = '';

            bahanKebutuhanData.forEach(b => {
                const sisa = b.stok - b.total_butuh;
                container.innerHTML += `
            <div class="border rounded-lg p-4 ${sisa < 0 ? 'bg-red-50 border-red-300' : ''}">
                <div class="flex justify-between">
                    <p class="font-semibold">${b.nama_bahan}</p>
                    <span class="text-xs ${sisa < 0 ? 'text-red-600' : 'text-green-600'}">
                        ${sisa < 0 ? 'Kurang' : 'Cukup'}
                    </span>
                </div>
                <div class="grid grid-cols-2 gap-2 text-xs mt-2">
                    <div>Total: <b>${b.total_butuh} ${b.satuan}</b></div>
                    <div>Stok: ${b.stok} ${b.satuan}</div>
                    <div>Sisa: <b>${sisa} ${b.satuan}</b></div>
                </div>
            </div>`;
            });
        }

        async function confirmPacking() {
            const ok = await window.__kwasSwalConfirm({
                title: 'Konfirmasi packing?',
                text: 'Yakin konfirmasi packing?',
                icon: 'warning',
                confirmButtonText: 'Ya, konfirmasi',
                confirmButtonColor: '#059669'
            });
            if (!ok) return;

            await fetch(`/gudang/packing/${currentPackingId}/confirm`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            });

            location.reload();
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
        }
    </script>
@endsection