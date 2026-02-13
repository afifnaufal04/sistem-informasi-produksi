@extends('layouts.allApp')

@section('title', 'Produksi Saya')
@section('role', 'Supplier')

@section('content')
<div class="container mx-auto px-6 py-6">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-3xl font-bold">Daftar Produksi</h2>
        
        {{-- Filter Dropdown --}}
        <div class="flex items-center gap-2">
            <label for="filterStatus" class="text-sm font-medium text-gray-700">Filter:</label>
            <select id="filterStatus" onchange="handleFilterChange(this.value)" 
                class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <option value="belum" {{ $filter == 'belum' ? 'selected' : '' }}>Belum Selesai</option>
                <option value="selesai" {{ $filter == 'selesai' ? 'selected' : '' }}>Selesai</option>
                <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>Semua</option>
            </select>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    @if ($produksiList->isEmpty())
        <div class="bg-white shadow-md rounded-xl p-8 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Tidak ada data produksi</h3>
            <p class="mt-1 text-sm text-gray-500">
                @if($filter == 'belum')
                    Belum ada barang yang perlu dikerjakan
                @elseif($filter == 'selesai')
                    Belum ada barang yang sudah selesai diambil
                @else
                    Belum ada data produksi
                @endif
            </p>
        </div>
    @else
        {{-- Tabel Desktop --}}
        <div class="bg-white shadow-md rounded-xl overflow-hidden hidden md:block">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gradient-to-r bg-green-600 text-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-sm font-semibold">No</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Nama Barang</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Sub Proses</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Jumlah</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Status</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Selesai</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Lolos QC</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Gagal QC</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Reject</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Dapat Diambil</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Sudah Diambil</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($produksiList as $index => $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3 text-sm text-gray-700">{{ $index + 1 }}</td>
                                <td class="px-4 py-3">
                                    <p class="text-sm font-semibold text-gray-800">{{ $item['nama_barang'] }}</p>
                                    <p class="text-xs text-gray-500">
                                        Diterima: {{ $item['waktu_diterima']->translatedFormat('d F Y H:i') }}
                                    </p>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item['sub_proses'] }}</td>
                                <td class="px-4 py-3 text-center">
                                    <span class="text-xl font-bold text-blue-600">{{ $item['jumlah_pengiriman'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        @if($item['status_pengerjaan'] === 'Selesai') bg-green-100 text-green-800
                                        @elseif($item['status_pengerjaan'] === 'Dalam Pengerjaan') bg-blue-100 text-blue-800
                                        @elseif($item['status_pengerjaan'] === 'Perlu Perbaikan') bg-yellow-100 text-yellow-800
                                        @endif">
                                        @if($item['status_pengerjaan'] === 'Selesai') Selesai
                                        @elseif($item['status_pengerjaan'] === 'Dalam Pengerjaan') Dalam Pengerjaan
                                        @elseif($item['status_pengerjaan'] === 'Perlu Perbaikan') Perlu Perbaikan
                                        @endif
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="font-semibold text-green-600">{{ $item['jumlah_selesai'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="font-semibold text-green-600">{{ $item['lolos_qc'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="font-semibold text-red-600">{{ $item['gagal_qc'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="font-semibold text-red-600">{{ $item['qc_reject'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="font-semibold text-blue-600">{{ $item['dapat_diambil'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="font-semibold text-gray-600">{{ $item['sudah_diambil'] }}</span>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <button 
                                        onclick="openEditModal({{ $item['detail_pengiriman_id'] }}, '{{ $item['status_pengerjaan'] }}', {{ $item['jumlah_selesai'] }}, {{ $item['jumlah_pengiriman'] }}, {{ $item['sudah_diambil'] }})"
                                        class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs font-medium transition">
                                        Edit
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Card Mobile --}}
        <div class="md:hidden space-y-4">
            @foreach ($produksiList as $index => $item)
                <div class="bg-white shadow-md rounded-xl p-4 border border-gray-200">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $item['nama_barang'] }}</h3>
                            <p class="text-xs text-gray-500">{{ $item['sub_proses'] }}</p>
                        </div>
                        <span class="px-2 py-1 text-xs font-medium rounded-full
                            @if($item['status_pengerjaan'] === 'Selesai') bg-green-100 text-green-800
                            @elseif($item['status_pengerjaan'] === 'Dalam Pengerjaan') bg-blue-100 text-blue-800
                            @elseif($item['status_pengerjaan'] === 'Perlu Perbaikan') bg-yellow-100 text-yellow-800
                            @endif">
                            @if($item['status_pengerjaan'] === 'Selesai') Selesai
                            @elseif($item['status_pengerjaan'] === 'Dalam Pengerjaan') Dalam Pengerjaan
                            @elseif($item['status_pengerjaan'] === 'Perlu Perbaikan') Perlu Perbaikan
                            @endif
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-2 text-sm mb-3">
                        <div>
                            <p class="text-gray-600">Jumlah:</p>
                            <p class="text-xl font-bold text-blue-600">{{ $item['jumlah_pengiriman'] }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Selesai:</p>
                            <p class="font-semibold text-green-600">{{ $item['jumlah_selesai'] }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Lolos QC:</p>
                            <p class="font-semibold text-green-600">{{ $item['lolos_qc'] }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Gagal QC:</p>
                            <p class="font-semibold text-red-600">{{ $item['gagal_qc'] }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Reject:</p>
                            <p class="font-semibold text-red-600">{{ $item['qc_reject'] }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Dapat Diambil:</p>
                            <p class="font-semibold text-blue-600">{{ $item['dapat_diambil'] }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Sudah Diambil:</p>
                            <p class="font-semibold text-gray-600">{{ $item['sudah_diambil'] }}</p>
                        </div>
                    </div>

                    <button 
                        onclick="openEditModal({{ $item['detail_pengiriman_id'] }}, '{{ $item['status_pengerjaan'] }}', {{ $item['jumlah_selesai'] }}, {{ $item['jumlah_pengiriman'] }}, {{ $item['sudah_diambil'] }})"
                        class="w-full bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded font-medium transition">
                        Edit Status
                    </button>
                </div>
            @endforeach
        </div>
    @endif

</div>

{{-- Modal Edit --}}
<div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Edit Status Pengerjaan</h3>
            <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="editForm" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Pengerjaan</label>
                <select name="status_pengerjaan" id="status_pengerjaan" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="Dalam Pengerjaan">Dalam Pengerjaan</option>
                    <option value="Selesai">Selesai</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Selesai</label>
                <input type="number" name="jumlah_selesai" id="jumlah_selesai" min="0" placeholder="0" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <p id="validasiInfo" class="text-xs text-gray-500 mt-1"></p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeEditModal()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition">
                    Batal
                </button>
                <button type="submit" 
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Handle filter change
    function handleFilterChange(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('qc_filter', value);
        window.location.href = url.toString();
    }

    function openEditModal(id, status, jumlahSelesai, jumlahPengiriman, sudahDiambil) {
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        const statusSelect = document.getElementById('status_pengerjaan');
        const jumlahInput = document.getElementById('jumlah_selesai');
        const validasiInfo = document.getElementById('validasiInfo');

        // Set form action
        form.action = `/supplier/produksi/${id}`;

        // Set values
        statusSelect.value = status;
        // Only set the value if it's greater than 0; otherwise leave empty so placeholder (0) is visible
        if (typeof jumlahSelesai === 'number' && jumlahSelesai > 0) {
            jumlahInput.value = jumlahSelesai;
        } else {
            jumlahInput.value = '';
        }
        jumlahInput.max = jumlahPengiriman;
        jumlahInput.min = sudahDiambil;

        // Set validasi info
        validasiInfo.textContent = `Min: ${sudahDiambil} (sudah diambil), Max: ${jumlahPengiriman} (total pengiriman)`;

        // Show modal
        modal.classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Close modal when clicking outside
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditModal();
        }
    });
</script>
@endsection