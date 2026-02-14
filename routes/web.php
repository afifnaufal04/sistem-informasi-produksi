<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\BahanPendukungController;
use App\Http\Controllers\PemesananController;  
use App\Http\Controllers\BarangController;
use App\Http\Controllers\GudangQCController;
use App\Http\Controllers\PembelianBahanPendukungController;
use App\Http\Controllers\ProgresProduksiController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\PengirimanInternalController;
use App\Http\Controllers\PengambilanController;
use App\Http\Controllers\PengambilanInternalController;
use App\Http\Controllers\SupplierProduksiController;
use App\Http\Controllers\PackingController;
use App\Http\Controllers\DashboardController;
use App\Models\Pengiriman;
use Illuminate\Http\Request;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route(Auth::user()->role . '.dashboard');
    }
    return redirect()->route('login'); // redirect ke login bawaan Breeze
});

//==========================================================================================
//                                   ADMIN
//==========================================================================================
Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Hanya admin yang bisa buka form register user
    Route::get('register', [RegisteredUserController::class, 'create'])->name('admin.register');

    // Hanya admin yang bisa menyimpan user baru
    Route::post('register', [RegisteredUserController::class, 'store'])->name('admin.register.store');

    // Barang
    Route::get('/daftarbarang', [BarangController::class, 'index'])->name('admin.daftarbarang');
    Route::get('/daftarbarang/create', [BarangController::class, 'create'])->name('admin.barang.create');
    Route::post('/daftarbarang/store', [BarangController::class, 'store'])->name('admin.barang.store');
    Route::get('/daftarbarang/edit/{id}', [BarangController::class, 'edit'])->name('admin.barang.edit');
    Route::put('/daftarbarang/update/{id}', [BarangController::class, 'update'])->name('admin.barang.update');
    Route::delete('/daftarbarang/destroy/{id}', [BarangController::class, 'destroy'])->name('admin.barang.destroy');

    // Pembeli
    Route::get('/daftarPembeli', [App\Http\Controllers\BuyerController::class, 'index'])->name('admin.daftarPembeli');
    Route::get('/daftarPembeli/create', [App\Http\Controllers\BuyerController::class, 'create'])->name('admin.pembeli.create');
    Route::post('/daftarPembeli/store', [App\Http\Controllers\BuyerController::class, 'store'])->name('admin.pembeli.store');
    Route::delete('/daftarPembeli/destroy/{id}', [App\Http\Controllers\BuyerController::class, 'destroy'])->name('admin.pembeli.destroy');


});


//==========================================================================================


//==========================================================================================
//                                 MARKETING
//==========================================================================================
Route::prefix('marketing')->middleware(['auth', 'role:marketing'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'indexForMarketing'])->name('marketing.dashboard');
    
    // Barang
    Route::get('/daftarbarang', [BarangController::class, 'index'])->name('marketing.daftarbarang');
    Route::get('/daftarbarang/create', [BarangController::class, 'create'])->name('marketing.barang.create');
    Route::post('/daftarbarang/store', [BarangController::class, 'store'])->name('marketing.barang.store');
    Route::get('/daftarbarang/edit/{id}', [BarangController::class, 'edit'])->name('marketing.barang.edit');
    Route::put('/daftarbarang/update/{id}', [BarangController::class, 'update'])->name('marketing.barang.update');
    Route::delete('/daftarbarang/destroy/{id}', [BarangController::class, 'destroy'])->name('marketing.barang.destroy');

    //Pemesanan
    Route::get('/pemesanan', [PemesananController::class, 'index'])->name('marketing.pemesanan.index');
    Route::get('/pemesanan/create', [PemesananController::class, 'create'])->name('marketing.pemesanan.create');
    Route::post('/pemesanan/store', [PemesananController::class, 'store'])->name('marketing.pemesanan.store');
    Route::get('/pemesanan/{id}/detail', [PemesananController::class, 'show'])->name('marketing.pemesanan.show');
    Route::delete('/pemesanan/{id}/destroy', [PemesananController::class, 'destroy'])->name('marketing.pemesanan.destroy');
    Route::get('/pemesanan/{id}/spk-download', [PemesananController::class, 'downloadSPK'])->name('marketing.pemesanan.downloadspk');
    Route::post('/pemesanan/{id}/konfirmasi/{bagian}', [PemesananController::class, 'konfirmasi'])->name('marketing.pemesanan.konfirmasi');

});
//==========================================================================================



