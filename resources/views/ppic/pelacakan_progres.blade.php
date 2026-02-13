@extends('layouts.allApp')

@section('title', 'Pelacakan Progres Produksi')
@section('role', 'PPIC')

@section('content')
<div class="container mx-auto px-6 w-full">
    <h2 class="text-3xl font-extrabold mb-6">Pelacakan Progres Produksi</h2>
    <div class="grid grid-cols-1 rounded-lg sm:grid-cols-2 lg:grid-cols-3 gap-6">
    @php
        // Sort so that 'Dalam Pengerjaan' / 'Diproses' appear first, 'Selesai' last
        $sortedMatrix = collect($matrix)->sortBy(function($row) {
            $status = isset($row['status_produksi']) ? strtolower($row['status_produksi']) : '';
            if ($status === 'dalam pengerjaan' || $status === 'diproses') return 0;
            if ($status === 'selesai') return 2;
            return 1;
        })->values();
    @endphp
    @forelse ($sortedMatrix as $index => $row)
        <div class="bg-white rounded-2xl shadow-md hover:shadow-lg transition p-5 border border-gray-100">
            {{-- Header Card --}}
            <div class="flex justify-between items-center mb-3">
                <span class="text-xl text-green-600 font-bold">Progres #{{ $index + 1 }}</span>
                @php $statusLower = strtolower(trim($row['status_produksi'] ?? '')) @endphp
                <span class="px-3 py-1 text-xs rounded-full font-semibold {{ $statusLower === 'selesai' ? 'bg-green-100 text-green-700' : ($statusLower === 'dalam pengerjaan' || $statusLower === 'diproses' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700') }}">
                    {{ $row['status_produksi'] }}
                </span>
            </div>

            <h2 class="text-2xl font-extrabold text-gray-800 mb-1">{{ $row['nama_barang'] }}</h2>
            <p class="text-lg text-gray-600 mb-1">Jumlah: <span class="font-bold">{{ $row['jumlah_produksi'] }}</span></p>
            <p class="text-lg text-gray-600 mb-4">Jenis Barang: <span class="font-bold">{{ $row['jenis_barang'] }}</span></p>

            {{-- Sub Proses --}}
            <div class="grid grid-cols-2 gap-2 mb-4 text-sm">
                @foreach ($subProsesList as $sp)
                    <div class="flex justify-between bg-gray-50 px-3 py-1 rounded-lg">
                        <span class="text-gray-600">{{ $sp->nama_sub_proses }}</span>
                        <span class="font-bold text-gray-800">
                            {{ $row['subproses'][$sp->sub_proses_id] ?? 0 }}
                        </span>
                    </div>
                @endforeach
            </div>

            {{-- Aksi --}}
            <div class="pt-3 border-t flex justify-end">
                <button  onclick="openPindahGudangModal({{ $row['produksi_id'] }}, '{{ $row['nama_barang'] }}', {{ $row['jumlah_siap_gudang'] }})"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-semibold transition">
                    Pindah ke Gudang ({{ $row['jumlah_siap_gudang'] }})
                </button>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-10 text-gray-500 bg-white rounded-xl shadow">
            Tidak Ada Progres Produksi
        </div>
    @endforelse
    </div>

</div>

{{-- Modal Pindah ke Gudang --}}
<div id="pindahGudangModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-xl shadow-2xl max-w-md w-full p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-bold text-gray-800">Pindah Barang Jadi ke Gudang</h3>
            <button onclick="closePindahGudangModal()" class="text-gray-500 hover:text-gray-700">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div class="mb-4 bg-green-50 p-3 rounded-lg border border-green-200">
            <p class="text-sm font-medium text-gray-700">Barang: <span id="gudang_nama_barang" class="text-green-600"></span></p>
            <p class="text-sm font-medium text-gray-700">Tersedia: <span id="gudang_jumlah_tersedia" class="text-green-600"></span> pcs</p>
        </div>

        <form id="pindahGudangForm" method="POST">
            @csrf
            <input type="hidden" name="produksi_id" id="gudang_produksi_id">

            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah yang Dipindahkan <span class="text-red-500">*</span></label>
                <input type="number" name="jumlah_pindah" id="jumlah_pindah" min="1" required
                    oninput="validatePindahGudang()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <p class="text-xs text-gray-500 mt-1">Maksimal: <span id="max_pindah" class="font-semibold">-</span> pcs</p>
            </div>

            <div class="mb-4 p-4 rounded-lg hidden" id="pindahValidasiInfo">
                <p id="pindahValidasiMessage" class="text-sm"></p>
            </div>

            <div class="flex gap-3">
                <button type="button" onclick="closePindahGudangModal()" 
                    class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg font-medium transition">
                    Batal
                </button>
                <button type="submit" id="pindahSubmitBtn"
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition">
                    Pindahkan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let maxJumlahPindah = 0;

    function openPindahGudangModal(produksiId, namaBarang, jumlahTersedia) {
        maxJumlahPindah = jumlahTersedia;
        
        document.getElementById('pindahGudangModal').classList.remove('hidden');
        document.getElementById('gudang_produksi_id').value = produksiId;
        document.getElementById('gudang_nama_barang').textContent = namaBarang;
        document.getElementById('gudang_jumlah_tersedia').textContent = jumlahTersedia;
        document.getElementById('max_pindah').textContent = jumlahTersedia;
        
        // Set form action
        document.getElementById('pindahGudangForm').action = `/ppic/produksi/pindah-ke-gudang/${produksiId}`;
        
        // Reset
        document.getElementById('jumlah_pindah').value = '';
        document.getElementById('pindahValidasiInfo').classList.add('hidden');
    }

    function closePindahGudangModal() {
        document.getElementById('pindahGudangModal').classList.add('hidden');
    }

    function validatePindahGudang() {
        const jumlahPindah = parseInt(document.getElementById('jumlah_pindah').value) || 0;
        const validasiInfo = document.getElementById('pindahValidasiInfo');
        const validasiMessage = document.getElementById('pindahValidasiMessage');
        const submitBtn = document.getElementById('pindahSubmitBtn');

        if (jumlahPindah === 0) {
            validasiInfo.classList.add('hidden');
            return;
        }

        if (jumlahPindah > maxJumlahPindah) {
            validasiInfo.className = 'mb-4 p-4 rounded-lg bg-red-100 border border-red-300';
            validasiMessage.textContent = `✗ Jumlah melebihi yang tersedia! Maksimal: ${maxJumlahPindah} pcs`;
            validasiMessage.className = 'text-sm text-red-700';
            validasiInfo.classList.remove('hidden');
            submitBtn.disabled = true;
            submitBtn.className = 'flex-1 bg-gray-400 text-white px-4 py-2 rounded-lg font-medium cursor-not-allowed';
        } else {
            validasiInfo.className = 'mb-4 p-4 rounded-lg bg-green-100 border border-green-300';
            validasiMessage.textContent = `✓ Valid! ${jumlahPindah} pcs akan dipindahkan ke gudang`;
            validasiMessage.className = 'text-sm text-green-700';
            validasiInfo.classList.remove('hidden');
            submitBtn.disabled = false;
            submitBtn.className = 'flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg font-medium transition';
        }
    }

    document.getElementById('pindahGudangModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePindahGudangModal();
        }
    });
</script>
@endsection