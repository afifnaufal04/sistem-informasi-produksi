@extends('layouts.allApp')

@section('title', 'Produksi Saya')
@section('role', 'Quality Control')

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
                            <th class="px-4 py-3 text-left text-sm font-semibold">Supplier</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Nama Barang</th>
                            <th class="px-4 py-3 text-left text-sm font-semibold">Sub Proses</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Jumlah</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Status</th>
                            <th class="px-4 py-3 text-center text-sm font-semibold">Deadline</th>
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
                                <td class="px-4 py-3 text-sm text-gray-600">{{ $item['supplier_name'] }}</td>
                                <td class="px-4 py-3">
                                    <p class="text-sm font-semibold text-gray-800">{{ $item['nama_barang'] }}</p>
                                    <p class="text-xs text-gray-500">Diterima: {{ $item['waktu_diterima']->format('d/m/Y H:i') }}</p>
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
                                    <span class="text-sm text-gray-700">
                                        {{ $item['tanggal_selesai'] ? $item['tanggal_selesai']->format('d/m/Y') : '-' }}
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
                                    <div class="flex gap-2 justify-center">
                                        @if($item['status_pengerjaan'] == 'Selesai')
                                            {{-- Tombol muncul jika sudah selesai --}}
                                            @if($item['tombol_aksi'])
                                                <button 
                                                    onclick="openRejectModal({{ $item['detail_pengiriman_id'] }}, '{{ addslashes($item['nama_barang']) }}', {{ $item['jumlah_pengiriman'] }}, {{ $item['qc_reject'] ?? 0 }})"
                                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-medium transition disabled:opacity-50"
                                                    {{ $item['jumlah_pengiriman'] == 0 ? 'disabled' : '' }}>
                                                    Reject
                                                </button>
                                            @else
                                                <button 
                                                    onclick="openQcModal({{ $item['detail_pengiriman_id'] }}, '{{ addslashes($item['nama_barang']) }}', {{ $item['jumlah_selesai'] }}, {{ $item['lolos_qc'] }}, {{ $item['gagal_qc'] }})"
                                                    class="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded text-xs font-medium transition disabled:opacity-50"
                                                    {{ $item['jumlah_selesai'] == 0 ? 'disabled' : '' }}>
                                                    Nilai Kualitas
                                                </button>
                                            @endif

                                        @elseif($item['status_pengerjaan'] == 'Perlu Perbaikan')
                                            {{-- Penanda untuk status Perlu Perbaikan --}}
                                            <span class="text-[10px] font-semibold text-yellow-600 italic flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                Sedang dalam perbaikan
                                            </span>

                                        @elseif($item['status_pengerjaan'] == 'Dalam Pengerjaan')
                                            {{-- Penanda baru untuk status Dalam Pengerjaan --}}
                                            <span class="text-[10px] font-semibold text-blue-600 italic flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                                Sedang dikerjakan
                                            </span>
                                        @endif                                          
                                    </div>
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
                            <p class="text-xs text-gray-500 mt-1">Supplier: {{ $item['supplier_name'] }}</p>
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

                    <div class="text-sm text-gray-700 mb-3">
                        Deadline: {{ $item['tanggal_selesai'] ? $item['tanggal_selesai']->format('d/m/Y') : '-' }}
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

                    <div class="flex gap-2 mt-4">
                        @if($item['status_pengerjaan'] == 'Selesai')
                            @if($item['tombol_aksi'])
                                <button 
                                    onclick="openRejectModal({{ $item['detail_pengiriman_id'] }}, '{{ $item['nama_barang'] }}', {{ $item['jumlah_pengiriman'] }}, {{ $item['qc_reject'] ?? 0 }})"
                                    class="flex-1 bg-red-500 hover:bg-red-600 text-white px-3 py-2 rounded text-xs font-medium transition"
                                    @if($item['jumlah_pengiriman'] == 0) disabled @endif>
                                    Reject
                                </button>          
                            @else
                            <button 
                                    onclick="openQcModal({{ $item['detail_pengiriman_id'] }}, '{{ $item['nama_barang'] }}', {{ $item['jumlah_selesai'] }}, {{ $item['lolos_qc'] }}, {{ $item['gagal_qc'] }})"
                                    class="flex-1 bg-purple-500 hover:bg-purple-600 text-white px-3 py-2 rounded text-xs font-medium transition"
                                    @if($item['jumlah_selesai'] == 0) disabled @endif>
                                    Cek Kualitas
                                </button> 
                            @endif
                        
                            @elseif($item['status_pengerjaan'] == 'Perlu Perbaikan')
                                <div class="flex-1 text-center py-2 bg-yellow-50 border border-yellow-200 rounded-lg text-xs text-yellow-700 font-medium italic flex items-center justify-center gap-2">
                                    Sedang dalam perbaikan
                                </div>

                            @elseif($item['status_pengerjaan'] == 'Dalam Pengerjaan')
                                <div class="flex-1 text-center py-2 bg-blue-50 border border-blue-200 rounded-lg text-xs text-blue-700 font-medium italic flex items-center justify-center gap-2">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Sedang dikerjakan
                                </div>
                            @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>

