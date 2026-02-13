@extends('layouts.allApp')

@section('title', 'Daftar Barang & Stok')
@section('role', 'PPIC')

@section('content')
    <div class="container mx-auto px-6 w-full">
        <h1 class="text-3xl font-bold mb-4">Daftar Barang & Stok</h1>
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-6">

            <a href="{{ route('ppic.barang.export') }}"
                class="inline-flex justify-center items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg shadow-sm transition duration-150 ease-in-out w-full md:w-auto">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                    </path>
                </svg>
                Export Excel
            </a>

            <form action="{{ route('ppic.daftarbarang') }}" method="GET" class="flex flex-row gap-2 w-full md:max-w-md">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama barang..."
                    class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none min-w-0">
                <button type="submit"
                    class="px-4 md:px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium whitespace-nowrap">
                    Search
                </button>
            </form>
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

        <div class="overflow-x-auto">
            <div class="grid gap-4 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
                @forelse ($barangs as $barang)
                    <div class="bg-white rounded-xl shadow-lg ring-1 ring-gray-200 overflow-hidden flex flex-col relative">
                        <div class="p-3 flex justify-center">
                            <div class="h-20 w-20 rounded-md overflow-hidden border border-gray-200 shadow-sm flex items-center justify-center transform transition duration-150 ease-out hover:shadow-md hover:border-gray-300 hover:scale-105 cursor-pointer group focus:outline-none focus:ring-2 focus:ring-green-200 js-preview-wrapper"
                                role="button" tabindex="0" aria-label="Preview image wrapper"
                                data-src="@if($barang->gambar_barang){{ asset('storage/' . $barang->gambar_barang) }}@endif">
                                @if($barang->gambar_barang)
                                    <img src="{{ asset('storage/' . $barang->gambar_barang) }}"
                                        data-src="{{ asset('storage/' . $barang->gambar_barang) }}" alt="Gambar Barang"
                                        class="h-full w-full object-contain cursor-pointer js-preview-image transition-transform duration-150 ease-out group-hover:scale-105"
                                        role="img">
                                @else
                                    <div class="h-full w-full bg-gray-100 flex items-center justify-center text-gray-400">No Image
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="px-4 text-center flex-1">
                            <h3 class="text-sm font-semibold text-gray-900 mb-1 truncate">{{ $barang->nama_barang }}</h3>
                            <div class="text-xs text-gray-600 mb-2">Jenis: <span
                                    class="font-medium">{{ $barang->jenis_barang }}</span></div>
                            <div class="text-xs text-gray-600 mb-2">Dimensi: <span class="font-medium">{{ $barang->panjang }} x
                                    {{ $barang->lebar }} x {{ $barang->tinggi }}</span></div>
                            <div class="text-xs text-gray-600 mb-2">Stok: <span
                                    class="font-medium">{{ $barang->stok_gudang }}</span></div>
                        </div>

                        <div class="px-4 pb-4 flex flex-col items-center gap-2">
                            <div class="w-full flex justify-center gap-2">
                                <a href="{{ route('ppic.barang.edit', $barang->barang_id) }}"
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium shadow-sm bg-yellow-400 hover:bg-yellow-500 text-white">Edit</a>
                                <button
                                    onclick="openPackingModal({{ $barang->barang_id }}, '{{ $barang->nama_barang }}', {{ $barang->stok_gudang }})"
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium shadow-sm bg-green-600 text-white">Masuk
                                    ke order</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-2 sm:col-span-3 md:col-span-4 text-center text-gray-500 py-6">Belum ada data barang
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Modal Packing --}}
    <div id="packingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full p-6 max-h-[88vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Masukkan ke Order (Packing)</h3>
            </div>

            <div class="mb-4 bg-blue-50 p-3 rounded-lg">
                <p class="text-sm font-medium text-gray-700">Barang: <span id="packing_nama_barang"
                        class="text-blue-600"></span></p>
                <p class="text-sm font-medium text-gray-700">Stok Tersedia: <span id="packing_stok_gudang"
                        class="text-blue-600"></span></p>
            </div>

            <form id="packingForm" method="POST" action="{{ route('ppic.packing.store') }}">
                @csrf
                <input type="hidden" name="barang_id" id="packing_barang_id">

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Pemesanan <span
                            class="text-red-500">*</span></label>
                    <select name="pemesanan_barang_id" id="pemesanan_barang_id" required onchange="updateMaxPacking()"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        <option value="">-- Pilih Pemesanan --</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Hanya pemesanan yang memiliki barang ini yang ditampilkan</p>
                </div>

                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Packing <span
                            class="text-red-500">*</span></label>
                    <input type="number" name="jumlah_packing" id="jumlah_packing" min="1" required
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    <p class="text-xs text-gray-500 mt-1">
                        Maksimal: <span id="max_packing_info" class="font-semibold">-</span>
                    </p>
                </div>

                {{-- Tabel Bahan Pendukung --}}
                <div class="mb-4 hidden" id="bahanPendukungSection">
                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Kebutuhan Bahan Pendukung</h4>
                    <div class="border border-gray-300 rounded-lg overflow-x-auto">
                        <table class="w-full text-xs">
                            <thead class="bg-gray-100">
                                <tr>
                                    <th class="px-3 py-2 text-left whitespace-nowrap">Nama Bahan</th>
                                    <th class="px-3 py-2 text-center whitespace-nowrap">Kebutuhan/pcs</th>
                                    <th class="px-3 py-2 text-center whitespace-nowrap">Total Butuh</th>
                                    <th class="px-3 py-2 text-center whitespace-nowrap">Stok</th>
                                    <th class="px-3 py-2 text-center whitespace-nowrap">Status</th>
                                </tr>
                            </thead>
                            <tbody id="bahanPendukungTableBody" class="divide-y divide-gray-200">
                                {{-- Data akan diisi via JavaScript --}}
                            </tbody>
                        </table>
                    </div>
                    <p class="text-xs text-gray-500 mt-1 italic">💡 Tip: Geser tabel ke kanan jika ada kolom tersembunyi</p>
                </div>

                <div class="mb-4 p-3 rounded-lg hidden" id="packingValidasiInfo">
                    <p id="packingValidasiMessage" class="text-sm"></p>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closePackingModal()"
                        class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition">
                        Batal
                    </button>
                    <button type="submit" id="packingSubmitBtn"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentStokGudang = 0;
        let pemesananData = {};
        let bahanPendukungData = [];

        async function openPackingModal(barangId, namaBarang, stokGudang) {
            currentStokGudang = stokGudang;

            document.getElementById('packingModal').classList.remove('hidden');
            document.getElementById('packing_barang_id').value = barangId;
            document.getElementById('packing_nama_barang').textContent = namaBarang;
            document.getElementById('packing_stok_gudang').textContent = stokGudang;

            // Reset form
            document.getElementById('pemesanan_barang_id').innerHTML = '<option value="">-- Pilih Pemesanan --</option>';
            document.getElementById('jumlah_packing').value = '';
            document.getElementById('max_packing_info').textContent = '-';
            document.getElementById('packingValidasiInfo').classList.add('hidden');
            document.getElementById('bahanPendukungSection').classList.add('hidden');

            // Fetch bahan pendukung untuk barang ini
            await fetchBahanPendukung(barangId);

            // Fetch pemesanan yang memiliki barang ini
            try {
                const response = await fetch(`/ppic/get-pemesanan-by-barang/${barangId}`);
                const data = await response.json();

                const selectPemesanan = document.getElementById('pemesanan_barang_id');
                pemesananData = {};

                if (data.success && data.pemesanan && data.pemesanan.length > 0) {
                    data.pemesanan.forEach(item => {
                        const sudahPacking = item.packing_sum_jumlah_packing || 0;
                        const sisaPemesanan = item.jumlah_pemesanan - sudahPacking;

                        if (sisaPemesanan > 0) {
                            const option = document.createElement('option');
                            option.value = item.pemesanan_barang_id;

                            const namaPembeli = item.pemesanan?.pembeli?.nama_pembeli || 'Pembeli Tidak Ditemukan';
                            const noSPK = item.pemesanan?.no_SPK_kwas || 'SPK Tidak Ditemukan';

                            option.textContent = `${noSPK} - ${namaPembeli} (Pesan: ${item.jumlah_pemesanan}, Sudah: ${sudahPacking}, Sisa: ${sisaPemesanan})`;

                            selectPemesanan.appendChild(option);

                            pemesananData[item.pemesanan_barang_id] = {
                                jumlah_pemesanan: item.jumlah_pemesanan,
                                sudah_packing: sudahPacking,
                                sisa_pemesanan: sisaPemesanan
                            };
                        }
                    });

                    if (selectPemesanan.options.length === 1) {
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'Semua pemesanan sudah selesai dipacking';
                        option.disabled = true;
                        selectPemesanan.appendChild(option);
                    }
                } else {
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'Tidak ada pemesanan untuk barang ini';
                    option.disabled = true;
                    selectPemesanan.appendChild(option);
                }
            } catch (error) {
                console.error('Error fetching pemesanan:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: 'Gagal memuat data pemesanan'
                });
            }
        }

        async function fetchBahanPendukung(barangId) {
            try {
                const response = await fetch(`/ppic/get-bahan-packing/${barangId}`);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const text = await response.text();
                console.log('Response text:', text);

                bahanPendukungData = JSON.parse(text);
                console.log('Bahan Pendukung Data:', bahanPendukungData);

                if (bahanPendukungData && bahanPendukungData.length > 0) {
                    document.getElementById('bahanPendukungSection').classList.remove('hidden');
                    const tableBody = document.getElementById('bahanPendukungTableBody');
                    tableBody.innerHTML = '<tr><td colspan="5" class="px-3 py-2 text-center text-gray-500">Masukkan jumlah packing untuk melihat kebutuhan bahan</td></tr>';
                } else {
                    document.getElementById('bahanPendukungSection').classList.add('hidden');
                }
            } catch (error) {
                console.error('Error fetching bahan pendukung:', error);
                bahanPendukungData = [];
                document.getElementById('bahanPendukungSection').classList.add('hidden');
            }
        }

        function handleJumlahPackingInput() {
            console.log('Input triggered');
            validatePacking();
            hitungKebutuhanBahan();
        }

        function hitungKebutuhanBahan() {
            const jumlahPacking = parseInt(document.getElementById('jumlah_packing').value) || 0;
            const tableBody = document.getElementById('bahanPendukungTableBody');
            const section = document.getElementById('bahanPendukungSection');

            console.log('Hitung Kebutuhan - Jumlah Packing:', jumlahPacking);
            console.log('Bahan Data:', bahanPendukungData);

            if (!bahanPendukungData || bahanPendukungData.length === 0) {
                section.classList.add('hidden');
                return;
            }

            section.classList.remove('hidden');

            if (jumlahPacking === 0) {
                tableBody.innerHTML = '<tr><td colspan="5" class="px-3 py-2 text-center text-gray-500">Masukkan jumlah packing untuk melihat kebutuhan bahan</td></tr>';
                return;
            }

            tableBody.innerHTML = '';
            let adaBahanKurang = false;

            bahanPendukungData.forEach(bahan => {
                const totalButuh = bahan.kebutuhan * jumlahPacking;
                const cukup = bahan.stok >= totalButuh;

                if (!cukup) {
                    adaBahanKurang = true;
                }

                const row = document.createElement('tr');
                row.className = cukup ? 'bg-white' : 'bg-red-50';

                row.innerHTML = `
                    <td class="px-3 py-2 whitespace-nowrap">${bahan.nama_bahan}</td>
                    <td class="px-3 py-2 text-center whitespace-nowrap">${bahan.kebutuhan} ${bahan.satuan}</td>
                    <td class="px-3 py-2 text-center font-semibold whitespace-nowrap">${totalButuh} ${bahan.satuan}</td>
                    <td class="px-3 py-2 text-center whitespace-nowrap">${bahan.stok} ${bahan.satuan}</td>
                    <td class="px-3 py-2 text-center whitespace-nowrap">
                        ${cukup
                        ? '<span class="text-green-600 font-semibold">✓ Cukup</span>'
                        : '<span class="text-red-600 font-semibold">✗ Kurang ' + (totalButuh - bahan.stok) + ' ' + bahan.satuan + '</span>'}
                    </td>
                `;

                tableBody.appendChild(row);
            });

            console.log('Tabel updated, Ada bahan kurang:', adaBahanKurang);
        }

        function closePackingModal() {
            document.getElementById('packingModal').classList.add('hidden');
        }

        function updateMaxPacking() {
            const selectPemesanan = document.getElementById('pemesanan_barang_id');
            const selectedOption = selectPemesanan.options[selectPemesanan.selectedIndex];

            if (selectedOption && selectedOption.value) {
                const pemesananId = selectedOption.value;
                const data = pemesananData[pemesananId];

                if (data) {
                    const sisaPemesanan = data.sisa_pemesanan;
                    const maxPacking = Math.min(sisaPemesanan, currentStokGudang);

                    document.getElementById('max_packing_info').textContent =
                        `${maxPacking} (Sisa Pesan: ${sisaPemesanan}, Stok: ${currentStokGudang})`;

                    document.getElementById('jumlah_packing').max = maxPacking;
                }
            } else {
                document.getElementById('max_packing_info').textContent = '-';
            }

            validatePacking();
        }

        function validatePacking() {
            const selectPemesanan = document.getElementById('pemesanan_barang_id');
            const jumlahPacking = parseInt(document.getElementById('jumlah_packing').value) || 0;
            const validasiInfo = document.getElementById('packingValidasiInfo');
            const validasiMessage = document.getElementById('packingValidasiMessage');
            const submitBtn = document.getElementById('packingSubmitBtn');

            if (!selectPemesanan.value || jumlahPacking === 0) {
                validasiInfo.classList.add('hidden');
                submitBtn.disabled = false;
                submitBtn.className = 'flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition';
                return;
            }

            const pemesananId = selectPemesanan.value;
            const data = pemesananData[pemesananId];

            if (data) {
                const sisaPemesanan = data.sisa_pemesanan;

                if (jumlahPacking > sisaPemesanan) {
                    validasiInfo.className = 'mb-4 p-3 rounded-lg bg-red-100 border border-red-300';
                    validasiMessage.textContent = `✗ Jumlah melebihi sisa pemesanan! Sisa: ${sisaPemesanan} pcs`;
                    validasiMessage.className = 'text-sm text-red-700';
                    validasiInfo.classList.remove('hidden');
                    submitBtn.disabled = true;
                    submitBtn.className = 'flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg font-medium cursor-not-allowed';
                    return;
                }

                if (jumlahPacking > currentStokGudang) {
                    validasiInfo.className = 'mb-4 p-3 rounded-lg bg-red-100 border border-red-300';
                    validasiMessage.textContent = `✗ Stok tidak mencukupi! Stok tersedia: ${currentStokGudang}`;
                    validasiMessage.className = 'text-sm text-red-700';
                    validasiInfo.classList.remove('hidden');
                    submitBtn.disabled = true;
                    submitBtn.className = 'flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg font-medium cursor-not-allowed';
                    return;
                }

                const adaBahanKurang = bahanPendukungData.some(bahan => {
                    const totalButuh = bahan.kebutuhan * jumlahPacking;
                    return bahan.stok < totalButuh;
                });

                const sisaSetelahPacking = sisaPemesanan - jumlahPacking;

                if (adaBahanKurang) {
                    validasiInfo.className = 'mb-4 p-3 rounded-lg bg-yellow-100 border border-yellow-300';
                    validasiMessage.innerHTML = `
                        <div class="space-y-1">
                            <p class="font-semibold">⚠ Valid, tapi ada bahan pendukung yang kurang!</p>
                            <p class="text-xs">✓ ${jumlahPacking} pcs akan dipacking</p>
                            <p class="text-xs">Sisa pemesanan: ${sisaSetelahPacking} pcs | Sisa stok: ${currentStokGudang - jumlahPacking} pcs</p>
                        </div>
                    `;
                    validasiMessage.className = 'text-sm text-yellow-700';
                } else {
                    validasiInfo.className = 'mb-4 p-3 rounded-lg bg-green-100 border border-green-300';
                    validasiMessage.innerHTML = `
                        <div class="space-y-1">
                            <p>✓ Valid! ${jumlahPacking} pcs akan dipacking</p>
                            <p class="text-xs">Sisa pemesanan: ${sisaSetelahPacking} pcs | Sisa stok: ${currentStokGudang - jumlahPacking} pcs</p>
                        </div>
                    `;
                    validasiMessage.className = 'text-sm text-green-700';
                }

                validasiInfo.classList.remove('hidden');
                submitBtn.disabled = false;
                submitBtn.className = 'flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition';
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            const jumlahPackingInput = document.getElementById('jumlah_packing');

            jumlahPackingInput.addEventListener('input', handleJumlahPackingInput);
            jumlahPackingInput.addEventListener('keyup', handleJumlahPackingInput);
            jumlahPackingInput.addEventListener('change', handleJumlahPackingInput);

            const modal = document.getElementById('packingModal');
            modal.addEventListener('click', function (e) {
                if (e.target === modal) {
                    closePackingModal();
                }
            });
        });
    </script>
@endsection