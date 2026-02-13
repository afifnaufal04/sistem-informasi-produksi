@extends('layouts.allApp')

@section('title', 'Daftar Barang')
@section('role', 'Marketing')

@section('content')
    <div class="container mx-auto px-6 w-full">
        <h1 class="text-3xl font-bold mb-4">Daftar Barang</h1>

        @if (session('success'))
            <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4"
                role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
                <span onclick="document.getElementById('alert-success').remove();"
                    class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">&times;</span>
            </div>
        @endif

        <!-- Search dan Tombol Tambah -->
        <div class="flex flex-col md:flex-row gap-4 justify-between mb-4">
            <!-- Search Box -->
            <form action="{{ route('marketing.daftarbarang') }}" method="GET"
                class="flex flex-row gap-2 w-full justify-end">
                <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama barang..."
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none">
                <!-- Tombol Search -->
                <button type="submit" class="flex-1 md:w-auto px-6 py-2 bg-green-600 text-white rounded-lg
                        hover:bg-green-700 transition-colors font-medium">
                    Search
                </button>
            </form>

            <!-- Tombol Tambah Barang -->
            <div class="w-full md:w-auto">
                <a href="{{ route('marketing.barang.create') }}"
                    class="flex items-center justify-center md:justify-start gap-2 bg-green-600 px-4 py-2 rounded-lg text-white hover:bg-green-700 transition-colors w-full md:w-auto">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <span class="whitespace-nowrap">Tambah Barang</span>
                </a>
            </div>
        </div>

        <!-- Info Hasil Pencarian  -->
        @if($search)
            <div class="mb-4 flex items-center justify-between bg-blue-50 border border-blue-200 rounded-lg px-4 py-3">
                <div class="flex items-center gap-2 text-sm text-blue-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>Menampilkan hasil pencarian untuk: <strong>"{{ $search }}"</strong> ({{ $barangs->count() }} barang
                        ditemukan)</span>
                </div>
                <a href="{{ route('marketing.daftarbarang') }}"
                    class="text-blue-600 hover:text-blue-800 text-sm font-medium underline">
                    Reset
                </a>
            </div>
        @endif

        <!-- Grid kartu -->
        <div class="grid gap-4 grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5">
            @forelse ($barangs as $barang)
                <div class="bg-white rounded-xl shadow-lg ring-1 ring-gray-200 overflow-hidden flex flex-col relative">
                    <div class="p-3 flex justify-center">
                        <div class="h-20 w-20 rounded-md overflow-hidden border border-gray-200 shadow-sm flex items-center justify-center transform transition duration-150 ease-out hover:shadow-md hover:border-gray-300 hover:scale-105 cursor-pointer group focus:outline-none focus:ring-2 focus:ring-green-200 js-preview-wrapper"
                            role="button" 
                            tabindex="0" 
                            aria-label="Preview image wrapper"
                            data-src="{{ $barang->gambar_barang ? Storage::url($barang->gambar_barang) : '' }}">
    
                            @if($barang->gambar_barang && Storage::disk('public')->exists($barang->gambar_barang))
                                <img src="{{ Storage::url($barang->gambar_barang) }}" 
                                    data-src="{{ Storage::url($barang->gambar_barang) }}" 
                                    alt="{{ $barang->nama_barang }}"
                                    class="h-full w-full object-contain cursor-pointer js-preview-image transition-transform duration-150 ease-out group-hover:scale-105"
                                    role="img">
                            @else
                                <div class="h-full w-full bg-gray-100 flex items-center justify-center text-gray-400 text-xs text-center p-1">
                                    No Image
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
                            <a href="{{ route('marketing.barang.edit', $barang->barang_id) }}"
                                class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium shadow-sm bg-yellow-400 hover:bg-yellow-500 text-white">Edit</a>
                            <form action="{{ route('marketing.barang.destroy', $barang->barang_id) }}" method="POST"
                                data-confirm-delete="1" data-swal-text="Yakin hapus barang ini?" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-medium shadow-sm bg-red-600 hover:bg-red-700 text-white">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 sm:col-span-3 md:col-span-4 lg:col-span-5 text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                    <p class="mt-2 text-gray-500">
                        @if($search)
                            Tidak ada barang yang cocok dengan pencarian "{{ $search }}"
                        @else
                            Belum ada data barang
                        @endif
                    </p>
                    @if($search)
                        <a href="{{ route('marketing.barang.index') }}"
                            class="mt-4 inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            Tampilkan Semua Barang
                        </a>
                    @endif
                </div>
            @endforelse
        </div>
    </div>

    <!-- modal review gambar -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center z-50 hidden"
        role="dialog" aria-modal="true" aria-hidden="true">
        <div class="relative max-w-3xl w-full mx-4">
            <button type="button" onclick="closeImageModal()"
                class="absolute top-0 right-0 mt-2 mr-2 bg-gray-800 bg-opacity-60 text-white rounded-full p-2 hover:bg-opacity-80 focus:outline-none">
                <span class="sr-only">Close</span>
                &times;
            </button>
            <img id="modalImage" src="" alt="Preview" class="w-full max-h-[80vh] object-contain rounded-md shadow-lg">
        </div>
    </div>

    <script>
        function openImageModal(src) {
            var modal = document.getElementById('imageModal');
            var modalImg = document.getElementById('modalImage');
            modalImg.src = src;
            modal.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }
        function closeImageModal() {
            var modal = document.getElementById('imageModal');
            var modalImg = document.getElementById('modalImage');
            modal.classList.add('hidden');
            modalImg.src = '';
            document.body.classList.remove('overflow-hidden');
        }
        // Tutup saat mengklik di luar gambar
        document.addEventListener('click', function (e) {
            var modal = document.getElementById('imageModal');
            if (!modal) return;
            if (!modal.classList.contains('hidden')) {
                var container = modal.querySelector('div');
                if (!container.contains(e.target)) {
                    closeImageModal();
                }
            }
        });
        //Tutup di ESC
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                var modal = document.getElementById('imageModal');
                if (modal && !modal.classList.contains('hidden')) {
                    closeImageModal();
                }
            }
        });

        document.addEventListener('DOMContentLoaded', function () {
            var imgs = document.querySelectorAll('.js-preview-image');
            imgs.forEach(function (img) {
                img.addEventListener('click', function (e) {
                    e.stopPropagation();
                    var src = img.getAttribute('data-src') || img.src;
                    console.log('preview click', src);
                    openImageModal(src);
                });
            });

            var wrappers = document.querySelectorAll('.js-preview-wrapper');
            wrappers.forEach(function (wrapper) {
                wrapper.addEventListener('click', function (e) {
                    e.stopPropagation();
                    var img = wrapper.querySelector('.js-preview-image');
                    var src = (img && (img.getAttribute('data-src') || img.src)) || wrapper.getAttribute('data-src');
                    if (src) {
                        console.log('preview wrapper click', src);
                        openImageModal(src);
                    }
                });
                wrapper.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        var img = wrapper.querySelector('.js-preview-image');
                        var src = (img && (img.getAttribute('data-src') || img.src)) || wrapper.getAttribute('data-src');
                        if (src) {
                            console.log('preview wrapper key', e.key, src);
                            openImageModal(src);
                        }
                    }
                });
            });
        });
    </script>

@endsection