{{-- Modal Nilai Kualitas (QC) - TANPA REJECT --}}
<div id="qcModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Penilaian Kualitas</h3>
            <button onclick="closeQcModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="mb-4 bg-blue-50 p-3 rounded-lg">
            <p class="text-sm font-medium text-gray-700">Barang: <span id="qc_nama_barang" class="text-blue-600"></span></p>
            <p class="text-sm font-medium text-gray-700">Jumlah Selesai: <span id="qc_jumlah_selesai" class="text-blue-600"></span></p>
        </div>

        <form id="qcForm" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Lolos QC</label>
                <input type="number" name="jumlah_lolos" id="jumlah_lolos" min="0" required
                    oninput="hitungTotalQc()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Gagal QC (Dapat Diperbaiki)</label>
                <input type="number" name="jumlah_gagal" id="jumlah_gagal" min="0" required readonly
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed focus:ring-0"
                    title="Nilai ini terisi otomatis dan tidak dapat diubah secara manual">
            </div>

            <div class="mb-4 p-4 rounded-lg" id="totalQcInfo">
                <p class="text-sm font-medium">Total: <span id="totalQcValue" class="font-bold">0</span> / <span id="maxQcValue">0</span></p>
                <p id="qcValidasiMessage" class="text-xs mt-1"></p>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                <textarea name="catatan" id="qc_catatan" rows="3"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                    placeholder="Tambahkan catatan..."></textarea>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closeQcModal()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition">
                    Batal
                </button>
                <button type="submit" id="qcSubmitBtn"
                    class="flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition">
                    Simpan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Reject --}}
