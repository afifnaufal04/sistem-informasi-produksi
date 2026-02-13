@extends('layouts.allApp')

@section('title', 'Daftar Packing')
@section('role', 'PPIC')

@section('content')
    <div class="container mx-auto px-6 py-3">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <h1 class="text-3xl font-extrabold">Daftar Packing</h1>
        </div>

        @if (session('success'))
            <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span onclick="document.getElementById('alert-success').remove();"
                    class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">&times;</span>
            </div>
        @endif

        @if ($errors->any())
            <div id="alert-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                @foreach ($errors->all() as $error)
                    <p class="block sm:inline">{{ $error }}</p>
                @endforeach
                <span onclick="document.getElementById('alert-error').remove();"
                    class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">&times;</span>
            </div>
        @endif

        {{-- Card List --}}
        <div id="packingGrid" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse ($packings as $index => $packing)
                @php
                    $progress = $packing->jumlah_selesai_packing > 0 ? ($packing->jumlah_selesai_packing / $packing->jumlah_packing) * 100 : 0;
                @endphp

                <div id="packing-card-{{ $packing->packing_id }}"
                    data-spk="{{ strtolower($packing->pemesananBarang->pemesanan->no_SPK_kwas ?? '') }}"
                    data-buyer="{{ strtolower($packing->pemesananBarang->pemesanan->pembeli->nama_pembeli ?? '') }}"
                    data-product="{{ strtolower($packing->pemesananBarang->barang->nama_barang ?? '') }}"
                    data-status="{{ $packing->status_packing }}"
                    class="bg-white rounded-2xl shadow-md p-5 border border-gray-100 flex flex-col justify-between">

                    {{-- Header --}}
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="bg-blue-100 text-blue-600 font-bold rounded-xl w-10 h-10 flex items-center justify-center">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <p class="text-xs text-gray-500">No SPK</p>
                                <p class="font-semibold text-gray-800">
                                    {{ $packing->pemesananBarang->pemesanan->no_SPK_kwas ?? '-' }}
                                </p>
                                <p class="text-xs text-gray-500">
                                    Pembeli : {{ $packing->pemesananBarang->pemesanan->pembeli->nama_pembeli ?? '-' }}
                                </p>
                            </div>
                        </div>
                        <span class="px-3 py-1 text-xs rounded-full font-medium
                                    @if($packing->status_packing === 'Selesai') bg-green-100 text-green-700
                                    @elseif($packing->status_packing === 'Dalam Proses') bg-blue-100 text-blue-700
                                    @else bg-yellow-100 text-yellow-700
                                    @endif">
                            {{ $packing->status_packing }}
                        </span>
                    </div>

                    {{-- Content --}}
                    <div class="mb-4">
                        <p class="text-sm text-gray-500">Barang</p>
                        <div class="flex justify-between items-center">
                            <p class="font-semibold text-gray-800">
                                {{ $packing->pemesananBarang->barang->nama_barang ?? '-' }}
                                ({{ $packing->pemesananBarang->barang->jenis_barang ?? '-' }})
                            </p>
                            <p class="font-semibold text-blue-600">
                                {{ $packing->jumlah_packing }} pcs
                            </p>
                        </div>
                    </div>

                    {{-- Progress --}}
                    <div class="mb-5">
                        <div class="flex justify-between text-xs mb-1">
                            <span class="text-gray-500">Progres</span>
                            <span class="font-semibold text-blue-600">{{ $packing->jumlah_selesai_packing }}</span>
                        </div>

                        <div class="w-full bg-gray-200 rounded-full h-2 overflow-hidden">
                            <div class="h-2 rounded-full transition-all duration-500
                                        @if($progress == 0) bg-gray-400
                                        @elseif($progress >= 50) bg-blue-500
                                        @elseif($progress == 100) bg-green-600
                                        @endif" style="width: {{ $progress }}%">
                            </div>
                        </div>
                    </div>

                    {{-- Action --}}
                    <div class="flex gap-2 mt-auto">
                        @if($packing->status_packing === 'Menunggu')
                            <button onclick="updateStatus({{ $packing->packing_id }}, 'Dalam Proses')"
                                class="flex-1 bg-green-500 hover:bg-green-600 text-white py-2 rounded-xl text-xs font-semibold">
                                Mulai
                            </button>
                        @endif

                        @if($packing->jumlah_selesai_packing == 0)
                            <form action="{{ route('ppic.packing.delete', $packing->packing_id) }}" method="POST" class="flex-1"
                                data-confirm-delete="1" data-swal-text="Yakin ingin menghapus data packing ini?">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-xl text-xs font-semibold">
                                    🗑 Hapus
                                </button>
                            </form>
                        @else
                            <button disabled
                                class="flex-1 bg-gray-400 text-gray-600 py-2 rounded-xl text-xs font-semibold cursor-not-allowed">
                                🗑 Hapus
                            </button>
                        @endif
                    </div>

                </div>

            @empty
                <div class="col-span-full text-center text-gray-500 py-12">
                    <p class="text-lg">Belum ada data packing</p>
                </div>
            @endforelse

        </div>

    </div>

    {{-- Form untuk Update Status --}}
    <form id="updateStatusForm" method="POST" style="display: none;">
        @csrf
        @method('PUT')
    </form>

    <script>
        function updateStatus(packingId, status) {
            window.__kwasSwalConfirm({
                title: 'Ubah status?',
                text: `Yakin ingin mengubah status menjadi "${status}"?`,
                icon: 'question',
                confirmButtonText: 'Ya, ubah',
                confirmButtonColor: '#2563eb'
            }).then((ok) => {
                if (!ok) return;
                const form = document.getElementById('updateStatusForm');
                form.action = `/ppic/daftarpacking/update-status/${packingId}`;

                const existing = form.querySelector('input[name="status_packing"]');
                if (existing) existing.remove();

                const statusInput = document.createElement('input');
                statusInput.type = 'hidden';
                statusInput.name = 'status_packing';
                statusInput.value = status;
                form.appendChild(statusInput);

                form.submit();
            });
        }
    </script>
@endsection