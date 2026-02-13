@extends('layouts.allApp')

@section('title', 'Daftar Packing')
@section('role', 'Packing')

@section('content')
    <div class="container mx-auto px-6 py-6">

        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
            <h1 class="text-3xl font-bold">Daftar Packing</h1>
        </div>

        {{-- Alert Success --}}
        @if (session('success'))
            <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 relative">
                {{ session('success') }}
                <span onclick="document.getElementById('alert-success').remove()"
                    class="absolute right-3 top-2 cursor-pointer font-bold">×</span>
            </div>
        @endif

        {{-- Alert Error --}}
        @if ($errors->any())
            <div id="alert-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 relative">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
                <span onclick="document.getElementById('alert-error').remove()"
                    class="absolute right-3 top-2 cursor-pointer font-bold">×</span>
            </div>
        @endif

        {{-- ================= DESKTOP TABLE ================= --}}
        <div class="hidden md:block bg-white shadow rounded-xl overflow-hidden">
            <table class="w-full">
                <thead class="bg-green-600 text-white">
                    <tr>
                        <th class="px-4 py-3 text-center">No</th>
                        <th class="px-4 py-3 text-left">No SPK</th>
                        <th class="px-4 py-3 text-left">Pembeli</th>
                        <th class="px-4 py-3 text-left">Barang</th>
                        <th class="px-4 py-3 text-center">Jumlah</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-center">Progress</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">

                    @forelse ($packings as $index => $packing)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-center">{{ $index + 1 }}</td>
                            <td class="px-4 py-3">
                                {{ $packing->pemesananBarang->pemesanan->no_SPK_kwas ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $packing->pemesananBarang->pemesanan->pembeli->nama_pembeli ?? '-' }}
                            </td>
                            <td class="px-4 py-3">
                                {{ $packing->pemesananBarang->barang->nama_barang ?? '-' }}
                                ({{ $packing->pemesananBarang->barang->jenis_barang ?? '-' }})
                            </td>
                            <td class="px-4 py-3 text-center font-semibold text-blue-600">
                                {{ $packing->jumlah_packing }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-3 py-1 text-xs rounded-full
                                    @if($packing->status_packing === 'Selesai') bg-green-100 text-green-700
                                    @elseif($packing->status_packing === 'Dalam Proses') bg-blue-100 text-blue-700
                                    @else bg-yellow-100 text-yellow-700
                                    @endif">
                                    {{ $packing->status_packing }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @php
                                    $progress = ($packing->jumlah_selesai_packing / $packing->jumlah_packing) * 100;
                                @endphp

                                <div class="w-full bg-gray-200 rounded-full h-3">
                                    <div class="bg-green-500 h-3 rounded-full" style="width: {{ $progress }}%">
                                    </div>
                                </div>

                                <p class="text-xs text-center mt-1">
                                    {{ $packing->jumlah_selesai_packing }} / {{ $packing->jumlah_packing }}
                                </p>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @if($packing->gudang_konfirmasi)
                                    <button onclick="updateProgress({{ $packing->packing_id }}, {{ $packing->jumlah_packing }})"
                                        class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded text-xs">
                                        Update Progress
                                    </button>
                                @else
                                    <button disabled
                                        onclick="updateProgress({{ $packing->packing_id }}, {{ $packing->jumlah_packing }})"
                                        class="bg-gray-500 cursor-not-allowed text-white px-3 py-1 rounded text-xs">
                                        Update Progress
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-8 text-gray-500">
                                Belum ada data packing
                            </td>
                        </tr>
                    @endforelse

                </tbody>
            </table>
        </div>

        {{-- ================= MOBILE CARD ================= --}}
        <div class="md:hidden space-y-4 mt-4 px-2">
            @forelse ($packings as $index => $packing)
                <div
                    class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden transition-all active:scale-[0.98]">
                    <!-- Header: Status & Index -->
                    <div class="bg-gray-50/50 px-5 py-3 flex justify-between items-center border-b border-gray-100">
                        <div class="flex items-center gap-2">
                            <span
                                class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-[10px] font-bold text-blue-600">
                                {{ $index + 1 }}
                            </span>
                        </div>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                            @if($packing->status_packing === 'Selesai') 
                                bg-emerald-100 text-emerald-700
                            @elseif($packing->status_packing === 'Dalam Proses') 
                                bg-blue-100 text-blue-700
                            @else 
                                bg-amber-100 text-amber-700
                            @endif">
                            <span class="w-1.5 h-1.5 rounded-full mr-1.5 
                                @if($packing->status_packing === 'Selesai') bg-emerald-500
                                @elseif($packing->status_packing === 'Dalam Proses') bg-blue-500
                                @else bg-amber-500 @endif">
                            </span>
                            {{ $packing->status_packing }}
                        </span>
                    </div>

                    <div class="p-5">
                        <div class="grid grid-cols-2 gap-4 mb-4">
                            <div>
                                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-tight">No. SPK</p>
                                <p class="text-sm font-bold text-gray-800 break-words">
                                    {{ $packing->pemesananBarang->pemesanan->no_SPK_kwas ?? '-' }}
                                </p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] uppercase font-bold text-gray-400 tracking-tight">Pembeli</p>
                                <p class="text-sm text-gray-700 truncate">
                                    {{ $packing->pemesananBarang->pemesanan->pembeli->nama_pembeli ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <div class="mb-5 bg-gray-50 rounded-2xl p-3 border border-dashed border-gray-200">
                            <p class="text-[10px] uppercase font-bold text-gray-400 mb-1">Nama Barang</p>
                            <p class="text-sm font-semibold text-blue-900">
                                {{ $packing->pemesananBarang->barang->nama_barang ?? '-' }}
                                ({{ $packing->pemesananBarang->barang->jenis_barang ?? '-' }})
                            </p>
                        </div>
                        @php
                            $progress = ($packing->jumlah_packing > 0) ? ($packing->jumlah_selesai_packing / $packing->jumlah_packing) * 100 : 0;
                        @endphp

                        <div class="space-y-2">
                            <div class="flex justify-between items-end">
                                <div>
                                    <span
                                        class="text-2xl font-black text-gray-800">{{ $packing->jumlah_selesai_packing }}</span>
                                    <span class="text-sm text-gray-400 font-medium">/ {{ $packing->jumlah_packing }} pcs</span>
                                </div>
                                <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded-lg">
                                    {{ number_format($progress, 0) }}%
                                </span>
                            </div>

                            <div class="relative w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                <div class="absolute top-0 left-0 h-full transition-all duration-500 ease-out rounded-full
                                    @if($progress >= 100) bg-emerald-500 @else bg-blue-500 @endif"
                                    style="width: {{ $progress }}%">
                                    <div class="absolute inset-0 bg-white/20 w-full h-full animate-pulse"></div>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6">
                            <button onclick="updateProgress({{ $packing->packing_id }}, {{ $packing->jumlah_packing }})"
                                class="w-full flex items-center justify-center gap-2 bg-slate-900 hover:bg-slate-800 text-white py-3 px-4 rounded-2xl text-sm font-bold shadow-lg shadow-slate-200 transition-all active:scale-95">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                </svg>
                                Update Progress
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center py-20 px-6">
                    <div class="bg-gray-100 p-4 rounded-full mb-4">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <p class="text-gray-500 font-medium text-center">Belum ada data packing untuk saat ini.</p>
                </div>
            @endforelse
        </div>

    </div>

    {{-- FORM UPDATE STATUS --}}
    <form id="updateStatusForm" method="POST" style="display:none;">
        @csrf
        @method('PUT')
    </form>

    <!-- UPDATE PROGRESS MODAL -->
    <div id="updateModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4"
        onclick="if(event.target === this) closeUpdateModal()">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-md p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold">Update Progress Packing</h3>
                <button type="button" onclick="closeUpdateModal()" class="text-gray-600 text-xl">&times;</button>
            </div>

            <form id="updateModalForm" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block text-sm text-gray-600 mb-1">Jumlah Selesai Packing</label>
                    <input id="modalJumlahInput" name="jumlah_selesai_packing" type="number" min="0"
                        class="w-full border rounded px-3 py-2" required>
                    <p class="text-xs text-gray-500 mt-1">Max: <span id="modalMaxJumlah">0</span></p>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeUpdateModal()"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 px-4 py-2 rounded-lg">Batal</button>
                    <button type="submit"
                        class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentUpdateId = null;
        let currentMaxJumlah = 0;

        function updateProgress(id, maxJumlah) {
            // Open modal to input jumlah instead of using prompt
            currentUpdateId = id;
            currentMaxJumlah = parseInt(maxJumlah) || 0;

            document.getElementById('modalMaxJumlah').innerText = currentMaxJumlah;
            const input = document.getElementById('modalJumlahInput');
            input.value = '';
            input.max = currentMaxJumlah;
            input.min = 0;

            const form = document.getElementById('updateModalForm');
            form.action = `/packing/daftarpacking/update-status/${id}`;

            document.getElementById('updateModal').classList.remove('hidden');

            // focus input after a tick so it's visible
            setTimeout(() => input.focus(), 50);
        }

        function closeUpdateModal() {
            document.getElementById('updateModal').classList.add('hidden');
        }

        document.getElementById('updateModalForm').addEventListener('submit', function (e) {
            const input = document.getElementById('modalJumlahInput');
            const val = parseInt(input.value);

            if (isNaN(val) || val < 0 || val > currentMaxJumlah) {
                e.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'Input tidak valid',
                    text: 'Jumlah tidak valid!'
                });
                input.focus();
                return false;
            }

            // allow normal form submission to the route
        });
    </script>
@endsection