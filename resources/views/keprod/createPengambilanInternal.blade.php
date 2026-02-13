@extends('layouts.allApp')

@section('title', 'Tambah Pengambilan Internal')
@section('role', 'Kepala Produksi')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-md mt-8">

        <h2 class="text-2xl font-bold mb-6 text-gray-700">Tambah Pengambilan Internal</h2>
        <p class="text-sm text-gray-500 mb-6">Pengambilan dari proses produksi internal (tanpa supplier eksternal)</p>

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

        <form action="{{ route('keprod.pengambilanInternal.store') }}" method="POST" class="space-y-5">
            @csrf

            <div id="pengambilanWrapper" class="space-y-5">
                <div class="pengambilan-item border rounded-lg p-4 bg-gray-50 space-y-4 relative">

                    <button type="button"
                        class="removePengambilan absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-7 h-7 flex items-center justify-center shadow hover:bg-red-700">✕</button>

                    <div>
                        <label class="font-medium text-gray-700">Barang yang Akan Diambil (Internal)</label>
                        <select name="detail_pengiriman_id[]" required
                            class="w-full mt-1 px-3 py-2 border rounded-lg barangSelect">
                            <option value="">-- Pilih Barang --</option>
                            @foreach($detailPengiriman as $item)
                                <option value="{{ $item['detail_pengiriman_id'] }}" data-max="{{ $item['dapat_diambil'] }}">
                                    {{ $item['nama_barang'] }} - {{ $item['sub_proses'] }} | Tersedia:
                                    {{ $item['dapat_diambil'] }} pcs
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1 noPengirimanInfo">No. Pengiriman: -</p>
                    </div>

                    <div>
                        <label class="font-medium text-gray-700">Jumlah yang Akan Diambil</label>
                        <input type="number" min="1" name="jumlah_diambil[]" required
                            class="w-full mt-1 px-3 py-2 border rounded-lg jumlahInput">
                        <p class="text-xs text-gray-500 mt-1 maxInfo">Maksimal: -</p>
                    </div>

                </div>
            </div>

            <button type="button" id="addPengambilan"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg mt-3">
                + Tambah Item
            </button>

            <div class="border-t pt-5 mt-5">
                <h3 class="font-semibold text-gray-700 mb-4">Detail Pengambilan</h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="font-medium text-gray-700">Tanggal Pengambilan</label>
                        <input type="date" name="tanggal_pengambilan" required
                            class="w-full mt-1 px-3 py-2 border rounded-lg" value="{{ date('Y-m-d') }}">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 mt-6">
                <a href="{{ route('keprod.pengambilan.index') }}"
                    class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium">
                    Batal
                </a>
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">
                    Simpan
                </button>
            </div>
        </form>
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
                    const noPengiriman = selected.getAttribute('data-no-pengiriman') || '-';

                    const jumlahInput = item.querySelector('.jumlahInput');
                    const maxInfo = item.querySelector('.maxInfo');
                    const noPengirimanInfo = item.querySelector('.noPengirimanInfo');

                    jumlahInput.max = max;
                    jumlahInput.value = '';
                    maxInfo.textContent = `Maksimal: ${max} pcs`;
                    noPengirimanInfo.textContent = `No. Pengiriman: ${noPengiriman}`;
                };
            });
        }

        initEvents();

        document.getElementById('addPengambilan').onclick = function () {
            const wrapper = document.getElementById('pengambilanWrapper');
            const clone = wrapper.children[0].cloneNode(true);

            clone.querySelectorAll('input, select').forEach(el => el.value = "");
            clone.querySelector('.maxInfo').textContent = 'Maksimal: -';
            clone.querySelector('.noPengirimanInfo').textContent = 'No. Pengiriman: -';

            wrapper.appendChild(clone);
            initEvents();
        };
    </script>

@endsection