//==========================================================================================
//                         Marketing, PPIC, Keprod, Gudang, Quality Control
//==========================================================================================
// Route::middleware(['auth', 'role:marketing,qc,ppic,keprod,gudang'])->group(function () {
//     Route::resource('pemesanan', PemesananController::class);

//     Route::get('pemesanan/{id}/spk-download', [PemesananController::class, 'downloadSPK'])->name('pemesanan.spk.download');

//     Route::post('/pemesanan/{id}/konfirmasi/{bagian}', [PemesananController::class, 'konfirmasi'])
//         ->name('pemesanan.konfirmasi');

//     // Route untuk selesaikan pemesanan
//     Route::patch('/pemesanan/{id}/selesaikan', [PemesananController::class, 'selesaikan'])
//         ->name('pemesanan.selesaikan');
// });

//==========================================================================================



//==========================================================================================
//                                      PPIC
//==========================================================================================
Route::prefix('ppic')->middleware(['auth', 'role:ppic'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'indexForPPIC'])->name('ppic.dashboard');

    Route::get('stokproduk', [BarangController::class, 'stokProduk'])->name('ppic.stokproduk');
    Route::get('/stok/create', [BarangController::class, 'tambahStok'])->name('ppic.stok.tambahstok');
    // Stok Gudang QC Gagal - PPIC
    Route::get('/gudang-qc-gagal', [ProgresProduksiController::class, 'gudangQcIndex'])->name('ppic.gudang_qc_gagal');
    Route::put('/gudang-qc-gagal/update/{id}', [ProgresProduksiController::class, 'updateGudangQc'])->name('ppic.gudang_qc_gagal.update');
    // Also accept POST for update to be defensive if method spoofing fails
    Route::post('/gudang-qc-gagal/update/{id}', [ProgresProduksiController::class, 'updateGudangQc']);
    // Fallback: accept PUT to base path and forward to update controller using id from request body
    Route::put('/gudang-qc-gagal', function (Request $request) {
        $id = $request->input('gudang_qc_gagal_id') ?? $request->input('id');
        if (!$id) {
            return redirect()->back()->with('error', 'ID pemesanan gudang QC tidak ditemukan untuk update.');
        }
        return app(ProgresProduksiController::class)->updateGudangQc($request, $id);
    });
    Route::delete('/gudang-qc-gagal/destroy/{id}', [ProgresProduksiController::class, 'destroyGudangQc'])->name('ppic.gudang_qc_gagal.destroy');
    Route::post('/stok/store', [BarangController::class, 'storeStok'])->name('ppic.stok.store');
    Route::get('/stok/{proses}/{id}/edit', [BarangController::class, 'editStok'])->name('ppic.stok.edit');
    // Route::put('/stok/{prosesLama}/{id}', [BarangController::class, 'updateStok'])->name('ppic.stok.update');
    // Route::delete('/stok/{proses}/{id}', [BarangController::class, 'hapusStok'])->name('ppic.stok.hapus');

    //Progres Produksi
    Route::get('/ppic/progres', [ProgresProduksiController::class, 'index'])->name('ppic.progres.index');
    Route::post('/ppic/progres/pindah', [ProgresProduksiController::class, 'pindahProses'])->name('ppic.progres.pindah');
    Route::post('/ppic/progres/qcgagal', [ProgresProduksiController::class, 'qcGagal'])->name('ppic.progres.qcgagal');
    Route::delete('/ppic/progres/{id}', [ProgresProduksiController::class, 'hapusProgres'])->name('ppic.progres.delete');
    Route::get('/ppic/progres/{id}/detail', [ProgresProduksiController::class, 'detail'])->name('ppic.progres.detail');

    // Pelacakan proses pemenuhan penyelesaian pemesanan
    Route::get('/progres-pemesanan', [ProgresProduksiController::class, 'ProsesPenyelesaianPemesanan'])->name('ppic.progres.pemesanan');

    // Gudang QC Gagal
    Route::get('ppic/qc-gagal/data', [ProgresProduksiController::class, 'indexQcGagal'])->name('ppic.qcGagal.data');
    Route::get('ppic/qc-gagal', function () {
        return view('ppic.gudang_qc_gagal');
    })->name('ppic.qcGagal.view');
    Route::get('ppic/qc-gagal/pemesanan-list', [GudangQCController::class, 'getPemesananList'])->name('ppic.qcGagal.pemesananList');
    Route::post('ppic/qc-gagal/pindahkan', [GudangQCController::class, 'pindahkanKePemesanan'])->name('ppic.qcGagal.pindahkan');
    Route::get('ppic/qc-gagal/create', [GudangQCController::class, 'create'])->name('ppic.qcGagal.create');
    Route::post('ppic/qc-gagal/store', [GudangQCController::class, 'store'])->name('ppic.qcGagal.store');
    Route::get('ppic/qc-gagal/edit/{id}', [GudangQCController::class, 'edit'])->name('ppic.qcGagal.edit');
    Route::put('ppic/qc-gagal/update/{id}', [GudangQCController::class, 'update'])->name('ppic.qcGagal.update');
    Route::delete('ppic/qc-gagal/delete/{id}', [GudangQCController::class, 'destroy'])->name('ppic.qcGagal.delete');
    

    // Pemesanan
    Route::get('/pemesanan', [PemesananController::class, 'index'])->name('ppic.pemesanan.index');
    Route::get('/pemesanan/{id}/detail', [PemesananController::class, 'show'])->name('ppic.pemesanan.show');
    Route::get('/pemesanan/{id}/spk-download', [PemesananController::class, 'downloadSPK'])->name('ppic.pemesanan.downloadspk');
    Route::post('/pemesanan/{id}/konfirmasi/{bagian}', [PemesananController::class, 'konfirmasi'])->name('ppic.pemesanan.konfirmasi');
    // Route untuk selesaikan pemesanan
    Route::patch('/pemesanan/{id}/selesaikan', [PemesananController::class, 'selesaikan'])->name('ppic.pemesanan.selesaikan');

    // Tombol Pindah ke Gudang di Produksi
    Route::post('/produksi/pindah-ke-gudang/{id}', [ProgresProduksiController::class, 'pindahKeGudang'])->name('produksi.pindahKeGudang');

    // Bahan Pendukung
    Route::get('/bahanpendukung', [BahanPendukungController::class, 'index'])->name('ppic.daftarbahanpendukung');
    Route::get('/bahanpendukung/edit/{id}', [BahanPendukungController::class, 'edit'])->name('ppic.daftarbahanpendukung.edit');
    Route::put('/bahanpendukung/update/{id}', [BahanPendukungController::class, 'update'])->name('ppic.daftarbahanpendukung.update');
    Route::delete('/bahanpendukung/destroy/{id}', [BahanPendukungController::class, 'destroy'])->name('ppic.daftarbahanpendukung.destroy');

    // Order Bahan Pendukung
    Route::get('/daftarorderbahanpendukung',[PembelianBahanPendukungController::class,'index'])->name('ppic.daftarorderbahanpendukung');
    Route::get('/daftarorderbahanpendukung/create',[PembelianBahanPendukungController::class,'create'])->name('ppic.daftarorderbahanpendukung.create');
    Route::get('/daftarorderbahanpendukung/edit/{id}',[PembelianBahanPendukungController::class,'edit'])->name('ppic.daftarorderbahanpendukung.edit');
    Route::put('/daftarorderbahanpendukung/update/{id}',[PembelianBahanPendukungController::class,'update'])->name('ppic.daftarorderbahanpendukung.update');
    Route::post('/daftarorderbahanpendukung/store',[PembelianBahanPendukungController::class,'store'])->name('ppic.daftarorderbahanpendukung.store');

    //Barang dan Stok
    Route::get('/daftarbarang', [BarangController::class, 'index'])->name('ppic.daftarbarang');
    Route::get('/daftarbarang/edit/{id}', [BarangController::class, 'edit'])->name('ppic.barang.edit');
    Route::put('/daftarbarang/update/{id}', [BarangController::class, 'update'])->name('ppic.barang.update');

    // Export Barang ke Excel
    Route::get('/export', [BarangController::class, 'export'])->name('ppic.barang.export');

    // Masukkan ke Packing
    Route::get('/get-pemesanan-by-barang/{barang_id}', [BarangController::class, 'getPemesananByBarang'])->name('ppic.getPemesananByBarang');
    Route::post('/packing/store', [BarangController::class, 'storePacking'])->name('ppic.packing.store');
    Route::get('/get-bahan-packing/{barang_id}', [BarangController::class, 'getBahanPacking'])->name('ppic.getBahanPacking');

    // Daftar Packing
    Route::get('/daftarpacking', [PackingController::class, 'index'])->name('ppic.packing.index');
    Route::get('/daftarpacking/detail/{id}', [PackingController::class, 'detail'])->name('ppic.packing.detail');
    Route::put('/daftarpacking/update-status/{id}', [PackingController::class, 'updateStatus'])->name('ppic.packing.updateStatus');
    Route::delete('/daftarpacking/delete/{id}', [PackingController::class, 'delete'])->name('ppic.packing.delete');

    // Kebutuhan Kardus
    Route::get('/kebutuhan-kardus', [PembelianBahanPendukungController::class, 'jumlahKardusBahanPendukung'])->name('ppic.kebutuhan_kardus');
});
//==========================================================================================



