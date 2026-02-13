@extends('layouts.allApp')

@section('title', 'Edit Order Bahan Pendukung')
@section('role', 'PPIC')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white shadow-lg rounded-xl">
    <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Edit Order Bahan Pendukung</h2>

    <form id="orderForm" action="{{ route('ppic.daftarorderbahanpendukung.update', $order->pembelian_bahan_pendukung_id) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Tanggal Pembelian -->
        <div class="mb-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Pembelian</label>
            <input type="date" name="tanggal_pembelian" value="{{ $order->tanggal_pembelian }}" class="w-full p-2 border rounded-lg" required>
        </div>

        <div id="bahanContainer" class="space-y-4">
            @foreach($order->detailpembelianbahanpendukung as $detail)
            <div class="bahan-item p-4 border border-gray-200 rounded-lg bg-gray-50">
                <div class="grid md:grid-cols-4 grid-cols-1 gap-4 items-center">
                    <!-- Pilih Bahan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Bahan Pendukung</label>
                        <select name="bahan_pendukung_id[]" class="w-full p-2 border rounded-lg focus:ring focus:ring-blue-300">
                            <option value="">-- Pilih Bahan --</option>
                            @foreach($bahanPendukung as $bahan)
                                <option value="{{ $bahan->bahan_pendukung_id }}"
                                    data-harga="{{ $bahan->harga_bahan_pendukung }}"
                                    {{ $detail->bahan_pendukung_id == $bahan->bahan_pendukung_id ? 'selected' : '' }}>
                                    {{ $bahan->nama_bahan_pendukung }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Harga -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp)</label>
                        <input type="number" name="harga_bahan_pendukung[]" class="w-full p-2 border rounded-lg harga"
                               value="{{ $detail->harga_bahan_pendukung }}" readonly>
                    </div>

                    <!-- Jumlah -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah</label>
                        <input type="number" name="jumlah_pembelian[]" class="w-full p-2 border rounded-lg jumlah"
                               min="1" value="{{ $detail->jumlah_pembelian }}">
                    </div>

                    <!-- Subtotal -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Subtotal (Rp)</label>
                        <input type="number" name="subtotal[]" class="w-full p-2 border rounded-lg subtotal"
                               value="{{ $detail->subtotal }}" readonly>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4 p-4 border border-gray-200 rounded-lg bg-gray-50">
            <label class="block text-sm font-medium text-gray-700 mb-1">Catatan (Opsional)</label>
            <textarea name="catatan" rows="3" class="w-full p-2 border rounded-lg" placeholder="Tulis catatan order...">{{ old('catatan', $order->catatan) }}</textarea>
        </div>

        <!-- Tombol Tambah -->
        <div class="mt-4 flex justify-between items-center">
            <button type="button" id="tambahBahan" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Tambah Barang
            </button>

            <div class="flex items-center space-x-3">
                <span class="font-semibold text-gray-700">Total (Rp):</span>
                <input type="number" id="total" name="total" value="{{ $order->total_harga }}" class="p-2 border rounded-lg w-40 text-right font-bold" readonly>
            </div>
        </div>

        <!-- Tombol Submit -->
        <div class="mt-6 text-center">
            <button type="submit" class="w-full bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition">
                Update Order
            </button>
        </div>
    </form>
</div>

<script>
    const bahanContainer = document.getElementById('bahanContainer');
    const tambahBtn = document.getElementById('tambahBahan');

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

    // Update harga & subtotal saat bahan berubah
    bahanContainer.addEventListener('change', function(e) {
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
    tambahBtn.addEventListener('click', function() {
        const firstItem = bahanContainer.querySelector('.bahan-item');
        const newItem = firstItem.cloneNode(true);
        newItem.querySelectorAll('input').forEach(input => input.value = '');
        newItem.querySelector('select').selectedIndex = 0;
        bahanContainer.appendChild(newItem);
    });

    // Hitung total awal saat halaman dimuat
    document.addEventListener('DOMContentLoaded', updateTotal);
</script>
@endsection
