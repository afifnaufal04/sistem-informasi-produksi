<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Surat Perintah Kerja</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      font-size: 13px;
      color: #000;
    }

    h2 {
      text-align: center;
      margin-bottom: 0;
    }

    h4 {
      text-align: center;
      margin-top: 4px;
      font-weight: normal;
      margin-bottom: 20px;
    }

    /* --- TATA LETAK HEADER --- */
    .header-table {
        width: 100%;
        border: none;
        margin-bottom: 20px;
    }
    .header-table td {
        border: none;
        padding: 2px 0;
        text-align: left;
    }
    .header-table .label-col {
        width: 100%; 
        font-weight: bold;
    }
    .header-table .separator-col {
        width: 50%;
        font-weight: bold;
        text-align: center;
    }
    .header-table .data-col {
        width: 100%;
        font-weight: normal;
    }

    /* --- TABEL DETAIL BARANG --- */
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      table-layout: fixed;
    }
    th, td {
      border: 1px solid #000;
      padding: 6px;
      text-align: center;
      font-size: 12px; 
      vertical-align: middle;
      word-wrap: break-word;
    }
    th {
      background-color: #f5f5f5;
      font-weight: bold;
    }
    .section-title {
      font-weight: bold;
      margin-top: 20px;
      margin-bottom: 8px;
    }

    /* --- PERBAIKAN TANDA TANGAN KRUSIAL --- */
    .sign-table {
      width: 100%;
      border: none;
      margin-top: 50px;
      table-layout: fixed;
    }

    .sign-table td {
      border: none;
      padding: 0 5px; /* Kurangi padding agar lebih rapat */
      text-align: center;
      vertical-align: top;
    }
    
    .sign-table .align-left {
        text-align: left; /* Khusus untuk label Diketahui */
    }

    .jabatan-label {
        font-size: 11px;
        font-weight: normal; /* Normal, bukan bold */
    }
    
    /* Style khusus untuk tanda tangan Diketahui Ganda */
    .double-sign-td {
        padding-top: 5px;
        padding-bottom: 5px;
    }
    .double-sign-td > div {
        display: block;
        margin: 15px 0; /* Jarak antara Finishing dan Gudang */
    }
  </style>
