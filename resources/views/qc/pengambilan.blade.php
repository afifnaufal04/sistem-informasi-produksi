@extends('layouts.allApp')

@section('title', 'Pengambilan - QC')
@section('role', 'Quality Control')

@section('content')
<div class="container mx-auto px-6 py-6">

    <h2 class="text-3xl font-bold mb-6">Daftar Pengambilan</h2>

    @if (session('success'))
        <div class="bg-green-100 px-4 py-3 rounded mb-4">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 px-4 py-3 rounded mb-4">
            @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
            @endforeach
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse ($pengambilanList as $item)
            <div class="bg-white shadow-md rounded-xl p-6">
                <h3 class="text-lg font-semibold mb-3">Pengambilan #{{ $item->pengambilan_id }}</h3>
                
                <div class="text-sm space-y-2 mb-4">
                    <p><span class="font-medium">Supir:</span> {{ $item->supir->name ?? '-' }}</p>
                    <p><span class="font-medium">Kendaraan:</span> {{ $item->kendaraan->nama ?? '-' }}</p>
                    <p><span class="font-medium">Tanggal:</span> {{ $item->tanggal_pengambilan->format('d/m/Y') }}</p>
                    
                    <div class="mt-3">
                        <p class="font-medium mb-1">Barang:</p>
                        @foreach($item->detailPengambilan as $detail)
                            <div class="bg-gray-50 rounded p-2 mb-1 text-xs">
                                <p class="font-semibold">{{ $detail->detailPengiriman->produksi->barang->nama_barang ?? '-' }}</p>
                                <p>{{ $detail->jumlah_diambil }} pcs - {{ $detail->detailPengiriman->supplier->name ?? '-' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mb-3">
                    <span class="px-3 py-1 rounded-full text-xs font-semibold
                        @if($item->status === 'Menunggu QC') bg-red-100 text-red-700
                        @elseif($item->status === 'Menunggu QC Lagi') bg-orange-100 text-orange-700
                        @endif">
                        {{ $item->status }}
                    </span>
                </div>

                @if($item->status === 'Dijadwalkan')
                    <form action="{{ route('qc.pengambilan.konfirmasi', $item->pengambilan_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg">
                            ✓ Konfirmasi Barang Siap
                        </button>
                    </form>
                @endif
            </div>
        @empty
            <div class="col-span-full text-center py-10 text-gray-500">Tidak ada pengambilan</div>
        @endforelse
    </div>
</div>
@endsection