//==========================================================================================
//                                       KEPROD
//==========================================================================================
Route::prefix('keprod')->middleware(['auth', 'role:keprod'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'indexForKeprod'])->name('keprod.dashboard');

    // Pemesanan
    Route::get('/pemesanan', [PemesananController::class, 'index'])->name('keprod.pemesanan.index');
    Route::get('/pemesanan/{id}/detail', [PemesananController::class, 'show'])->name('keprod.pemesanan.show');
    Route::get('/pemesanan/{id}/spk-download', [PemesananController::class, 'downloadSPK'])->name('keprod.pemesanan.downloadspk');
    Route::post('/pemesanan/{id}/konfirmasi/{bagian}', [PemesananController::class, 'konfirmasi'])->name('keprod.pemesanan.konfirmasi');

    // Progres Produksi
    Route::get('/progres', [ProgresProduksiController::class, 'index'])->name('keprod.progres.index');
    Route::get('/progres/{id}/detail', [ProgresProduksiController::class, 'detail'])->name('keprod.progres.detail');

    // Produksi
    Route::get('/produksi/create', [ProgresProduksiController::class, 'createProduksi'])->name('keprod.produksi.create');
    Route::post('/produksi/store', [ProgresProduksiController::class, 'storeProduksi'])->name('keprod.produksi.store');

    // Pengiriman
    Route::get('/pengiriman', [PengirimanController::class, 'index'])->name('keprod.pengiriman.index');
    Route::get('/pengiriman/create/{proses}', [PengirimanController::class, 'create'])->name('keprod.pengiriman.create');
    Route::post('/pengiriman/store', [PengirimanController::class, 'store'])->name('keprod.pengiriman.store');
    Route::get('/bahan-pendukung/{produksi}/{sub_proses}', [PengirimanController::class, 'getBahanPendukung']);
    Route::get('/pengiriman/subproses/{produksi_id}', [PengirimanController::class, 'getSubProsesSaatIni']);

    // Pengiriman Internal
    Route::get('/pengiriman-internal', [PengirimanInternalController::class, 'create'])->name('keprod.pengirimanInternal.index');
    Route::post('/pengiriman-internal/store', [PengirimanInternalController::class, 'store'])->name('keprod.pengirimanInternal.store');
    Route::get('/bahan-pendukung-internal/{produksi}/{sub_proses}', [PengirimanInternalController::class, 'getBahanPendukung']);
    Route::get('/pengiriman-internal/subproses/{produksi_id}', [PengirimanInternalController::class, 'getSubProsesSaatIni']);

    // Pengambilan
    Route::get('/pengambilan', [PengambilanController::class, 'index'])->name('keprod.pengambilan.index');
    Route::get('/pengambilan/create', [PengambilanController::class, 'create'])->name('keprod.pengambilan.create');
    Route::post('/pengambilan/store', [PengambilanController::class, 'store'])->name('keprod.pengambilan.store');

    // Pengambilan Internal
    Route::get('/pengambilan-internal/create', [PengambilanInternalController::class, 'create'])->name('keprod.pengambilanInternal.create');
    Route::post('/pengambilan-internal/store', [PengambilanInternalController::class, 'store'])->name('keprod.pengambilanInternal.store');

    // History Pemindahan Barang
    Route::get('/history-pemindahan', [ProgresProduksiController::class, 'historyPemindahan'])->name('keprod.historyPemindahan');

    // List Pengerjaan Supplier
    Route::get('/supplier-produksi', [SupplierProduksiController::class, 'listPengerjaan'])->name('keprod.listPengerjaanSupplier');
});
//==========================================================================================


