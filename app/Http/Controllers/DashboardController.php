<?php

namespace App\Http\Controllers;

use App\Models\BahanPendukung;
use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\DetailPengambilan;
use App\Models\DetailPengiriman;
use App\Models\Packing;
use App\Models\PembelianBahanPendukung;
use App\Models\Pemesanan;
use App\Models\Pengambilan;
use App\Models\Pengiriman;
use App\Models\Produksi;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function indexForMarketing()
    {
        $barangs = Barang::all()->count();
        $pemesanan = Pemesanan::all()->count();
        return view('marketing.dashboard', compact('barangs', 'pemesanan'));
    }

    public function indexForPPIC()
    {
        $produksi = Produksi::all()->count();
        $produksiSelesai = Produksi::where('status_produksi', 'Selesai')->count();
        $produksiDalamProses = Produksi::whereIn('status_produksi', ['Diproses', 'Pending'])->count();

        $pemesanan = Pemesanan::all()->count();

        $stokBP = BahanPendukung::all()->count();
        $stokBPHabis = BahanPendukung::where('stok_bahan_pendukung', '=', 0)->count();

        $orderBP = PembelianBahanPendukung::all()->count();
        $orderBPDalamProses = PembelianBahanPendukung::whereIn('status_order', ['Menunggu', 'Proses Pembelian'])->count();
        $orderBPSelesai = PembelianBahanPendukung::where('status_order', 'Barang Diterima')->count();

        $barang = Barang::all()->count();
        $barangHabis = Barang::where('stok_gudang', '=', 0)->count();

        $packing = Packing::all()->count();
        $packingSelesai = Packing::where('status_packing', 'Selesai')->count();
        $packingDalamProses = Packing::where('status_packing', 'Dalam Proses')->count();
        
        return view('ppic.dashboard', compact(
            'produksi',
            'produksiSelesai',
            'produksiDalamProses',
            'pemesanan',
            'stokBP',
            'stokBPHabis',
            'orderBP',
            'orderBPSelesai',
            'orderBPDalamProses',
            'barang',
            'barangHabis',
            'packing',
            'packingSelesai',
            'packingDalamProses'
        ));
    }

    public function indexForKeprod()
    {
        $produksi = Produksi::all()->count();
        $produksiSelesai = Produksi::where('status_produksi', 'Selesai')->count();
        $produksiDalamProses = Produksi::whereIn('status_produksi', ['Diproses', 'Pending'])->count();

        $pengiriman = Pengiriman::all()->count();
        $pengirimanSelesai = Pengiriman::where('status', 'Selesai')->count();
        $pengirimanDalamProses = Pengiriman::whereIn('status', ['Menunggu QC','Sedang Dipersiapkan','Dalam Pengiriman'])->count();

        $pengambilan = Pengambilan::all()->count();
        $pengambilanSelesai = Pengambilan::where('status', 'Selesai')->count();
        $pengambilanDalamProses = Pengambilan::whereIn('status', ['Dijadwalkan', 'Diambil'])->count();
        
        return view('keprod.dashboard', compact(
            'produksi',
            'produksiSelesai',
            'produksiDalamProses',
            'pengiriman',
            'pengirimanSelesai',
            'pengirimanDalamProses',
            'pengambilan',
            'pengambilanSelesai',
            'pengambilanDalamProses'
        ));
    }
    public function indexForGudang()
    {
        $pemesanan = Pemesanan::all()->count();

        $stokBP = BahanPendukung::all()->count();
        $stokBPHabis = BahanPendukung::where('stok_bahan_pendukung', '=', 0)->count();

        $orderBP = PembelianBahanPendukung::all()->count();
        $orderBPDalamProses = PembelianBahanPendukung::whereIn('status_order', ['Menunggu', 'Proses Pembelian'])->count();
        $orderBPSelesai = PembelianBahanPendukung::where('status_order', 'Barang Diterima')->count();

        $detailPengirimanButuhBP = DetailPengiriman::where('butuh_bp', 1)->where('gudang_konfirmasi', 0)->count();
        return view('gudang.dashboard', compact(
            'pemesanan',
            'stokBP',
            'stokBPHabis',
            'orderBP',
            'orderBPDalamProses',
            'orderBPSelesai',
            'detailPengirimanButuhBP'
        ));
    }
    public function indexForSupplier()
    {
        $user = Auth::user();
        $barangMasuk = DetailPengiriman::where('status_pengiriman', 'Sampai')
                                        ->where('supplier_id', $user->id)
                                        ->count();

        $produksi = DetailPengiriman::where('supplier_id', $user->id)
                                    ->where('status_pengiriman', 'Diterima')
                                    ->count();
        $produksiSelesai = DetailPengiriman::where('supplier_id', $user->id)
                                        ->where('status_pengiriman', 'Diterima')
                                        ->where('status_pengerjaan', 'Selesai')
                                        ->count();
        $produksiDalamProses = DetailPengiriman::where('supplier_id', $user->id)
                                        ->where('status_pengiriman', 'Diterima')
                                        ->whereIn('status_pengerjaan', ['Dalam Pengerjaan', 'Perlu Perbaikan'])
                                        ->count();
        
        return view('supplier.dashboard', compact(
            'barangMasuk',
            'produksi',
            'produksiSelesai',
            'produksiDalamProses'
        ));
    }

    public function indexForSupir()
    {
        $user = Auth::user();
        $pengiriman = Pengiriman::where('supir_id', $user->id)->count();
        $pengirimanSelesai = Pengiriman::where('supir_id', $user->id)
                                        ->where('status', 'Selesai')
                                        ->count();
        $pengirimanDalamProses = Pengiriman::where('supir_id', $user->id)
                                        ->whereIn('status', ['Menunggu QC','Sedang Dipersiapkan','Dalam Pengiriman'])
                                        ->count();

        $pengambilan = Pengambilan::where('supir_id', $user->id)->count();
        $pengambilanSelesai = Pengambilan::where('supir_id', $user->id)
                                        ->where('status', 'Selesai')
                                        ->count();
        $pengambilanDalamProses = Pengambilan::where('supir_id', $user->id)
                                        ->whereIn('status', ['Dijadwalkan', 'Diambil'])
                                        ->count(); 
        return view('supir.dashboard', compact(
            'pengiriman',
            'pengirimanSelesai',
            'pengirimanDalamProses',
            'pengambilan',
            'pengambilanSelesai',
            'pengambilanDalamProses'
        ));       
    }

    public function indexForPurchasing()
    {
        $stokBP = BahanPendukung::all()->count();
        $stokBPHabis = BahanPendukung::where('stok_bahan_pendukung', '=', 0)->count();

        $orderBP = PembelianBahanPendukung::all()->count();
        $orderBPDalamProses = PembelianBahanPendukung::whereIn('status_order', ['Menunggu', 'Proses Pembelian'])->count();
        $orderBPSelesai = PembelianBahanPendukung::where('status_order', 'Barang Diterima')->count();

        $pembayaranBelumDibayar = DetailPengambilan::where('status_bayar', 'Belum Dibayar')->count();
        $pembayaranSudahDibayar = DetailPengambilan::where('status_bayar', 'Lunas')->count();

        return view('purchasing.dashboard', compact(
            'stokBP',
            'stokBPHabis',
            'orderBP',
            'orderBPDalamProses',
            'orderBPSelesai',
            'pembayaranBelumDibayar',
            'pembayaranSudahDibayar'
        ));

    }

    public function indexForQC()
    {
        $user = Auth::user();
        $pemesanan = Pemesanan::all()->count();

        $produksi = Produksi::all()->count();
        $produksiSelesai = Produksi::where('status_produksi', 'Selesai')->count();
        $produksiDalamProses = Produksi::whereIn('status_produksi', ['Diproses', 'Pending'])->count();

        $pengiriman = Pengiriman::where('qc_id', $user->id)->count();
        $pengirimanButuhQC = Pengiriman::where('qc_id', $user->id)
                                    ->where('status', 'Menunggu QC')
                                    ->count();

        $pengambilan = Pengambilan::where('qc_id', $user->id)->count();
        
        $produksiPerluQC = DetailPengiriman::where('status_pengiriman', 'Diterima')->count(); 
        return view('qc.dashboard', compact(
            'pemesanan',
            'produksi',
            'produksiSelesai',
            'produksiDalamProses',
            'pengiriman',
            'pengirimanButuhQC',
            'pengambilan',
            'produksiPerluQC'
        ));

    }
    public function indexForPacking()
    {
        $packing = Packing::all()->count();
        $packingSelesai = Packing::where('status_packing', 'Selesai')->count();
        $packingDalamProses = Packing::where('status_packing', 'Dalam Proses')->count();
        return view('packing.dashboard', compact(
            'packing',
            'packingSelesai',
            'packingDalamProses'
        ));
    }
}