</head>
<body>

  <h2>Surat Perintah Kerja</h2>
  <h4>CoC / Non-CoC</h4>

  {{-- Tabel Header Data --}}
  <table class="header-table">
    <tr>
      {{-- Kolom Kiri --}}
      <td class="label-col">Nomor P.O Buyer</td><td class="separator-col">:</td><td class="data-col">{{ $pemesanan->no_PO ?? '-' }}</td>
      {{-- Kolom Kanan --}}
      <td class="label-col">Nomor SPK</td><td class="separator-col">:</td><td class="data-col">{{ $pemesanan->no_SPK_kwas ?? '-' }}</td>
    </tr>
    <tr>
      <td class="label-col">Tanggal P.O Buyer</td><td class="separator-col">:</td><td class="data-col">{{ \Carbon\Carbon::parse($pemesanan->tgl_pemesanan)->format('d-m-Y') ?? '-' }}</td>
      <td class="label-col">Tanggal Penerbitan SPK</td><td class="separator-col">:</td><td class="data-col">{{ \Carbon\Carbon::parse($pemesanan->tgl_penerbitan_spk)->format('d-m-Y') ?? '-' }}</td>
    </tr>
    <tr>
      <td class="label-col">Kode Buyer</td><td class="separator-col">:</td><td class="data-col">{{ $pemesanan->pembeli->nama_pembeli ?? '-' }}</td>
      <td class="label-col">Periode Produksi</td><td class="separator-col">:</td><td class="data-col">{{ $pemesanan->periode_produksi ?? '-' }}</td>
    </tr>
  </table>

  <div class="section-title">Data Teknik Permintaan Buyer</div>

  {{-- Tabel Detail Barang --}}
  <table>
    <thead>
      <tr>
        <th rowspan="2">NO</th>
        <th rowspan="2">KODE</th>
        <th rowspan="2">NAMA BARANG</th>
        <th colspan="3">DIMENSI / PC (CM)</th>
        <th rowspan="2">QTY</th>
        <th rowspan="2">CBM</th>
        <th rowspan="2">BAHAN / KAYU</th>
        <th rowspan="2">GRADE</th>
        <th rowspan="2">FINISHING</th>
        <th rowspan="2">KONSTRUKSI KD / FA</th>
        <th rowspan="2">PACKING</th>
        <th rowspan="2">STUFFING</th>
        <th rowspan="2">KETERANGAN</th>
      </tr>
      <tr>
        <th>P</th><th>L</th><th>T</th>
      </tr>
    </thead>
    <tbody>
        @foreach($pemesanan->pemesananBarang as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>-</td>
                <td style="text-align: left;">{{ $item->barang->nama_barang ?? '-' }}</td>
                <td>{{ $item->barang->panjang ?? '-' }}</td>
                <td>{{ $item->barang->lebar ?? '-' }}</td>
                <td>{{ $item->barang->tinggi ?? '-' }}</td>
                <td>{{ $item->jumlah_pemesanan ?? '-' }}</td>
                <td>0</td>
                <td>JATI</td>
                <td>-</td>
                <td>{{ $item->barang->jenis_barang?? '-' }}</td>
                <td>-</td>
                <td>0 / Box</td>
                <td>-</td>
                <td>FSC 100%</td>
            </tr>
        @endforeach
    </tbody>
  </table>

    <!-- Footer tanda tangan -->
    <div>
        <table class="sign-table">
            <tr>
                <td colspan="2">Dikeluarkan Oleh</td>
                <td colspan="2">Diterima Oleh</td>
                <td colspan="4">Diketahui Oleh</td>
                <td colspan="2">Disetujui Oleh</td>
            </tr>
            <tr>
                <td colspan="2">&nbsp;</td>
                <td colspan="2">&nbsp;</td>
                <td colspan="4" class="double-sign-td">
                    <div>&nbsp;</div>
                    <div>&nbsp;</div>
                </td>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                {{-- Marketing --}}
                @if($pemesanan->konfirmasi_marketing)
                    <td colspan="2"><img src="{{ public_path('assets/images/TTD_Marketing.png') }}" width="100">
</td>
                @else
                    <td colspan="2">(...........................)</td>
                @endif

                {{-- PPIC --}}
                @if($pemesanan->konfirmasi_ppic)
                    <td colspan="2"><img src="{{ public_path('assets/images/TTD_PPIC.png') }}" width="100">
</td>
                @else
                    <td colspan="2">(...........................)</td>
                @endif

                {{-- QC --}}
                @if($pemesanan->konfirmasi_finishing)
                    <td colspan="2"><img src="{{ public_path('assets/images/TTD_QC.png') }}" width="100"></td>
                @else
                    <td colspan="2">(...........................)</td>
                @endif

                {{-- Gudang --}}
                @if($pemesanan->konfirmasi_gudang)
                    <td colspan="2"><img src="{{ public_path('assets/images/TTD_Gudang.png') }}" width="100"></td>
                @else
                    <td colspan="2">(...........................)</td>
                @endif

                {{-- Manajer (opsional kalau ada) --}}
                @if($pemesanan->konfirmasi_keprod)
                    <td colspan="2"><img src="{{ public_path('assets/images/TTD_Keprod.png') }}" width="100">
</td>
                @else
                    <td colspan="2">(...........................)</td>
                @endif
            </tr>

            <tr>
                <td colspan="2" class="jabatan-label">Bagian Marketing</td>
                <td colspan="2" class="jabatan-label">Bagian PPIC/Produksi</td>
                <td colspan="2" class="jabatan-label">Bagian QC</td>
                <td colspan="2" class="jabatan-label">Bagian Gudang/Logistik</td>
                <td colspan="2" class="jabatan-label">Manajer Operasional</td>
            </tr>
        </table>
        
    </div>


</body>
</html>