//==========================================================================================
//                                       SUPIR
//==========================================================================================
Route::prefix('supir')->middleware(['auth', 'role:supir'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'indexForSupir'])->name('supir.dashboard');

    // 🔹 Pengiriman Barang
    Route::get('/pengiriman', [PengirimanController::class, 'indexForSupir'])->name('supir.pengiriman.index');
    Route::post('/pengiriman/{id}/mulai', [PengirimanController::class, 'mulai'])->name('supir.pengiriman.mulai');
    Route::get('/pengiriman/{id}/perjalanan', [PengirimanController::class, 'perjalanan'])->name('supir.pengiriman.perjalanan');
    Route::post('/pengiriman/{id}/sampai', [PengirimanController::class, 'konfirmasiSampai'])->name('supir.pengiriman.sampai');
    Route::post('/pengiriman/{id}/selesai', [PengirimanController::class, 'selesai'])->name('supir.pengiriman.selesai');

    // Pengambilan Barang
    Route::get('/pengambilan', [PengambilanController::class, 'indexForSupir'])->name('supir.pengambilan.index');
    Route::get('/pengambilan/{id}/form-konfirmasi', [PengambilanController::class, 'formKonfirmasi'])->name('supir.pengambilan.form-konfirmasi');
    Route::post('/pengambilan/{id}/konfirmasi', [PengambilanController::class, 'konfirmasiSupir'])->name('supir.pengambilan.konfirmasi');
    Route::post('/pengambilan/{id}/mulai', [PengambilanController::class, 'mulai'])->name('supir.pengambilan.mulai');
    Route::get('/pengambilan/{id}/perjalanan', [PengambilanController::class, 'perjalanan'])->name('supir.pengambilan.perjalanan');
    Route::post('/pengambilan/{id}/selesai', [PengambilanController::class, 'selesai'])->name('supir.pengambilan.selesai');

});
//==========================================================================================