<div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Barang Reject</h3>
            <button onclick="closeRejectModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="mb-4 bg-red-50 p-3 rounded-lg border border-red-200">
            <p class="text-sm font-medium text-gray-700">Barang: <span id="reject_nama_barang" class="text-red-600"></span></p>
            <p class="text-sm font-medium text-gray-700">Jumlah Pengiriman: <span id="reject_jumlah_pengiriman" class="text-red-600"></span></p>
            <p class="text-sm font-medium text-gray-700">Reject Sebelumnya: <span id="reject_sebelumnya" class="text-red-600"></span></p>
        </div>

        <form id="rejectForm" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Reject (Tidak Dapat Diperbaiki)</label>
                <input type="number" name="jumlah_reject" id="jumlah_reject" min="0" required
                    oninput="validasiReject()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Masukkan jumlah barang yang tidak dapat diperbaiki</p>
            </div>

            <div class="mb-4 p-4 rounded-lg hidden" id="rejectValidasiInfo">
                <p id="rejectValidasiMessage" class="text-xs"></p>
            </div>


            <div class="flex gap-3">
                <button type="button" onclick="closeRejectModal()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition">
                    Batal
                </button>
                <button type="submit" id="rejectSubmitBtn"
                    class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition">
                    Simpan Reject
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let maxJumlahSelesai = 0;
    let maxJumlahPengiriman = 0;

    // Handle filter change
    function handleFilterChange(value) {
        const url = new URL(window.location.href);
        url.searchParams.set('qc_filter', value);
        window.location.href = url.toString();
    }

    // Modal QC (Lolos & Gagal)
    function openQcModal(id, namaBarang, jumlahSelesai, lolosQc, gagalQc) {
        const modal = document.getElementById('qcModal');
        const form = document.getElementById('qcForm');
        
        maxJumlahSelesai = jumlahSelesai;

        form.action = `/qc/nilai-kualitas/${id}`;

        document.getElementById('qc_nama_barang').textContent = namaBarang;
        document.getElementById('qc_jumlah_selesai').textContent = jumlahSelesai;
        document.getElementById('maxQcValue').textContent = jumlahSelesai;

        document.getElementById('jumlah_lolos').value = lolosQc || 0;
        // jumlah_gagal is auto-filled (readonly) — set initial value
        document.getElementById('jumlah_gagal').value = gagalQc || (maxJumlahSelesai - (lolosQc || 0));
        document.getElementById('qc_catatan').value = '';

        hitungTotalQc();
        modal.classList.remove('hidden');
    }

    function closeQcModal() {
        document.getElementById('qcModal').classList.add('hidden');
    }

    function hitungTotalQc() {
        const lolosInput = document.getElementById('jumlah_lolos');
        const gagalInput = document.getElementById('jumlah_gagal');

        const lolos = parseInt(lolosInput.value) || 0;

        // Always auto-fill jumlah_gagal based on sisa (readonly)
        if (gagalInput) {
            let autoGagal = maxJumlahSelesai - lolos;
            if (autoGagal < 0) autoGagal = 0;
            gagalInput.value = autoGagal;
        }

        const gagal = parseInt(gagalInput.value) || 0;
        const total = lolos + gagal;
        document.getElementById('totalQcValue').textContent = total;

        const totalInfo = document.getElementById('totalQcInfo');
        const validasiMessage = document.getElementById('qcValidasiMessage');
        const submitBtn = document.getElementById('qcSubmitBtn');

        if (total === maxJumlahSelesai) {
            totalInfo.className = 'mb-4 p-4 rounded-lg bg-green-100 border border-green-300';
            validasiMessage.textContent = '✓ Total sudah sesuai!';
            validasiMessage.className = 'text-xs mt-1 text-green-700';
            submitBtn.disabled = false;
            submitBtn.className = 'flex-1 bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg font-medium transition';
        } else if (total < maxJumlahSelesai) {
            totalInfo.className = 'mb-4 p-4 rounded-lg bg-yellow-100 border border-yellow-300';
            validasiMessage.textContent = `⚠ Kurang ${maxJumlahSelesai - total} item`;
            validasiMessage.className = 'text-xs mt-1 text-yellow-700';
            submitBtn.disabled = true;
            submitBtn.className = 'flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg font-medium cursor-not-allowed';
        } else {
            totalInfo.className = 'mb-4 p-4 rounded-lg bg-red-100 border border-red-300';
            validasiMessage.textContent = `✗ Kelebihan ${total - maxJumlahSelesai} item`;
            validasiMessage.className = 'text-xs mt-1 text-red-700';
            submitBtn.disabled = true;
            submitBtn.className = 'flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg font-medium cursor-not-allowed';
        }
    }

    // Modal Reject
    function openRejectModal(id, namaBarang, jumlahPengiriman, rejectSebelumnya) {
        const modal = document.getElementById('rejectModal');
        const form = document.getElementById('rejectForm');
        
        maxJumlahPengiriman = jumlahPengiriman;

        form.action = `/qc/reject-barang/${id}`;

        document.getElementById('reject_nama_barang').textContent = namaBarang;
        document.getElementById('reject_jumlah_pengiriman').textContent = jumlahPengiriman;
        document.getElementById('reject_sebelumnya').textContent = rejectSebelumnya;

        document.getElementById('jumlah_reject').value = '';
        document.getElementById('rejectValidasiInfo').classList.add('hidden');

        const submitBtn = document.getElementById('rejectSubmitBtn');
        submitBtn.disabled = false;
        submitBtn.className = 'flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition';

        modal.classList.remove('hidden');
    }

    function closeRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    function validasiReject() {
        const jumlahReject = parseInt(document.getElementById('jumlah_reject').value) || 0;
        const validasiInfo = document.getElementById('rejectValidasiInfo');
        const validasiMessage = document.getElementById('rejectValidasiMessage');
        const submitBtn = document.getElementById('rejectSubmitBtn');

        if (jumlahReject > maxJumlahPengiriman) {
            validasiInfo.className = 'mb-4 p-4 rounded-lg bg-red-100 border border-red-300';
            validasiMessage.textContent = `✗ Jumlah reject melebihi jumlah pengiriman (${maxJumlahPengiriman})`;
            validasiMessage.className = 'text-xs text-red-700';
            validasiInfo.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.className = 'flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg font-medium cursor-not-allowed';
        } else if (jumlahReject > 0) {
            validasiInfo.className = 'mb-4 p-4 rounded-lg bg-yellow-100 border border-yellow-300';
            validasiMessage.textContent = `⚠ ${jumlahReject} barang akan direject`;
            validasiMessage.className = 'text-xs text-yellow-700';
            validasiInfo.classList.remove('hidden');
            submitBtn.disabled = false;
            submitBtn.className = 'flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition';
        } else {
            validasiInfo.classList.add('hidden');
            submitBtn.disabled = false;
            submitBtn.className = 'flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium transition';
        }
    }

    // Close modals on outside click
    document.getElementById('qcModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeQcModal();
        }
    });

    document.getElementById('rejectModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeRejectModal();
        }
    });

    // Keep jumlah_gagal readonly and auto-updated; listen only to jumlah_lolos changes
    (function(){
        var jumlahLolosInput = document.getElementById('jumlah_lolos');
        if (jumlahLolosInput) {
            jumlahLolosInput.addEventListener('input', function(){
                hitungTotalQc();
            });
        }
    })();
</script>
@endsection