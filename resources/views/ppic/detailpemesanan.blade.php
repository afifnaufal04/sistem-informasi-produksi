@extends('layouts.allApp')
@section('title', 'Detail Pemesanan')
@section('role', 'PPIC')

@section('content')
<div class="mx-2 flex justify-start space-x-2 mb-4">
    <a href="{{ route('ppic.pemesanan.index') }}" 
        class="bg-blue-500 hover:bg-blue-600 text-white font-medium px-4 py-2 rounded shadow">
        Kembali
    </a>
    <a href="{{ route('ppic.pemesanan.downloadspk', $pemesanan->pemesanan_id) }}" 
        class="bg-red-500 hover:bg-red-700 text-white font-medium px-4 py-2 rounded shadow">
        Download SPK
    </a>

    <form action="{{ route('ppic.pemesanan.konfirmasi', ['id' => $pemesanan->pemesanan_id, 'bagian' => 'ppic']) }}" method="POST">
        @csrf
        <button type="submit" class="bg-green-600 text-white font-medium px-4 py-2 rounded shadow">
            Konfirmasi SPK
        </button>
    </form>
</div>
<div class="max-w-6xl mx-auto bg-white p-8 rounded-lg shadow-md">
    <h3 class="text-center text-2xl font-bold mb-1">Surat Perintah Kerja</h3>
    <p class="text-center text-gray-600 mb-6">CoC / Non-CoC</p>

    <!-- Bagian Informasi PO dan SPK -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
        <table class="w-full text-sm">
            <tbody class="divide-y divide-gray-200">
                <tr>
                    <td class="py-2 font-semibold w-1/3">Nomor P.O Buyer</td>
                    <td class="px-2">:</td>
                    <td class="py-2">{{ $pemesanan->no_PO ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-semibold">Tanggal P.O Buyer</td>
                    <td class="px-2">:</td>
                    <td class="py-2">{{ \Carbon\Carbon::parse($pemesanan->tgl_pemesanan)->format('d-m-Y') ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-semibold">Kode Buyer</td>
                    <td class="px-2">:</td>
                    <td class="py-2">{{ $pemesanan->pembeli->nama_pembeli ?? '-' }}</td>
                </tr>
            </tbody>
        </table>

        <table class="w-full text-sm">
            <tbody class="divide-y divide-gray-200">
                <tr>
                    <td class="py-2 font-semibold w-1/3">Nomor SPK</td>
                    <td class="px-2">:</td>
                    <td class="py-2">{{ $pemesanan->no_SPK_kwas ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-semibold">Tanggal Penerbitan SPK</td>
                    <td class="px-2">:</td>
                    <td class="py-2">{{ \Carbon\Carbon::parse($pemesanan->tgl_penerbitan_spk)->format('d-m-Y') ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="py-2 font-semibold">Periode Produksi</td>
                    <td class="px-2">:</td>
                    <td class="py-2">{{ \Carbon\Carbon::parse($pemesanan->periode_produksi)->format('d-m-Y') ?? '-'}}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Tabel Data Teknik -->
    <div class="mb-10">
        <p class="font-semibold text-gray-700 mb-4">Data Teknik Permintaan Buyer</p>
        <div class="overflow-x-auto">
            <table class="w-full border border-gray-300 text-xs text-gray-800">
                <thead class="bg-gray-100">
                    <tr>
                        <th rowspan="2" class="px-2 py-2 border text-center">NO</th>
                        <th rowspan="2" class="px-2 py-2 border text-center">KODE</th>
                        <th rowspan="2" class="px-2 py-2 border text-center">NAMA BARANG</th>
                        <th colspan="3" class="px-2 py-2 border text-center">DIMENSI / PC (CM)</th>
                        <th rowspan="2" class="px-2 py-2 border text-center">QTY</th>
                        <th rowspan="2" class="px-2 py-2 border text-center">CBM</th>
                        <th rowspan="2" class="px-2 py-2 border text-center">BAHAN / KAYU</th>
                        <th rowspan="2" class="px-2 py-2 border text-center">GRADE</th>
                        <th rowspan="2" class="px-2 py-2 border text-center">FINISHING</th>
                        <th rowspan="2" class="px-2 py-2 border text-center">KONSTRUKSI<br>KD / FA</th>
                        <th rowspan="2" class="px-2 py-2 border text-center">PACKING</th>
                        <th rowspan="2" class="px-2 py-2 border text-center">STUFFING</th>
                        <th rowspan="2" class="px-2 py-2 border text-center">KETERANGAN</th>
                    </tr>
                    <tr>
                        <th class="px-2 py-2 border text-center">P</th>
                        <th class="px-2 py-2 border text-center">L</th>
                        <th class="px-2 py-2 border text-center">T</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pemesanan->pemesananBarang as $index => $item)
                        <tr class="text-center">
                            <td class="border px-2 py-1">{{ $index + 1 }}</td>
                            <td class="border px-2 py-1"></td>
                            <td class="border px-2 py-1">{{ $item->barang->nama_barang ?? '-' }}</td>
                            <td class="border px-2 py-1">{{ $item->barang->panjang ?? '-' }}</td>
                            <td class="border px-2 py-1">{{ $item->barang->lebar ?? '-' }}</td>
                            <td class="border px-2 py-1">{{ $item->barang->tinggi ?? '-' }}</td>
                            <td class="border px-2 py-1">{{ $item->jumlah_pemesanan ?? '-' }}</td>
                            <td class="border px-2 py-1">0</td>
                            <td class="border px-2 py-1">JATI</td>
                            <td class="border px-2 py-1">-</td>
                            <td class="border px-2 py-1">{{ $item->barang->jenis_barang?? '-' }}</td>
                            <td class="border px-2 py-1">-</td>
                            <td class="border px-2 py-1">0 / Box</td>
                            <td class="border px-2 py-1">-</td>
                            <td class="border px-2 py-1">FSC 100%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Footer tanda tangan -->
    {{-- DESKTOP --}}
    <footer class="hidden lg:block mt-20 text-sm">
        <div class="flex justify-between text-center w-full">

            <div class="w-1/5">
                <p class="mb-16">Dikeluarkan Oleh</p>
                @if($pemesanan->konfirmasi_marketing)
                    <img src="{{ asset('assets/images/TTD_Marketing.png') }}" class="mx-auto" width="110">
                @else
                    <p>(...........................)</p>
                @endif
                <p class="font-semibold mt-2">Bagian Marketing</p>
            </div>

            <div class="w-1/5">
                <p class="mb-16">Diterima Oleh</p>
                @if($pemesanan->konfirmasi_ppic)
                    <img src="{{ asset('assets/images/TTD_PPIC.png') }}" class="mx-auto" width="110">
                @else
                    <p>(...........................)</p>
                @endif
                <p class="font-semibold mt-2">Bagian PPIC/Produksi</p>
            </div>

            <div class="w-1/5">
                <p class="mb-16">Diketahui Oleh</p>
                @if($pemesanan->konfirmasi_finishing)
                    <img src="{{ asset('assets/images/TTD.png') }}" class="mx-auto" width="110">
                @else
                    <p>(...........................)</p>
                @endif
                <p class="font-semibold mt-2">Bagian QC</p>
            </div>

            <div class="w-1/5">
                <p class="mb-16">Diketahui Oleh</p>
                @if($pemesanan->konfirmasi_gudang)
                    <img src="{{ asset('assets/images/TTD.png') }}" class="mx-auto" width="110">
                @else
                    <p>(...........................)</p>
                @endif
                <p class="font-semibold mt-2">Bagian Gudang/Logistik</p>
            </div>

            <div class="w-1/5">
                <p class="mb-16">Disetujui Oleh</p>
                @if($pemesanan->konfirmasi_keprod)
                    <img src="{{ asset('assets/images/TTD_Keprod.png') }}" class="mx-auto" width="110">
                @else
                    <p>(...........................)</p>
                @endif
                <p class="font-semibold mt-2">Manajer Operasional</p>
            </div>

        </div>
    </footer>

    {{-- MOBILE --}}
    <footer class="block lg:hidden mt-12 text-sm">
        <div class="grid grid-cols-2 gap-y-10 text-center">

            <div>
                <p class="mb-10">Dikeluarkan Oleh</p>
                @if($pemesanan->konfirmasi_marketing)
                    <img src="{{ asset('assets/images/TTD.png') }}" class="mx-auto" width="90">
                @else
                    <p>(...........................)</p>
                @endif
                <p class="font-semibold mt-2">Marketing</p>
            </div>

            <div>
                <p class="mb-10">Diterima Oleh</p>
                @if($pemesanan->konfirmasi_ppic)
                    <img src="{{ asset('assets/images/TTD.png') }}" class="mx-auto" width="90">
                @else
                    <p>(...........................)</p>
                @endif
                <p class="font-semibold mt-2">PPIC / Produksi</p>
            </div>

            <div>
                <p class="mb-10">Diketahui Oleh</p>
                @if($pemesanan->konfirmasi_finishing)
                    <img src="{{ asset('assets/images/TTD.png') }}" class="mx-auto" width="90">
                @else
                    <p>(...........................)</p>
                @endif
                <p class="font-semibold mt-2">QC</p>
            </div>

            <div>
                <p class="mb-10">Diketahui Oleh</p>
                @if($pemesanan->konfirmasi_gudang)
                    <img src="{{ asset('assets/images/TTD.png') }}" class="mx-auto" width="90">
                @else
                    <p>(...........................)</p>
                @endif
                <p class="font-semibold mt-2">Gudang / Logistik</p>
            </div>

            <div class="col-span-2">
                <p class="mb-10">Disetujui Oleh</p>
                @if($pemesanan->konfirmasi_keprod)
                    <img src="{{ asset('assets/images/TTD.png') }}" class="mx-auto" width="90">
                @else
                    <p>(...........................)</p>
                @endif
                <p class="font-semibold mt-2">Manajer Operasional</p>
            </div>

        </div>
    </footer>

</div>
@endsection