//==========================================================================================
//                                 DIREKTUR
//==========================================================================================
Route::prefix('direktur')->middleware(['auth', 'role:direktur'])->group(function () {
    Route::get('/dashboard', function () {
        return view('direktur.dashboard');
    })->name('direktur.dashboard');

});
//==========================================================================================


//==========================================================================================
//                                 SUPPLIER
//==========================================================================================
Route::prefix('supplier')->middleware(['auth', 'role:supplier'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'indexForSupplier'])->name('supplier.dashboard');

    // 🔹 Barang Masuk
    Route::get('/pengiriman', [PengirimanController::class, 'indexForSupplier'])->name('supplier.pengiriman.index');
    Route::post('/pengiriman/{id}/terima', [PengirimanController::class, 'terima'])->name('supplier.pengiriman.terima');

    // 🔹 Produksi
    Route::get('/produksi', [SupplierProduksiController::class, 'index'])->name('supplier.produksi.index');
    Route::put('/produksi/{id}', [SupplierProduksiController::class, 'update'])->name('supplier.produksi.update');
});
//==========================================================================================




//==========================================================================================
//                                 QUALITY CONTROL
//==========================================================================================
Route::prefix('qc')->middleware(['auth', 'role:qc'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'indexForQC'])->name('qc.dashboard');

    // Pemesanan
    Route::get('/pemesanan', [PemesananController::class, 'index'])->name('qc.pemesanan.index');
    Route::get('/pemesanan/{id}/detail', [PemesananController::class, 'show'])->name('qc.pemesanan.show');
    Route::get('/pemesanan/{id}/spk-download', [PemesananController::class, 'downloadSPK'])->name('qc.pemesanan.downloadspk');
    Route::post('/pemesanan/{id}/konfirmasi/{bagian}', [PemesananController::class, 'konfirmasi'])->name('qc.pemesanan.konfirmasi');

    // Progres Produksi
    Route::get('/progres', [ProgresProduksiController::class, 'index'])->name('qc.progres.index');
    Route::get('/progres/{id}/detail', [ProgresProduksiController::class, 'detail'])->name('qc.progres.detail');
    Route::post('/progres/qcgagal', [ProgresProduksiController::class, 'qcGagal'])->name('qc.progres.qcgagal');

    // Pengiriman
    Route::get('/pengiriman', [PengirimanController::class, 'index'])->name('qc.pengiriman.index');
    Route::put('/pengiriman/update-status/{id}', [PengirimanController::class, 'updateStatus'])->name('qc.pengiriman.updateStatus');

    // 🔹 Produksi Supplier
    Route::get('/produksi', [SupplierProduksiController::class, 'indexForQc'])->name('qc.produksi.index');
    Route::put('/nilai-kualitas/{detail_pengiriman_id}', [SupplierProduksiController::class, 'nilaiKualitas'])->name('qc.nilai-kualitas');
    Route::put('/reject-barang/{id}', [SupplierProduksiController::class, 'rejectBarang'])->name('qc.reject');

    // Pengambilan
    Route::get('/pengambilan', [PengambilanController::class, 'indexForQc'])->name('qc.pengambilan.index');
    Route::post('/pengambilan/update-status/{id}', [PengambilanController::class, 'konfirmasi'])->name('qc.pengambilan.konfirmasi');
});
//==========================================================================================



