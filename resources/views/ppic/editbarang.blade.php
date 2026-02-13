@extends('layouts.allApp')

@section('title', 'Edit Barang')
@section('role', 'PPIC')

@section('content')
<div class="container mx-auto px-6 w-full py-6">
    <h1 class="text-3xl text-center font-bold">Edit Barang</h1>
    <p class="text-sm text-center text-gray-500 mb-6">Perbarui detail barang dan bahan pendukung</p>

    <form action="{{ route('ppic.barang.update', $barang->barang_id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 rounded-2xl shadow-lg space-y-6 max-w-5xl mx-auto">
        @csrf
        @method('PUT')

        {{-- Section 1: Info Barang --}}
        <div class="border-b pb-6">
            <h2 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
                Informasi Barang
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Kolom Kiri --}}
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Barang <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" 
                            class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-green-200 focus:border-green-400" 
                            placeholder="Contoh: Talenan Kayu Jati" required>
                        @error('nama_barang')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Barang <span class="text-red-500">*</span></label>
                        <select name="jenis_barang" class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-green-200 focus:border-green-400" required>
                            <option value="" disabled>Pilih Jenis Barang</option>
                            <option value="diy" {{ old('jenis_barang', $barang->jenis_barang) == 'diy' ? 'selected' : '' }}>DIY</option>
                            <option value="superindo" {{ old('jenis_barang', $barang->jenis_barang) == 'superindo' ? 'selected' : '' }}>Superindo</option>
                            <option value="pendopo" {{ old('jenis_barang', $barang->jenis_barang) == 'pendopo' ? 'selected' : '' }}>Pendopo</option>
                            <option value="ooa" {{ old('jenis_barang', $barang->jenis_barang) == 'ooa' ? 'selected' : '' }}>OOA</option>
                        </select>
                        @error('jenis_barang')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Harga (Rp) <span class="text-red-500">*</span></label>
                            <input type="number" name="harga_barang" value="{{ old('harga_barang', $barang->harga_barang) }}" 
                                class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-green-200 focus:border-green-400" 
                                placeholder="50000" min="1" required>
                            @error('harga_barang')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Stok Gudang</label>
                            <input type="number" name="stok_gudang" value="{{ old('stok_gudang', $barang->stok_gudang) }}" 
                                class="w-full border border-gray-300 rounded-lg p-3 focus:ring-2 focus:ring-green-200 focus:border-green-400" 
                                placeholder="0" min="1" required>
                            @error('stok_gudang')
                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Dimensi (cm) <span class="text-red-500">*</span></label>
                        <div class="grid grid-cols-3 gap-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Panjang</label>
                                <input type="number" step="0.01" name="panjang" value="{{ old('panjang', $barang->panjang) }}" 
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-200 focus:border-green-400" 
                                    placeholder="Panjang" required min="0.1">
                                @error('panjang')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Lebar</label>
                                <input type="number" step="0.01" name="lebar" value="{{ old('lebar', $barang->lebar) }}" 
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-200 focus:border-green-400" 
                                    placeholder="Lebar" required min="0.1">
                                @error('lebar')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tinggi</label>
                                <input type="number" step="0.01" name="tinggi" value="{{ old('tinggi', $barang->tinggi) }}" 
                                    class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-green-200 focus:border-green-400" 
                                    placeholder="Tinggi" required min="0.1">
                                @error('tinggi')
                                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan - Upload Gambar --}}
                <div class="space-y-4">
                    <label class="block text-sm font-medium text-gray-700">Upload Gambar</label>
                    <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 flex flex-col items-center justify-center text-center hover:border-green-400 transition" id="imageUploadContainer">
                        <div class="w-48 h-48 rounded-md overflow-hidden bg-gray-50 flex items-center justify-center mb-3 border border-gray-200">
                            @php
                                $currentImage = $barang->gambar_barang 
                                    ? asset('storage/' . $barang->gambar_barang) 
                                    : asset('images/placeholder-image.png');
                            @endphp
                            <img id="previewImage" src="{{ $currentImage }}" alt="Preview" class="w-full h-full object-contain" />
                        </div>
                        <p class="text-sm text-gray-600 mb-3">
                            Tarik dan lepaskan gambar atau 
                            <span class="text-green-600 font-semibold">pilih file</span>
                        </p>
                        <input type="file" name="gambar_barang" id="gambarInput" accept="image/*" class="hidden">
                        <div class="flex gap-2">
                            <label for="gambarInput" class="bg-white border border-gray-300 rounded-lg px-4 py-2 text-sm cursor-pointer hover:bg-gray-50 transition font-medium">
                                Pilih File
                            </label>
                            <button type="button" id="removeImageBtn" class="bg-red-50 text-red-600 border border-red-200 px-4 py-2 rounded-lg hover:bg-red-100 transition text-sm font-medium">
                                Hapus
                            </button>
                        </div>
                        <p class="text-xs text-gray-400 mt-3">Format: JPG, PNG, GIF, WEBP | Max: 2MB</p>
                        @if($barang->gambar_barang)
                            <p class="text-xs text-green-600 mt-2">Gambar saat ini: {{ basename($barang->gambar_barang) }}</p>
                        @endif
                        @error('gambar_barang')
                            <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: Bahan Pendukung --}}
        <div class="border-b pb-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-3">
                <div>
                    <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        Bahan Pendukung per Sub Proses (Opsional)
                    </h2>
                </div>
                <button type="button" id="addBahanBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium flex items-center gap-2 transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Tambah Bahan
                </button>
            </div>

            <div id="bahanContainer" class="space-y-4">
                @forelse($barang->bahanPendukungBarang as $index => $bpb)
                    <div class="bahan-item bg-gray-50 border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-700">Bahan Ke-{{ $index + 1 }}</h3>
                            <button type="button" class="removeBahanBtn bg-red-500 hover:bg-red-600 text-white rounded-lg px-3 py-1.5 flex items-center gap-1.5 text-xs font-medium transition shadow-sm hover:shadow">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus
                            </button>
                        </div>

                        <input type="hidden" name="bahan_pendukung_barang_id[]" value="{{ $bpb->bahan_pendukung_barang_id }}">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Sub Proses</label>
                                <select name="sub_proses_id[]" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                                    <option value="">Pilih Sub Proses</option>
                                    @foreach($subProses as $sp)
                                        <option value="{{ $sp->sub_proses_id }}" {{ $bpb->sub_proses_id == $sp->sub_proses_id ? 'selected' : '' }}>
                                            {{ $sp->nama_sub_proses }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Bahan Pendukung</label>
                                <select name="bahan_pendukung_id[]" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                                    <option value="">Pilih Bahan</option>
                                    @foreach($bahanPendukung as $bp)
                                        <option value="{{ $bp->bahan_pendukung_id }}" {{ $bpb->bahan_pendukung_id == $bp->bahan_pendukung_id ? 'selected' : '' }}>
                                            {{ $bp->nama_bahan_pendukung }} ({{ $bp->satuan }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah/Unit</label>
                                <input type="number" step="0.01" name="jumlah_bahan_pendukung[]" min="0" value="{{ $bpb->jumlah_bahan_pendukung }}"
                                    class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400" 
                                    placeholder="Contoh: 1">
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Template kosong jika belum ada bahan pendukung --}}
                    <div class="bahan-item bg-gray-50 border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                        <div class="flex items-start justify-between mb-3">
                            <h3 class="text-sm font-semibold text-gray-700">Bahan Ke-1</h3>
                            <button type="button" class="removeBahanBtn bg-red-500 hover:bg-red-600 text-white rounded-lg px-3 py-1.5 flex items-center gap-1.5 text-xs font-medium transition shadow-sm hover:shadow">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Hapus
                            </button>
                        </div>

                        <input type="hidden" name="bahan_pendukung_barang_id[]" value="">

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Sub Proses</label>
                                <select name="sub_proses_id[]" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                                    <option value="">Pilih Sub Proses</option>
                                    @foreach($subProses as $sp)
                                        <option value="{{ $sp->sub_proses_id }}">{{ $sp->nama_sub_proses }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Bahan Pendukung</label>
                                <select name="bahan_pendukung_id[]" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                                    <option value="">Pilih Bahan</option>
                                    @foreach($bahanPendukung as $bp)
                                        <option value="{{ $bp->bahan_pendukung_id }}">{{ $bp->nama_bahan_pendukung }} ({{ $bp->satuan }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah/Unit</label>
                                <input type="number" step="0.01" name="jumlah_bahan_pendukung[]" min="0" 
                                    class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400" 
                                    placeholder="Contoh: 1">
                            </div>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="mt-4 bg-blue-50 border border-blue-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-blue-600 mr-2 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-xs text-blue-800">
                        <p class="font-semibold mb-2">Informasi Bahan Pendukung:</p>
                        <ul class="list-disc list-inside space-y-1 ml-2">
                            <li>Bagian ini <strong>bersifat opsional</strong>, dapat dikosongkan jika belum mengetahui kebutuhan bahan</li>
                            <li>PPIC dapat menambahkan atau mengubah bahan pendukung melalui menu <strong>Edit Barang</strong></li>
                            <li>Setiap sub proses bisa memiliki beberapa bahan pendukung berbeda</li>
                            <li><strong>Contoh:</strong> Pada proses 2D membutuhkan Lem Putih 1 gram dan Paku 2 pcs</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        {{-- Validation Errors --}}
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <div class="flex">
                    <svg class="w-5 h-5 text-red-600 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <h3 class="text-sm font-semibold text-red-800 mb-2">Terdapat kesalahan validasi:</h3>
                        <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        {{-- Action Buttons --}}
        <div class="flex justify-end gap-3 pt-4 border-t">
            <a href="{{ route('ppic.daftarbarang') }}" class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 px-6 py-2.5 rounded-lg text-sm text-gray-700 font-medium transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Batal
            </a>
            <button type="submit" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white px-6 py-2.5 rounded-lg text-sm font-medium transition shadow-md hover:shadow-lg">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Update Barang
            </button>
        </div>
        </form>
        </div>

        {{-- Template untuk bahan baru (hidden) --}}
        <template id="bahanTemplate">
            <div class="bahan-item bg-gray-50 border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                <div class="flex items-start justify-between mb-3">
                    <h3 class="text-sm font-semibold text-gray-700">Bahan Ke-1</h3>
                    <button type="button" class="removeBahanBtn bg-red-500 hover:bg-red-600 text-white rounded-lg px-3 py-1.5 flex items-center gap-1.5 text-xs font-medium transition shadow-sm hover:shadow">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Hapus
                    </button>
                </div>

                <input type="hidden" name="bahan_pendukung_barang_id[]" value="">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Sub Proses</label>
                        <select name="sub_proses_id[]" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                            <option value="">Pilih Sub Proses</option>
                            @foreach($subProses as $sp)
                                <option value="{{ $sp->sub_proses_id }}">{{ $sp->nama_sub_proses }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Bahan Pendukung</label>
                        <select name="bahan_pendukung_id[]" class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400">
                            <option value="">Pilih Bahan</option>
                            @foreach($bahanPendukung as $bp)
                                <option value="{{ $bp->bahan_pendukung_id }}">{{ $bp->nama_bahan_pendukung }} ({{ $bp->satuan }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Jumlah/Unit</label>
                        <input type="number" step="0.01" name="jumlah_bahan_pendukung[]" min="0" 
                            class="w-full border border-gray-300 rounded-lg p-2 text-sm focus:ring-2 focus:ring-blue-200 focus:border-blue-400" 
                            placeholder="Contoh: 1">
                    </div>
                </div>
            </div>
        </template>

<script>
// ==========================================
// IMAGE UPLOAD & PREVIEW
// ==========================================
(function(){
    var input = document.getElementById('gambarInput');
    var preview = document.getElementById('previewImage');
    var container = document.getElementById('imageUploadContainer');
    var removeBtn = document.getElementById('removeImageBtn');
    var defaultImage = "{{ $currentImage }}";

    if (!input) return;

    // Handle file input change
    input.addEventListener('change', function(e){
        var file = e.target.files[0];
        if (!file) return;
        
        // Validate file size (2MB max)
        if (file.size > 2048000) {
            alert('Ukuran file terlalu besar! Maksimal 2MB');
            input.value = '';
            return;
        }
        
        var reader = new FileReader();
        reader.onload = function(ev){
            preview.src = ev.target.result;
        }
        reader.readAsDataURL(file);
    });

    // Remove image
    removeBtn.addEventListener('click', function(){
        input.value = '';
        preview.src = defaultImage;
    });

    // Drag & drop support
    container.addEventListener('dragover', function(e){
        e.preventDefault();
        container.classList.add('bg-green-50', 'border-green-400');
    });
    
    container.addEventListener('dragleave', function(e){
        container.classList.remove('bg-green-50', 'border-green-400');
    });
    
    container.addEventListener('drop', function(e){
        e.preventDefault();
        container.classList.remove('bg-green-50', 'border-green-400');
        
        var dt = e.dataTransfer;
        if (dt && dt.files && dt.files.length) {
            var file = dt.files[0];
            
            // Validate file size
            if (file.size > 2048000) {
                alert('Ukuran file terlalu besar! Maksimal 2MB');
                return;
            }
            
            input.files = dt.files;
            var reader = new FileReader();
            reader.onload = function(ev){
                preview.src = ev.target.result;
            }
            reader.readAsDataURL(file);
        }
    });
})();

// ==========================================
// BAHAN PENDUKUNG DYNAMIC
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
    const addBahanBtn = document.getElementById('addBahanBtn');
    const bahanContainer = document.getElementById('bahanContainer');
    const template = document.getElementById('bahanTemplate');

    // Function to update item numbers
    function updateItemNumbers() {
        document.querySelectorAll('.bahan-item').forEach((item, index) => {
            const title = item.querySelector('h3');
            if (title) {
                title.textContent = `Bahan Ke-${index + 1}`;
            }
        });
    }

    // Function to initialize remove buttons
    function initRemoveButtons() {
        document.querySelectorAll('.removeBahanBtn').forEach(btn => {
            btn.onclick = function() {
                this.closest('.bahan-item').remove();
                updateItemNumbers();
            };
        });
    }

    // Add new bahan item
    addBahanBtn.addEventListener('click', function() {
        const newItem = template.content.cloneNode(true);
        bahanContainer.appendChild(newItem);
        updateItemNumbers();
        initRemoveButtons();
        
        // Scroll to new item
        const items = bahanContainer.querySelectorAll('.bahan-item');
        const lastItem = items[items.length - 1];
        lastItem.scrollIntoView({ behavior: 'smooth', block: 'center' });
    });

    // Initialize remove buttons on page load
    initRemoveButtons();
});
</script>

<style>
    /* Smooth transitions */
    input:focus, select:focus, textarea:focus {
        transition: all 0.3s ease;
    }
    
    /* Custom scrollbar for container */
    #bahanContainer {
        max-height: 500px;
        overflow-y: auto;
    }
    
    #bahanContainer::-webkit-scrollbar {
        width: 6px;
    }
    
    #bahanContainer::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    #bahanContainer::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 10px;
    }
    
    #bahanContainer::-webkit-scrollbar-thumb:hover {
        background: #a0aec0;
    }
</style>
@endsection