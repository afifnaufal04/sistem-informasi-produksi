@extends('layouts.allApp')

@section('title', 'Tambah Pengambilan')
@section('role', 'Kepala Produksi')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-md mt-8">

        <h2 class="text-2xl font-bold mb-6 text-gray-700">Tambah Pengambilan</h2>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong class="font-bold">Ada kesalahan:</strong>
                <ul class="mt-2 ml-4 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{!! $error !!}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('keprod.pengambilan.store') }}" method="POST" class="space-y-5">
            @csrf

            <div id="pengambilanWrapper" class="space-y-5">
                <div class="pengambilan-item border rounded-lg p-4 bg-gray-50 space-y-4 relative">

                    <button type="button"
                        class="removePengambilan absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-7 h-7 flex items-center justify-center shadow">✕</button>

                    <div>
                        <label class="font-medium text-gray-700">Barang yang Akan Diambil</label>
                        <select name="detail_pengiriman_id[]" required
                            class="w-full mt-1 px-3 py-2 border rounded-lg barangSelect">
                            <option value="">-- Pilih Barang --</option>
                            @foreach($detailPengiriman as $item)
                                <option value="{{ $item['detail_pengiriman_id'] }}" data-supplier="{{ $item['supplier'] }}"
                                    data-max="{{ $item['dapat_diambil'] }}">
                                    {{ $item['nama_barang'] }} - {{ $item['sub_proses'] }} | Supplier: {{ $item['supplier'] }} |
                                    Tersedia: {{ $item['dapat_diambil'] }} pcs
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1 supplierInfo">Supplier: -</p>
                    </div>

                    <div>
                        <label class="font-medium text-gray-700">Jumlah yang Akan Diambil</label>
                        <input type="number" min="1" name="jumlah_diambil[]" required
                            class="w-full mt-1 px-3 py-2 border rounded-lg jumlahInput">
                        <p class="text-xs text-gray-500 mt-1 maxInfo">Maksimal: -</p>
                    </div>

                    <div>
                        <label class="font-medium text-gray-700">Harga Produksi (Pcs)</label>
                        <input type="number" min="0" step="0.01" name="harga_produksi[]" required
                            class="w-full mt-1 px-3 py-2 border rounded-lg" placeholder="Masukkan harga produksi per pcs">
                    </div>

                </div>
            </div>

            <button type="button" id="addPengambilan"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg mt-3">+ Tambah Item</button>

            <div>
                <label class="font-medium text-gray-700">QC</label>
                <select name="qc_id" required class="w-full mt-1 px-3 py-2 border rounded-lg">
                    <option value="">-- Pilih QC --</option>
                    @foreach($qc as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="font-medium text-gray-700">Supir</label>
                <select name="supir_id" required class="w-full mt-1 px-3 py-2 border rounded-lg">
                    <option value="">-- Pilih Supir --</option>
                    @foreach($supir as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="font-medium text-gray-700">Kendaraan</label>
                <select name="kendaraan_id" required class="w-full mt-1 px-3 py-2 border rounded-lg">
                    <option value="">-- Pilih Kendaraan --</option>
                    @foreach($kendaraan as $k)
                        <option value="{{ $k->kendaraan_id }}">{{ $k->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="font-medium text-gray-700">Tanggal Pengambilan</label>
                <input type="date" name="tanggal_pengambilan" required class="w-full mt-1 px-3 py-2 border rounded-lg">
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">Simpan</button>
            </div>

            <script>
                function initEvents() {
                    document.querySelectorAll('.removePengambilan').forEach(btn => {
                        btn.onclick = function () {
                            if (document.querySelectorAll('.pengambilan-item').length > 1) {
                                this.closest('.pengambilan-item').remove();
                            } else {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Tidak bisa',
                                    text: 'Minimal harus ada 1 item!'
                                });
                            }
                        };
                    });

                    document.querySelectorAll('.barangSelect').forEach(sel => {
                        sel.onchange = function () {
                            const item = this.closest('.pengambilan-item');
                            const selected = this.options[this.selectedIndex];
                            const max = selected.getAttribute('data-max') || 0;
                            const supplier = selected.getAttribute('data-supplier') || '-';

                            const jumlahInput = item.querySelector('.jumlahInput');
                            const maxInfo = item.querySelector('.maxInfo');
                            const supplierInfo = item.querySelector('.supplierInfo');

                            jumlahInput.max = max;
                            jumlahInput.value = '';
                            maxInfo.textContent = `Maksimal: ${max} pcs`;
                            supplierInfo.textContent = `Supplier: ${supplier}`;
                        };
                    });
                }

                initEvents();

                document.getElementById('addPengambilan').onclick = function () {
                    const wrapper = document.getElementById('pengambilanWrapper');
                    const clone = wrapper.children[0].cloneNode(true);

                    clone.querySelectorAll('input, select').forEach(el => el.value = "");
                    clone.querySelector('.maxInfo').textContent = 'Maksimal: -';
                    clone.querySelector('.supplierInfo').textContent = 'Supplier: -';

                    wrapper.appendChild(clone);
                    initEvents();
                };
            </script>

        </form>
    </div>
@endsection