//==========================================================================================
//                                      PURCHASING
//==========================================================================================
Route::prefix('purchasing')->middleware(['auth', 'role:purchasing'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'indexForPurchasing'])->name('purchasing.dashboard');

    // Daftar Order
    Route::get('/daftarorderbahanpendukung',[PembelianBahanPendukungController::class,'index'])->name('purchasing.daftarorderbahanpendukung');
    Route::put('/daftarorderbahanpendukung/konfirmasi-pembelian/{id}',[PembelianBahanPendukungController::class,'konfirmasiPembelian'])->name('purchasing.orderbahanpendukung.konfirmasi-pembelian');
    
    // Bahan Pendukung
    Route::get('/bahanpendukung', [BahanPendukungController::class, 'index'])->name('purchasing.daftarbahanpendukung');
    Route::post('/bahanpendukung/store', [BahanPendukungController::class, 'store'])->name('purchasing.bahanpendukung.store');
    Route::get('/bahanpendukung/edit/{id}', [BahanPendukungController::class, 'edit'])->name('purchasing.daftarbahanpendukung.edit');
    Route::put('/bahanpendukung/update/{id}', [BahanPendukungController::class, 'update'])->name('purchasing.daftarbahanpendukung.update');
    Route::get('/bahanpendukung/search', [BahanPendukungController::class, 'search'])->name('purchasing.daftarbahanpendukung.search');

    //Pembayaran Supplier
    Route::get('/daftarPembayaran',[PengambilanController::class,'DaftarPembayaran'])->name('purchasing.pembayaran.index');
    Route::put('/daftarPembayaran/konfirmasi-bayar/{id}',[PengambilanController::class,'KonfirmasiPembayaran'])->name('purchasing.pembayaran.konfirmasibayar');
});
//==========================================================================================



