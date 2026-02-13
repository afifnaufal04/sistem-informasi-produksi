@extends('layouts.allApp')

@section('title', 'Order Bahan Pendukung')
@section('role', 'PPIC')

@section('content')
    <div class="max-w-5xl mx-auto p-6 bg-white shadow-lg rounded-xl">
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-center text-gray-800">Form Order Bahan Pendukung</h2>
            <p class="text-center text-sm text-gray-500 mt-1">Isi detail bahan, lalu simpan order.</p>
        </div>

        <form id="orderForm" action="{{ route('ppic.daftarorderbahanpendukung.store') }}" method="POST">
            @csrf
            <!-- Tanggal -->
            <div class="mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembelian</label>
                <input type="date" name="tanggal_pembelian" class="w-full p-2 border rounded-lg" required>
            </div>

            <div class="flex items-center justify-between mb-2">
                <h3 class="text-base font-semibold text-gray-800">Rincian Bahan</h3>
                <span class="text-xs text-gray-500">Tambah lebih dari satu item bila perlu</span>
            </div>
            <div id="bahanContainer" class="space-y-4">
                <div class="bahan-item p-4 border border-gray-200 rounded-lg bg-gray-50">

                    <div class="grid md:grid-cols-4 grid-cols-1 gap-4 items-center">
                        <!-- Pilih Bahan -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bahan Pendukung</label>
                            <select name="bahan_pendukung_id[]"
                                class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
                                <option value="">-- Pilih Bahan --</option>
                                @foreach($bahanPendukung as $bahan)
                                    <option value="{{ $bahan->bahan_pendukung_id }}"
                                        data-harga="{{ $bahan->harga_bahan_pendukung }}">
                                        {{ $bahan->nama_bahan_pendukung }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Harga -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                            <input type="number" name="harga_bahan_pendukung[]" class="w-full p-2 border rounded-lg harga"
                                readonly>
                        </div>

                        <!-- Jumlah -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                            <input type="number" name="jumlah_pembelian[]" class="w-full p-2 border rounded-lg jumlah"
                                min="1" value="1">
                        </div>

                        <!-- Subtotal -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Subtotal (Rp)</label>
                            <input type="number" name="subtotal[]" class="w-full p-2 border rounded-lg subtotal" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Tambah -->
            <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                <button type="button" id="tambahBahan"
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition w-full sm:w-auto">
                    Tambah Barang
                </button>

                <div class="flex items-center justify-between sm:justify-end gap-3">
                    <span class="font-semibold text-gray-700 whitespace-nowrap">Total (Rp):</span>
                    <input type="number" id="total" name="total" class="p-2 border rounded-lg w-full sm:w-56 text-right font-bold" readonly>
                </div>
            </div>

            <div class="mt-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
                <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
                <textarea name="catatan" rows="3" class="w-full p-2 border rounded-lg"
                    placeholder="Tulis catatan order...">{{ old('catatan') }}</textarea>
            </div>

            <!-- Tombol Submit -->
            <div class="mt-6 text-center">
                <button type="submit"
                    class="w-full bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                    Simpan Order
                </button>
            </div>
        </form>
    </div>

    <script>
        const bahanContainer = document.getElementById('bahanContainer');
        const tambahBtn = document.getElementById('tambahBahan');

        // Fungsi update subtotal dan total
        function updateSubtotal(item) {
            const harga = item.querySelector('.harga').value || 0;
            const jumlah = item.querySelector('.jumlah').value || 0;
            const subtotal = item.querySelector('.subtotal');
            subtotal.value = harga * jumlah;
            updateTotal();
        }

        function updateTotal() {
            let total = 0;
            document.querySelectorAll('.subtotal').forEach(sub => {
                total += parseInt(sub.value || 0);
            });
            document.getElementById('total').value = total;
        }

        // Event untuk update harga dan subtotal
        bahanContainer.addEventListener('change', function (e) {
            if (e.target.tagName === 'SELECT') {
                const selected = e.target.selectedOptions[0];
                const harga = selected.getAttribute('data-harga') || 0;
                const item = e.target.closest('.bahan-item');
                item.querySelector('.harga').value = harga;
                updateSubtotal(item);
            } else if (e.target.classList.contains('jumlah')) {
                updateSubtotal(e.target.closest('.bahan-item'));
            }
        });

        // Tambah baris bahan baru
        tambahBtn.addEventListener('click', function () {
            const firstItem = bahanContainer.querySelector('.bahan-item');
            const newItem = firstItem.cloneNode(true);
            newItem.querySelectorAll('input').forEach(input => input.value = '');
            newItem.querySelector('select').selectedIndex = 0;
            bahanContainer.appendChild(newItem);
        });
    </script>
@endsection