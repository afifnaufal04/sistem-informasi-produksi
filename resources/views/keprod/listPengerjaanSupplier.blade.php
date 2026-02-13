@extends('layouts.allApp')

@section('title', 'List Pengerjaan Supplier ')
@section('role', 'Kepala Produksi')

@section('content')
<div class="container mx-auto px-6 py-3">

    {{-- Header --}}
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-6">
        <h1 class="text-3xl font-bold">List Pengerjaan Supplier</h1>
    </div>

    {{-- ================= DESKTOP TABLE ================= --}}
    <div class="hidden md:block bg-white shadow rounded-xl overflow-hidden">
        <table class="w-full">
            <thead class="bg-green-600 text-white">
                <tr>
                    <th class="px-4 py-3 text-center">No</th>
                    <th class="px-4 py-3 text-left">Nama Supplier</th>
                    <th class="px-4 py-3 text-left">Tanggal Selesai</th>
                    <th class="px-4 py-3 text-left">Barang</th>
                    <th class="px-4 py-3 text-center">Jumlah Pengiriman</th>
                    <th class="px-4 py-3 text-center">QC</th>
                    <th class="px-4 py-3 text-center">Pengecekan QC</th>
                    <th class="px-4 py-3 text-center">Sudah Diambil</th>
                    <th class="px-4 py-3 text-center">Sisa Belum Diambil</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse ($pengiriman as $pengirimans)
                    @foreach ($pengirimans->detailPengiriman as $detail)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-center">
                                {{ $loop->parent->iteration }}.{{ $loop->iteration }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $detail->supplier->name ?? '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $pengirimans->tanggal_selesai ? $pengirimans->tanggal_selesai->format('d/m/Y') : '-' }}
                            </td>

                            <td class="px-4 py-3">
                                {{ $detail->produksi->barang->nama_barang ?? '-' }}
                            </td>

                            <td class="px-4 py-3 text-center">
                                {{ $detail->jumlah_pengiriman_bersih }} pcs
                            </td>

                            <td class="px-4 py-3 text-center">
                                {{ $pengirimans->qc->name ?? '-' }} 
                            </td>

                            <td class="px-4 py-3 text-center">
                                <span class="font-semibold text-green-600">
                                    {{ $detail->lolos_qc }} pcs
                                </span>
                            </td>   
                            
                            <td class="px-4 py-3 text-center">
                                <span class="font-semibold text-blue-600">
                                    {{ $detail->sudah_diambil }} pcs
                                </span>
                            </td>   
                            
                            <td class="px-4 py-3 text-center">
                                <span class="font-bold {{ $detail->sisa_belum_diambil > 0 ? 'text-orange-600' : 'text-gray-400' }}">
                                    {{ $detail->sisa_belum_diambil }} pcs
                                </span>
                            </td>   
                        </tr>
                    @endforeach
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-8 text-gray-500">
                            Belum ada Pengerjaan Barang di Supplier
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- ================= MOBILE CARDS ================= --}}
    <div class="md:hidden space-y-4">

        @forelse ($groupedSuppliers as $supplierId => $details)
            <div class="bg-white shadow rounded-xl overflow-hidden">

                {{-- ================= CARD HEADER ================= --}}
                <div class="px-4 py-4 bg-green-600 text-white">
                    <div class="font-semibold text-base">
                        Supplier : {{ $details->first()->supplier->name ?? '-' }}
                    </div>
                    <div class="text-xs opacity-90 mt-1">
                        Total Barang yang dikerjakan: {{ $details->count() }}
                    </div>
                </div>

                {{-- ================= DROPDOWN TOGGLE ================= --}}
                <button onclick="toggleDropdown('supplier-{{ $loop->index }}')" class="w-full px-4 py-3 bg-gray-50 flex items-center justify-between hover:bg-gray-100 border-b">
                    <span class="text-sm font-medium text-gray-700">
                        Lihat Barang
                    </span>

                    <svg id="icon-supplier-{{ $loop->index }}" class="w-5 h-5 transform transition-transform duration-200 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                {{-- ================= DROPDOWN CONTENT ================= --}}
                <div id="supplier-{{ $loop->index }}" class="hidden divide-y">
                    @foreach ($details as $detail)
                        <div class="px-4 py-4 text-sm">
                            <div class="font-semibold text-gray-800 mb-2">
                                {{ $detail->produksi->barang->nama_barang ?? '-' }}
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Jumlah Kirim</span>
                                <span>{{ $detail->jumlah_pengiriman }} pcs</span>
                            </div>

                            <div class="flex justify-between mt-1">
                                <span class="text-gray-500">Lolos QC</span>
                                <span class="font-semibold text-green-600">
                                    {{ $detail->lolos_qc }} pcs
                                </span>
                            </div>

                            <div class="flex justify-between mt-1">
                                <span class="text-gray-500">Sudah Diambil</span>
                                <span class="font-semibold text-blue-600">
                                    {{ $detail->sudah_diambil }} pcs
                                </span>
                            </div>

                            <div class="flex justify-between mt-1">
                                <span class="text-gray-500">Sisa</span>
                                <span class="font-bold
                                    {{ $detail->sisa_belum_diambil > 0 ? 'text-orange-600' : 'text-gray-400' }}">
                                    {{ $detail->sisa_belum_diambil }} pcs
                                </span>
                            </div>
                            <div class="flex justify-between mt-1">
                                <span class="text-gray-500">Tanggal selesai</span>
                                <span class="font-bold italic">
                                    {{ $detail->pengiriman->tanggal_selesai?->format('d/m/Y') ?? '-' }}
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @empty
            <div class="bg-white shadow rounded-xl p-8 text-center">
                <p class="text-gray-500">
                    Belum ada Pengerjaan Barang di Supplier
                </p>
            </div>
        @endforelse
    </div>

    
</div>

@push('scripts')
<script>
    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        
        dropdown.classList.toggle('hidden');
        icon.classList.toggle('rotate-180');
    }
</script>
@endpush
@endsection