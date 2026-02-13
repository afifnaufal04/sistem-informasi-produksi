@extends('layouts.allApp')

@section('title', 'Stok Gudang QC Gagal')
@section('role', 'PPIC')

@section('content')
    <div class="container mx-auto px-5">
        <h2 class="text-3xl font-bold text-left mb-2 text-gray-800">Data QC Gagal</h2>
        <div class="flex justify-end">
            <a href="{{ route('ppic.qcGagal.create') }}"
                class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">Tambah Stok</a>
        </div>

        <div class="bg-white shadow-md rounded-lg overflow-hidden mt-3">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-gray-700 border border-gray-200">
                    <thead class="bg-gray-100 text-gray-800 uppercase text-xs font-semibold">
                        <tr>
                            <th class="px-4 py-3 text-center">No</th>
                            <th class="px-4 py-3 text-left">Nama Barang</th>
                            <th class="px-4 py-3 text-left">Sub Proses</th>
                            <th class="px-4 py-3 text-center">Jumlah</th>
                            <th class="px-4 py-3 text-center">Dari Pemesanan</th>
                            <th class="px-4 py-3 text-left">Catatan</th>
                            <th class="px-4 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="qcGagalTableBody" class="divide-y divide-gray-100 bg-white">
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">Memuat data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="pindahModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg shadow-lg w-full max-w-lg p-6">
            <h3 class="text-xl font-bold text-gray-800 mb-4 text-center">🔄 Pindahkan Barang ke Pemesanan</h3>

            <form id="pindahForm" class="space-y-4">
                <input type="hidden" id="gagal_id" name="gagal_id">
                <input type="hidden" id="barang_id" name="barang_id">
                <input type="hidden" id="sub_proses_id" name="sub_proses_id">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Pemesanan</label>
                    <select id="pemesanan_id" name="pemesanan_id" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        <option value="">-- Pilih Pemesanan --</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang</label>
                    <input id="nama_barang" type="text" class="w-full border-gray-300 rounded-md bg-gray-100" readonly>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Sub Proses</label>
                    <input id="nama_sub_proses" type="text" class="w-full border-gray-300 rounded-md bg-gray-100" readonly>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Dipindahkan</label>
                    <input id="jumlah" name="jumlah" type="number" min="1"
                        class="w-full border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" required>
                </div>

                <div class="flex justify-end gap-3 mt-6">
                    <button type="button" id="cancelBtn"
                        class="px-4 py-2 bg-gray-400 text-white rounded-md hover:bg-gray-500">Batal</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">Pindahkan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Script --}}
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const tableBody = document.querySelector('#qcGagalTableBody');
            const modal = document.querySelector('#pindahModal');
            const cancelBtn = document.querySelector('#cancelBtn');
            const form = document.querySelector('#pindahForm');
            const pemesananSelect = document.querySelector('#pemesanan_id');

            // Fetch data QC gagal
            async function loadData() {
                tableBody.innerHTML = `<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">Sedang mengambil data...</td></tr>`;
                try {
                    const response = await fetch('{{ route("ppic.qcGagal.data") }}');
                    const result = await response.json();

                    if (result.success && result.data.length > 0) {
                        const rows = result.data.map((item, index) => {
                            const namaBarang = item.nama_barang ?? '-';
                            const namaSubProses = item.nama_sub_proses ?? '-';
                            const jumlah = item.jumlah ?? '-';
                            const asalSpk = item.asal_spk ?? '-';
                            const catatan = item.catatan ?? '-';

                            return `
                            <tr class="hover:bg-gray-50 transition-colors duration-150">
                                <td class="px-4 py-3 text-center">${index + 1}</td>
                                <td class="px-4 py-3 font-semibold text-gray-800">${namaBarang}</td>
                                <td class="px-4 py-3 text-gray-700">${namaSubProses}</td>
                                <td class="px-4 py-3 text-center text-gray-700">${jumlah}</td>
                                <td class="px-4 py-3 text-center text-gray-700">${asalSpk}</td>
                                <td class="px-4 py-3 text-gray-600 italic">${catatan}</td>
                                <td class="px-4 py-3 text-center flex justify-center gap-2">
                                    <button 
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs"
                                        onclick="openModal(${item.gudang_qc_gagal_id}, ${item.barang_id ?? 'null'}, ${item.sub_proses_id ?? 'null'}, '${namaBarang}', '${namaSubProses}')">
                                        Pindahkan
                                    </button>
                                    <a href="/ppic/ppic/qc-gagal/edit/${item.gudang_qc_gagal_id}"
                                        class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">
                                        Edit
                                    </a>
                                    <button 
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs"
                                        onclick="hapusData(${item.gudang_qc_gagal_id})">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        `;
                        }).join('');
                        tableBody.innerHTML = rows;
                    } else {
                        tableBody.innerHTML = `<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">Tidak ada data QC gagal.</td></tr>`;
                    }

                } catch (error) {
                    console.error(error);
                    tableBody.innerHTML = `<tr><td colspan="7" class="px-6 py-4 text-center text-red-600">Gagal memuat data.</td></tr>`;
                }
            }

            // Load pemesanan list
            async function loadPemesananList() {
                const res = await fetch('{{ route("ppic.qcGagal.pemesananList") }}');
                const data = await res.json();
                pemesananSelect.innerHTML = `<option value="">-- Pilih Pemesanan --</option>`;
                data.forEach(p => {
                    pemesananSelect.innerHTML += `<option value="${p.pemesanan_id}">${p.no_SPK_kwas}</option>`;
                });
            }

            // Open modal
            window.openModal = async (gagalId, barangId, subProsesId, namaBarang, namaSubProses) => {
                await loadPemesananList();

                document.querySelector('#gagal_id').value = gagalId;
                document.querySelector('#barang_id').value = barangId;
                document.querySelector('#sub_proses_id').value = subProsesId;
                document.querySelector('#nama_barang').value = namaBarang;
                document.querySelector('#nama_sub_proses').value = namaSubProses;
                modal.classList.remove('hidden');
            };

            cancelBtn.addEventListener('click', () => {
                modal.classList.add('hidden');
                form.reset();
            });

            // Submit form pindah
            form.addEventListener('submit', async (e) => {
                e.preventDefault();

                const formData = new FormData(form);
                try {
                    const res = await fetch('{{ route("ppic.qcGagal.pindahkan") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    });
                    const result = await res.json();

                    Swal.fire({
                        icon: result.success ? 'success' : 'info',
                        title: result.success ? 'Berhasil' : 'Info',
                        text: result.message
                    });
                    if (result.success) {
                        modal.classList.add('hidden');
                        form.reset();
                        loadData();
                    }
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Terjadi kesalahan saat memproses data.'
                    });
                    console.error(error);
                }
            });

            // Fungsi hapus data
            window.hapusData = async (gudang_qc_gagal_id) => {
                const ok = await window.__kwasSwalConfirm({
                    title: 'Hapus data?',
                    text: 'Apakah kamu yakin ingin menghapus data ini?',
                    icon: 'warning',
                    confirmButtonText: 'Ya, hapus'
                });
                if (!ok) return;

                try {
                    const res = await fetch(`/ppic/ppic/qc-gagal/delete/${gudang_qc_gagal_id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        }
                    });
                    const result = await res.json();

                    Swal.fire({
                        icon: result.success ? 'success' : 'info',
                        title: result.success ? 'Berhasil' : 'Info',
                        text: result.message
                    });
                    if (result.success) loadData();
                } catch (error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: 'Gagal menghapus data.'
                    });
                    console.error(error);
                }
            };

            loadData();
        });
    </script>
@endsection