//==========================================================================================
//                                        GUDANG
//==========================================================================================
Route::prefix('gudang')->middleware(['auth', 'role:gudang'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'indexForGudang'])->name('gudang.dashboard');

    Route::get('/daftarorderbahanpendukung',[PembelianBahanPendukungController::class,'index'])->name('gudang.daftarorderbahanpendukung');
    Route::put('/daftarorderbahanpendukung/konfirmasi-sampai/{id}',[PembelianBahanPendukungController::class,'konfirmasiSampai'])->name('gudang.orderbahanpendukung.konfirmasi-sampai');
    Route::get('/bahanpendukung', [BahanPendukungController::class, 'index'])->name('gudang.daftarbahanpendukung');
    Route::get('/bahanpendukung/create', [BahanPendukungController::class, 'create'])->name('gudang.bahanpendukung.create');
    Route::post('/bahanpendukung/store', [BahanPendukungController::class, 'store'])->name('gudang.bahanpendukung.store');
    Route::get('/bahanpendukung/edit/{id}', [BahanPendukungController::class, 'edit'])->name('gudang.daftarbahanpendukung.edit');
    Route::put('/bahanpendukung/update/{id}', [BahanPendukungController::class, 'update'])->name('gudang.daftarbahanpendukung.update');
    Route::delete('/bahanpendukung/destroy/{id}', [BahanPendukungController::class, 'destroy'])->name('gudang.daftarbahanpendukung.destroy');
    Route::get('/bahanpendukung/search', [BahanPendukungController::class, 'search'])->name('gudang.daftarbahanpendukung.search');

    // Pemesanan
    Route::get('/pemesanan', [PemesananController::class, 'index'])->name('gudang.pemesanan.index');
    Route::get('/pemesanan/{id}/detail', [PemesananController::class, 'show'])->name('gudang.pemesanan.show');
    Route::get('/pemesanan/{id}/spk-download', [PemesananController::class, 'downloadSPK'])->name('gudang.pemesanan.downloadspk');
    Route::post('/pemesanan/{id}/konfirmasi/{bagian}', [PemesananController::class, 'konfirmasi'])->name('gudang.pemesanan.konfirmasi');

    // Pengiriman
    Route::get('/pengiriman', [PengirimanController::class, 'indexGudang'])->name('gudang.pengiriman.index');
    Route::get('/pengiriman/detail/{id}', [PengirimanController::class, 'bahanDetail'])->name('gudang.pengiriman.detail');
    // Route untuk konfirmasi kebutuhan bahan pendukung
    Route::post('/pengiriman/konfirmasi/{detail_pengiriman_id}', [PengirimanController::class, 'konfirmasiKebutuhan'])->name('gudang.pengiriman.konfirmasi');

    // Packing
    Route::get('/packing', [PackingController::class, 'index'])->name('gudang.packing.index');
    Route::get('/packing/{packing_id}/detail', [PackingController::class, 'getPackingDetail'])->name('gudang.packing.detail');
    Route::post('/packing/{packing_id}/confirm', [PackingController::class, 'confirmPacking'])->name('gudang.packing.confirm');
});
//==========================================================================================




//==========================================================================================
//                                      PACKING
//==========================================================================================
Route::prefix('packing')->middleware(['auth', 'role:packing'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'indexForPacking'])->name('packing.dashboard');

    // Daftar Packing
    Route::get('/daftarpacking', [PackingController::class, 'index'])->name('packing.packing.index');
    Route::get('/daftarpacking/detail/{id}', [PackingController::class, 'detail'])->name('packing.packing.detail');
    Route::put('/daftarpacking/update-status/{id}', [PackingController::class, 'updateStatus'])->name('packing.packing.updateStatus');
    Route::delete('/daftarpacking/delete/{id}', [PackingController::class, 'delete'])->name('packing.packing.delete');
});
//==========================================================================================

require __DIR__.'/auth.php';