<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengiriman;
use App\Models\DetailPengiriman;
use App\Models\PemesananBarang;
use App\Models\Kendaraan;
use App\Models\BahanPendukung;
use App\Models\User;
use App\Models\SubProses;
use App\Models\ProgresProduksi;
use App\Models\Produksi;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;



class PengirimanController extends Controller
{
    // 🧭 Tampilkan semua data pengiriman
   public function index(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;

        // Relasi yang selalu dipakai
        $withRelations = [
            'detailPengiriman.supplier', 
            'detailPengiriman.produksi.barang',
            'detailPengiriman.subProses',
            'supir', 
            'qc', 
            'detailPengiriman.produksi.progresProduksi.subProses.proses', 
            'kendaraan'
        ];

        // Ambil filter dari request
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        if ($role === 'keprod') {
            // Keprod: lihat semua pengiriman dengan filter
            $query = Pengiriman::with($withRelations);

            // Filter berdasarkan bulan
            if ($bulan) {
                $query->whereMonth('tanggal_pengiriman', $bulan);
            }

            // Filter berdasarkan tahun
            if ($tahun) {
                $query->whereYear('tanggal_pengiriman', $tahun);
            }

            $pengiriman = $query->orderBy('tanggal_pengiriman', 'desc')->get();

            // Data untuk dropdown filter
            $tahunList = Pengiriman::whereNotNull('tanggal_pengiriman')
                ->get(['tanggal_pengiriman'])
                ->map(function($item) {
                    return date('Y', strtotime($item->tanggal_pengiriman));
                })
                ->unique()
                ->sortDesc()
                ->values();

            return view('keprod.pengiriman', compact('pengiriman', 'tahunList', 'bulan', 'tahun'));

        } elseif ($role === 'qc') {
            // QC: hanya lihat pengiriman miliknya dengan filter
            $query = Pengiriman::with($withRelations)
                ->whereHas('qc', function ($q) use ($user) {
                    $q->where('id', $user->id);
                });

            // Filter berdasarkan bulan
            if ($bulan) {
                $query->whereMonth('tanggal_pengiriman', $bulan);
            }

            // Filter berdasarkan tahun
            if ($tahun) {
                $query->whereYear('tanggal_pengiriman', $tahun);
            }

            $pengiriman = $query->orderBy('tanggal_pengiriman', 'desc')
                        ->where('tipe_pengiriman', 'eksternal')
                        ->get();

            // Data untuk dropdown filter
            $tahunList = Pengiriman::whereNotNull('tanggal_pengiriman')
                ->whereHas('qc', function ($q) use ($user) {
                    $q->where('id', $user->id);
                })
                ->get(['tanggal_pengiriman'])
                ->map(function($item) {
                    return date('Y', strtotime($item->tanggal_pengiriman));
                })
                ->unique()
                ->sortDesc()
                ->values();

            return view('qc.pengiriman', compact('pengiriman', 'tahunList', 'bulan', 'tahun'));
        }

        // Jika role lain mencoba akses halaman ini
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }


    // 📝 Tampilkan form tambah pengiriman
    // public function create()
    // {
    //     $pemesanan = PemesananBarang::with([
    //         'pemesanan.pembeli',
    //         'barang',
    //         'progresProduksi.subProses'
    //     ])
    //     ->withSum('detailPengiriman', 'jumlah_pengiriman')
    //     ->get()
    //     ->filter(function ($item) {
    //         // Ambil total pengiriman, jika null, jadikan 0
    //         $totalDikirim = $item->detail_pengiriman_sum_jumlah_pengiriman ?? 0;
    //         return $totalDikirim < $item->jumlah_pemesanan;
    //     })
    //     ->values(); // reset key biar rapih

    //     $kendaraan = Kendaraan::all();
    //     $supplier = User::where('role', 'supplier')->get();
    //     $supir = User::where('role', 'supir')->get();
    //     $qc = User::where('role', 'qc')->get();
    //     $subProsesList = SubProses::all();

    //     return view('keprod.createPengiriman', compact('pemesanan', 'kendaraan', 'supplier', 'supir', 'qc', 'subProsesList'));
    // }
    public function create($proses)
    {
        $produksi = Produksi::with(['barang', 'progresProduksi'])
            ->get()
            ->map(function ($item) {
                // Hitung total yang sudah terdistribusi ke semua sub proses
                $totalTerdistribusi = $item->progresProduksi->sum('jumlah');

                // Sisa produksi = yang belum masuk ke proses manapun
                $item->sisa_pengiriman = $item->jumlah_produksi - $totalTerdistribusi;

                return $item;
            });

        $subProsesTujuan = SubProses::where('proses_id', $proses)->orderBy('urutan')->get();

        $supplier = User::where('role', 'supplier')->where('tipe_supplier', 'eksternal')->get();
        $qc = User::where('role', 'qc')->get();
        $supir = User::where('role', 'supir')->get();

        $kendaraan = Kendaraan::all();

        return view('keprod.createPengiriman', compact(
            'produksi',
            'subProsesTujuan',
            'supplier',
            'qc',
            'supir',
            'kendaraan',
            'proses'
        ));
    }

    public function getSubProsesSaatIni($produksi_id)
    {
        $subproses = ProgresProduksi::with('subProses')
            ->where('produksi_id', $produksi_id)
            ->where('sdh_selesai', '>', 0)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->sub_proses_id,
                    'nama' => $item->subProses->nama_sub_proses,
                    'jumlah' => $item->sdh_selesai
                ];
            });

        return response()->json($subproses);
    }






    // public function getBahanPendukung($pemesanan_barang_id)
    // {
    //     $pemesanan = PemesananBarang::with([
    //         'barang.bahanPendukungBarang.bahanPendukung',
    //         'detailPengiriman',
    //         'progresProduksi.subProses'
    //     ])->findOrFail($pemesanan_barang_id);

    //     // Jumlah pesanan
    //     $jumlahPesanan = $pemesanan->jumlah_pemesanan;

    //     // Total pengiriman yang sudah dilakukan
    //     $totalDikirim = $pemesanan->detailPengiriman->sum('jumlah_pengiriman');

    //     // Sisa pesanan yang belum terkirim
    //     $sisaPesanan = $jumlahPesanan - $totalDikirim;

    //     // Ambil progres produksi sesuai pemesanan_barang_id
    //     $progresProduksi = $pemesanan->progresProduksi->map(function ($progres) {
    //         return [
    //             'sub_proses' => $progres->subProses->nama_sub_proses ?? '-',
    //             'nama_progres' => $progres->pemesananBarang->barang->nama_barang ?? '-',
    //             'jumlah' => $progres->jumlah,
    //             'tanggal_update' => $progres->updated_at ? $progres->updated_at->format('d-m-Y') : '-',
    //             'sub_proses_id' => $progress->subProses->sub_proses_id ?? null,
    //             'urutan' => $progress->subProses->urutan ?? null,
    //         ];
    //     });

    //     // Bahan pendukung
    //     $bahanPendukung = $pemesanan->barang->bahanPendukungBarang->map(function ($item) {
    //         return [
    //             'nama_bahan' => $item->bahanPendukung->nama_bahan_pendukung,
    //             'jumlah_per_barang' => $item->jumlah_bahan_pendukung,
    //             'satuan' => $item->bahanPendukung->satuan ?? 'unit'
    //         ];
    //     });

    //     return response()->json([
    //         'barang' => $pemesanan->barang->nama_barang,
    //         'jumlah_pesanan' => $jumlahPesanan,
    //         'total_dikirim' => $totalDikirim,
    //         'sisa_pesanan' => $sisaPesanan,
    //         'bahan_pendukung' => $bahanPendukung,
    //         'progres_produksi' => $progresProduksi
    //     ]);
    // }

    // public function getBahanPendukung($produksi_id, $proses)
    // {
    //     $produksi = Produksi::with([
    //         'barang.bahanPendukungBarang.bahanPendukung',
    //         'barang.bahanPendukungBarang.proses',
    //         'detailPengiriman',
    //         'progresProduksi.subProses'
    //     ])->findOrFail($produksi_id);

    //     $jumlahProduksi = $produksi->jumlah_produksi;
    //     $totalDikirim = $produksi->detailPengiriman->sum('jumlah_pengiriman');
    //     $sisaProduksi = $jumlahProduksi - $totalDikirim;

    //     // Format progres
    //     $progresProduksi = $produksi->progresProduksi->map(function ($progres) {
    //         return [
    //             'id' => $progres->progres_produksi_id,
    //             'sub_proses' => $progres->subProses->nama_sub_proses ?? '-',
    //             'jumlah' => $progres->jumlah,
    //             'tanggal_update' => $progres->updated_at ? $progres->updated_at->format('d-m-Y') : '-',
    //             'sub_proses_id' => $progres->subProses->sub_proses_id ?? null,
    //             'urutan' => $progres->subProses->urutan ?? null,
    //         ];
    //     });

    //     $bahanPendukung = $produksi->barang->bahanPendukungBarang
    //         ->filter(fn($row) => $row->proses->proses_id == $proses)
    //         ->map(fn($row) => [
    //             'nama_bahan' => $row->bahanPendukung->nama_bahan_pendukung,
    //             'jumlah_per_barang' => $row->jumlah_bahan_pendukung,
    //             'satuan' => $row->bahanPendukung->satuan,
    //         ])->values();

    //     return response()->json([
    //         'barang' => $produksi->barang->nama_barang,
    //         'jumlah_produksi' => $jumlahProduksi,
    //         'total_dikirim' => $totalDikirim,
    //         'sisa_produksi' => $sisaProduksi,
    //         'bahan_pendukung' => $bahanPendukung,
    //         'progres_produksi' => $progresProduksi,
    //     ]);
    // }

    public function store(Request $request)
    {
        $rules = [
            'produksi_id' => 'required|array',
            'produksi_id.*' => 'required|exists:produksi,produksi_id',

            'sub_proses_tujuan' => 'required|array',
            'sub_proses_tujuan.*' => 'required|exists:sub_proses,sub_proses_id',

            'jumlah_pengiriman' => 'required|array',
            'jumlah_pengiriman.*' => 'required|numeric|min:1',

            'supplier_id' => 'required|array',
            'supplier_id.*' => 'required|exists:users,id',

            'qc_id' => 'required|exists:users,id',
            'supir_id' => 'required|exists:users,id',
            'kendaraan_id' => 'required|exists:kendaraan,kendaraan_id',
            'tanggal_pengiriman' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_pengiriman',
        ];

        $messages = [
            'produksi_id.required' => 'Setidaknya ada 1 item pengiriman.',
            'produksi_id.*.required' => 'Pilihan produksi pada setiap item wajib diisi.',
            'produksi_id.*.exists' => 'Pilihan produksi tidak valid.',

            'sub_proses_tujuan.*.required' => 'Sub proses tujuan pada setiap item wajib dipilih.',
            'sub_proses_tujuan.*.exists' => 'Sub proses tujuan tidak valid.',

            'jumlah_pengiriman.*.required' => 'Jumlah pengiriman pada setiap item wajib diisi.',
            'jumlah_pengiriman.*.numeric' => 'Jumlah pengiriman harus berupa angka.',
            'jumlah_pengiriman.*.min' => 'Jumlah pengiriman minimal 1.',

            'supplier_id.*.required' => 'Supplier pada setiap item wajib dipilih.',
            'supplier_id.*.exists' => 'Supplier tidak valid.',

            'qc_id.required' => 'QC wajib dipilih.',
            'qc_id.exists' => 'QC tidak valid.',
            'supir_id.required' => 'Supir wajib dipilih.',
            'supir_id.exists' => 'Supir tidak valid.',
            'kendaraan_id.required' => 'Kendaraan wajib dipilih.',
            'kendaraan_id.exists' => 'Kendaraan tidak valid.',

            'tanggal_pengiriman.required' => 'Tanggal pengiriman wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal pengiriman.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            // ========================================
            // VALIDASI SEMUA BAHAN PENDUKUNG DULU
            // ========================================
            $errorMessages = [];

            foreach ($request->produksi_id as $i => $produksiID) {
                $subProsesSaatIni = $request->sub_proses_saat_ini[$i] ?? null;
                $subProsesTujuan = $request->sub_proses_tujuan[$i];
                $jumlah = $request->jumlah_pengiriman[$i];
                $butuhBp = $request->butuh_bp[$i] ?? 0;

                $produksi = Produksi::with(['barang.bahanPendukungBarang.bahanPendukung'])
                    ->findOrFail($produksiID);

                // ========================================
                // VALIDASI STOK PROGRES
                // ========================================
                $maxJumlah = 0;
                
                // Jika ada sub proses saat ini, validasi dari stok sub proses tersebut
                if ($subProsesSaatIni) {
                    $progresSaatIni = ProgresProduksi::where('produksi_id', $produksiID)
                        ->where('sub_proses_id', $subProsesSaatIni)
                        ->first();

                    if (!$progresSaatIni || $progresSaatIni->sdh_selesai <= 0) {
                        $errorMessages[] = "Item " . ($i + 1) . " : Sub proses saat ini tidak memiliki stok yang sudah selesai!";
                        continue;
                    }

                    // Validasi berdasarkan stok yang SUDAH SELESAI (bukan dlm_proses)
                    $maxJumlah = $progresSaatIni->sdh_selesai;
                } 
                // Jika tidak ada sub proses saat ini (baru mulai produksi)
                else {
                    // Hitung total yang sudah didistribusikan ke semua sub proses
                    $totalTerdistribusi = ProgresProduksi::where('produksi_id', $produksiID)->sum('jumlah');
                    $sisaProduksi = $produksi->jumlah_produksi - $totalTerdistribusi;

                    if ($sisaProduksi <= 0) {
                        $errorMessages[] = "Item " . ($i + 1) . " : Semua produksi sudah terdistribusi!";
                        continue;
                    }

                    $maxJumlah = $sisaProduksi;
                }

                if ($jumlah > $maxJumlah) {
                    $errorMessages[] = "Item " . ($i + 1) . " : Jumlah melebihi stok maksimal ($maxJumlah)";
                    continue;
                }

                // ========================================
                // CEK BAHAN PENDUKUNG (JIKA BUTUH)
                // AKUMULASI DARI PROSES SAAT INI SAMPAI TUJUAN
                // ========================================
                if ($butuhBp == 1) {
                    $subProsesTujuanObj = SubProses::find($subProsesTujuan);
                    
                    // Tentukan range sub_proses_id
                    $subProsesSaatIniObj = $subProsesSaatIni ? SubProses::find($subProsesSaatIni) : null;
                    
                    // Jika ada proses saat ini, mulai dari proses berikutnya
                    // Jika tidak ada (produksi baru), mulai dari proses 1
                    $startSubProsesId = $subProsesSaatIniObj ? $subProsesSaatIniObj->sub_proses_id + 1 : 1;
                    $endSubProsesId = $subProsesTujuanObj->sub_proses_id;

                    // Ambil semua bahan pendukung dalam range tersebut
                    $bahanPendukung = $produksi->barang->bahanPendukungBarang
                        ->whereBetween('sub_proses_id', [$startSubProsesId, $endSubProsesId]);

                    // Kelompokkan per bahan pendukung (karena bisa sama di beda sub proses)
                    $kebutuhanPerBahan = [];
                    
                    foreach ($bahanPendukung as $bpb) {
                        $bpId = $bpb->bahan_pendukung_id;
                        $butuh = $bpb->jumlah_bahan_pendukung * $jumlah;
                        
                        if (!isset($kebutuhanPerBahan[$bpId])) {
                            $kebutuhanPerBahan[$bpId] = [
                                'nama' => $bpb->bahanPendukung->nama_bahan_pendukung,
                                'stok' => $bpb->bahanPendukung->stok_bahan_pendukung,
                                'total_butuh' => 0
                            ];
                        }
                        
                        $kebutuhanPerBahan[$bpId]['total_butuh'] += $butuh;
                    }

                    // Validasi setiap bahan
                    foreach ($kebutuhanPerBahan as $bpId => $data) {
                        if ($data['total_butuh'] > $data['stok']) {
                            $errorMessages[] = "Item #" . ($i + 1) . ": Stok {$data['nama']} tidak cukup! (Butuh: {$data['total_butuh']}, Tersedia: {$data['stok']})";
                        }
                    }
                }
            }

            // ========================================
            // JIKA ADA ERROR, LANGSUNG ROLLBACK
            // ========================================
            if (!empty($errorMessages)) {
                DB::rollBack();
                return back()->withErrors(['bahan_pendukung' => implode('<br>', $errorMessages)])
                    ->withInput();
            }

            // ========================================
            // CEK APAKAH ADA BARANG YANG BUTUH BP
            // ========================================
            $adaYangButuhBP = false;
            foreach ($request->butuh_bp ?? [] as $butuhBp) {
                if ($butuhBp == 1) {
                    $adaYangButuhBP = true;
                    break;
                }
            }

            // ========================================
            // TENTUKAN STATUS PENGIRIMAN
            // ========================================
            // Jika ada barang yang butuh BP → "Menunggu Gudang"
            // Jika tidak ada yang butuh BP → "Menunggu QC"
            $statusPengiriman = $adaYangButuhBP ? 'Menunggu Gudang' : 'Menunggu QC';

            // ========================================
            // JIKA SEMUA VALID, LANJUTKAN PROSES
            // ========================================
            $pengiriman = Pengiriman::create([
                'qc_id' => $request->qc_id,
                'supir_id' => $request->supir_id,
                'kendaraan_id' => $request->kendaraan_id,
                'tanggal_pengiriman' => $request->tanggal_pengiriman,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status' => $statusPengiriman,
                'tipe_pengiriman' => 'eksternal',
            ]);

            // LOOP TIAP ITEM
            foreach ($request->produksi_id as $i => $produksiID) {
                $subProsesSaatIni = $request->sub_proses_saat_ini[$i] ?? null;
                $subProsesTujuan = $request->sub_proses_tujuan[$i];
                $jumlah = $request->jumlah_pengiriman[$i];
                $supplierID = $request->supplier_id[$i];
                $butuhBp = $request->butuh_bp[$i] ?? 0;

                $produksi = Produksi::with(['barang.bahanPendukungBarang.bahanPendukung'])
                    ->findOrFail($produksiID);

                // ========================================
                // UPDATE PROGRES PRODUKSI
                // ========================================
                
                // JIKA ADA SUB PROSES SAAT INI → KURANGI DARI sdh_selesai
                if ($subProsesSaatIni) {
                    $progresSaatIni = ProgresProduksi::where('produksi_id', $produksiID)
                        ->where('sub_proses_id', $subProsesSaatIni)
                        ->first();

                    if ($progresSaatIni) {
                        // Kurangi dari sdh_selesai
                        $progresSaatIni->sdh_selesai -= $jumlah;
                        // Update jumlah total
                        $progresSaatIni->jumlah = $progresSaatIni->dlm_proses + $progresSaatIni->sdh_selesai;
                        $progresSaatIni->save();
                    }
                }
                
                // TAMBAHKAN KE SUB PROSES TUJUAN (SELALU DILAKUKAN)
                $progresTujuan = ProgresProduksi::firstOrCreate(
                    [
                        'produksi_id' => $produksiID,
                        'sub_proses_id' => $subProsesTujuan,
                    ],
                    [
                        'dlm_proses' => 0,
                        'sdh_selesai' => 0,
                        'jumlah' => 0
                    ]
                );

                // Tambahkan ke dlm_proses (karena baru dikirim)
                $progresTujuan->dlm_proses += $jumlah;
                // Update jumlah total
                $progresTujuan->jumlah = $progresTujuan->dlm_proses + $progresTujuan->sdh_selesai;
                $progresTujuan->save();

                // ========================================
                // UPDATE STATUS PRODUKSI JIKA BARU PERTAMA KALI
                // ========================================
                if ($produksi->status_produksi == 'Pending') {
                    $produksi->update(['status_produksi' => 'Diproses']);
                }

                // ========================================
                // TENTUKAN GUDANG_KONFIRMASI
                // ========================================
                // Jika butuh BP = 1 → gudang_konfirmasi = false (perlu konfirmasi gudang)
                // Jika butuh BP = 0 → gudang_konfirmasi = true (tidak perlu konfirmasi)
                $gudangKonfirmasi = $butuhBp == 1 ? false : true;

                // INSERT DETAIL PENGIRIMAN
                DetailPengiriman::create([
                    'pengiriman_id'       => $pengiriman->pengiriman_id,
                    'produksi_id'         => $produksiID,
                    'sub_proses_id'       => $subProsesTujuan,
                    'supplier_id'         => $supplierID,
                    'jumlah_pengiriman'   => $jumlah,
                    'butuh_bp'            => $butuhBp,
                    'gudang_konfirmasi'   => $gudangKonfirmasi
                ]);
            }

            DB::commit();
            return redirect()->route('keprod.pengiriman.index')
                ->with('success', 'Semua pengiriman berhasil disimpan!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['errors' => 'Gagal menyimpan: ' . $e->getMessage()])->withInput();
        }
    }


    public function getBahanPendukung($produksi_id, $sub_proses_tujuan)
    {
        $current = request()->query('current', 1); // 0 = belum ada proses sebelumnya

        $produksi = Produksi::findOrFail($produksi_id);

        $start = $current > 1 ? $current + 1 : 1;   // contoh: jika current = 2 → mulai dari 3

        $bahanPendukung = $produksi->barang
            ->bahanPendukungBarang()
            ->whereBetween('sub_proses_id', [$start, $sub_proses_tujuan])
            ->with('bahanPendukung')
            ->get();

        $result = $bahanPendukung->map(function ($item) {
            return [
                'nama_bahan' => $item->bahanPendukung->nama_bahan_pendukung,
                'stok'       => $item->bahanPendukung->stok_bahan_pendukung,
                'kebutuhan'  => $item->jumlah_bahan_pendukung,
            ];
        });

        return response()->json($result);
    }

    public function indexGudang()
    {
        $detail = DetailPengiriman::where('butuh_bp', 1)
            ->orderBy('gudang_konfirmasi', 'asc')
            ->with([
                'pengiriman.kendaraan',
                'supplier',
                'pengiriman.supir',
                'pengiriman.qc',
                'produksi.barang'
            ])
            ->get();

        return view('gudang.pengiriman', compact('detail'));
    }

    public function bahanDetail($id)
    {
        $current = request()->query('current', 1); // proses terakhir yang sudah lewat

        $item = DetailPengiriman::with([
            'produksi.barang.bahanPendukungBarang.bahanPendukung',
            'produksi.progresProduksi.subProses'
        ])->findOrFail($id);

        $jumlah = $item->jumlah_pengiriman;
        $subProsesTujuan = $item->sub_proses_id;

        // 🔹 Tentukan range proses
        $start = $current > 1 ? $current + 1 : 1;

        // 🔹 Ambil bahan pendukung dari start → sub proses tujuan
        $bahan = $item->produksi->barang
            ->bahanPendukungBarang
            ->whereBetween('sub_proses_id', [$start, $subProsesTujuan])
            ->map(function ($bpb) use ($jumlah) {
                return [
                    'nama_bahan'      => $bpb->bahanPendukung->nama_bahan_pendukung,
                    'stok'            => $bpb->bahanPendukung->stok_bahan_pendukung,
                    'jumlah_per_unit' => $bpb->jumlah_bahan_pendukung,
                    'total_butuh'     => $bpb->jumlah_bahan_pendukung * $jumlah,
                ];
            })
            ->values();

        return response()->json([
            'nama_barang' => $item->produksi->barang->nama_barang,
            'jumlah_pengiriman' => $jumlah,
            'bahan' => $bahan
        ]);
    }


    // Konfirmasi kebutuhan bahan pendukung pengiriman oleh gudang
    public function konfirmasiKebutuhan($id)
    {
        DB::beginTransaction();
        try {
            // Ambil detail pengiriman dengan relasi yang diperlukan
            $detailPengiriman = DetailPengiriman::with([
                'pengiriman.detailPengiriman', // Tambahkan relasi untuk cek detail lainnya
                'produksi.barang.bahanPendukungBarang.bahanPendukung',
                'subProses'
            ])->findOrFail($id);

            // Validasi: Cek apakah status pengiriman masih "Menunggu Gudang"
            if ($detailPengiriman->pengiriman->status !== 'Menunggu Gudang') {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengiriman ini sudah dikonfirmasi sebelumnya!'
                ], 400);
            }

            // Validasi: Cek apakah butuh bahan pendukung
            if ($detailPengiriman->butuh_bp != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pengiriman ini tidak membutuhkan bahan pendukung!'
                ], 400);
            }

            // Validasi: Cek apakah sudah dikonfirmasi sebelumnya
            if ($detailPengiriman->gudang_konfirmasi == true) {
                return response()->json([
                    'success' => false,
                    'message' => 'Detail pengiriman ini sudah dikonfirmasi sebelumnya!'
                ], 400);
            }

            $produksi = $detailPengiriman->produksi;
            $subProsesTujuan = $detailPengiriman->sub_proses_id;
            $jumlah = $detailPengiriman->jumlah_pengiriman;

            // 🔹 Ambil nilai 'current' dari query parameter (sama seperti bahanDetail)
            $current = request()->query('current', 1);
            
            // 🔹 Tentukan range proses (sama seperti bahanDetail)
            $start = $current > 1 ? $current + 1 : 1;

            // 🔹 Ambil semua bahan pendukung dalam range
            $bahanPendukungList = $produksi->barang->bahanPendukungBarang
                ->whereBetween('sub_proses_id', [$start, $subProsesTujuan]);

            // 🔹 Kelompokkan dan hitung kebutuhan per bahan
            $pengurangan = [];
            $errorMessages = [];
            
            foreach ($bahanPendukungList as $bpb) {
                $bpId = $bpb->bahan_pendukung_id;
                $butuh = $bpb->jumlah_bahan_pendukung * $jumlah;
                
                if (!isset($pengurangan[$bpId])) {
                    $pengurangan[$bpId] = [
                        'model' => $bpb->bahanPendukung,
                        'nama' => $bpb->bahanPendukung->nama_bahan_pendukung,
                        'stok' => $bpb->bahanPendukung->stok_bahan_pendukung,
                        'total' => 0
                    ];
                }
                
                $pengurangan[$bpId]['total'] += $butuh;
            }

            // 🔹 Validasi stok sebelum mengurangi
            foreach ($pengurangan as $data) {
                if ($data['total'] > $data['stok']) {
                    $errorMessages[] = "Stok {$data['nama']} tidak cukup! (Butuh: {$data['total']}, Tersedia: {$data['stok']})";
                }
            }

            if (!empty($errorMessages)) {
                return response()->json([
                    'success' => false,
                    'message' => implode(', ', $errorMessages)
                ], 400);
            }

            // 🔹 Kurangi stok bahan pendukung
            foreach ($pengurangan as $data) {
                $data['model']->decrement('stok_bahan_pendukung', $data['total']);
            }

            // 🔹 Update gudang_konfirmasi untuk detail pengiriman ini
            $detailPengiriman->update([
                'gudang_konfirmasi' => true,
            ]);

            // ========================================
            // CEK APAKAH SEMUA DETAIL SUDAH DIKONFIRMASI
            // ========================================
            $pengiriman = $detailPengiriman->pengiriman;
            
            // Hitung jumlah detail yang butuh BP
            $totalButuhBP = $pengiriman->detailPengiriman()
                ->where('butuh_bp', 1)
                ->count();
            
            // Hitung jumlah detail yang sudah dikonfirmasi
            $totalSudahKonfirmasi = $pengiriman->detailPengiriman()
                ->where('butuh_bp', 1)
                ->where('gudang_konfirmasi', true)
                ->count();
            
            // Jika semua detail yang butuh BP sudah dikonfirmasi
            if ($totalButuhBP > 0 && $totalButuhBP == $totalSudahKonfirmasi) {
                // ========================================
                // TENTUKAN STATUS BERDASARKAN TIPE PENGIRIMAN
                // ========================================
                // Internal → "Selesai" (tidak perlu QC)
                // Eksternal → "Menunggu QC" (perlu QC)
                $newStatus = $pengiriman->tipe_pengiriman === 'internal' ? 'Selesai' : 'Menunggu QC';
                
                $pengiriman->update([
                    'status' => $newStatus,
                ]);
                
                // ========================================
                // UPDATE STATUS_PENGIRIMAN SEMUA DETAIL (KHUSUS INTERNAL)
                // ========================================
                // Jika pengiriman internal, update SEMUA detail menjadi "Sampai"
                if ($pengiriman->tipe_pengiriman === 'internal') {
                    $pengiriman->detailPengiriman()->update([
                        'status_pengiriman' => 'Sampai',
                        'waktu_diterima' => now()
                    ]);
                }
                
                // Pesan disesuaikan dengan tipe pengiriman
                if ($pengiriman->tipe_pengiriman === 'internal') {
                    $message = 'Semua bahan pendukung berhasil dikonfirmasi! Pengiriman internal telah selesai dan semua barang sudah sampai ke supplier.';
                } else {
                    $message = 'Semua bahan pendukung berhasil dikonfirmasi! Status pengiriman diubah menjadi "Menunggu QC".';
                }
            } else {
                $sisaBelumKonfirmasi = $totalButuhBP - $totalSudahKonfirmasi;
                $message = "Bahan pendukung untuk item ini berhasil dikonfirmasi! Masih ada {$sisaBelumKonfirmasi} item lagi yang perlu dikonfirmasi.";
            }

            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengkonfirmasi: ' . $e->getMessage()
            ], 500);
        }
    }


    // Daftar Pengiriman untuk Supir
    public function indexForSupir()
    {
        $user = Auth::user();

        // Ambil semua pengiriman berdasarkan supir_id saja (lebih akurat)
        $pengirimanList = Pengiriman::with([
                'detailPengiriman',
                'detailPengiriman.produksi.barang',
                'kendaraan',
                'detailPengiriman.supplier',
                'qc',
                'supir'
            ])
            ->where('supir_id', $user->id)
            ->whereIn('status', ['Menunggu QC', 'Sedang Dipersiapkan', 'Dalam Pengiriman'])
            ->orderByDesc('created_at')
            ->get();

        return view('supir.infopengiriman', compact('pengirimanList'));
    }

    // Update status pengiriman oleh QC
    public function updateStatus($id)
    {
        $pengiriman = Pengiriman::findOrFail($id);

        $pengiriman->update(['status' => 'Sedang Dipersiapkan']);

        return back()->with('success', 'Status pengiriman berhasil diubah menjadi "Sedang Dipersiapkan".');
    }

    public function mulai($pengiriman_id)
    {
        DB::beginTransaction();
        try {
            $pengiriman = Pengiriman::with(['detailPengiriman.supplier'])
                ->findOrFail($pengiriman_id);

            // Hitung total waktu antar dari semua supplier
            $totalWaktuAntar = $pengiriman->detailPengiriman
                ->pluck('supplier')
                ->unique('id')
                ->sum('waktu_antar');

            // Update pengiriman
            $pengiriman->update([
                'status' => 'Dalam Pengiriman',
                'waktu_mulai' => now(),
                'total_waktu_antar' => $totalWaktuAntar
            ]);

            // Update semua detail pengiriman
            $pengiriman->detailPengiriman()->update([
                'status_pengiriman' => 'Dalam Perjalanan'
            ]);

            DB::commit();
            return redirect()->route('supir.pengiriman.perjalanan', $pengiriman_id)
                ->with('success', 'Pengiriman dimulai!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal memulai pengiriman: ' . $e->getMessage()]);
        }
    }

    // Halaman perjalanan dengan countdown
    public function perjalanan($pengiriman_id)
    {
        $pengiriman = Pengiriman::with([
            'detailPengiriman.produksi.barang',
            'detailPengiriman.supplier',
            'detailPengiriman.subProses',
            'kendaraan',
            'qc'
        ])
        ->findOrFail($pengiriman_id);

        // Group by supplier
        $supplierGroups = $pengiriman->detailPengiriman
            ->groupBy('supplier_id')
            ->map(function ($items, $supplierId) {
                $supplier = $items->first()->supplier;
                return [
                    'supplier_id' => $supplierId,
                    'supplier_name' => $supplier->name,
                    'waktu_antar' => $supplier->waktu_antar,
                    'items' => $items,
                    'status' => $items->first()->status_pengiriman,
                    'waktu_sampai' => $items->first()->waktu_sampai
                ];
            })
            ->values();

        return view('supir.perjalanan', compact('pengiriman', 'supplierGroups'));
    }

    // Konfirmasi sampai di supplier
    public function konfirmasiSampai(Request $request, $pengiriman_id)
    {
        $request->validate([
            'supplier_id' => 'required|exists:users,id'
        ]);

        DB::beginTransaction();
        try {
            $pengiriman = Pengiriman::findOrFail($pengiriman_id);

            // Update semua detail pengiriman untuk supplier ini
            DetailPengiriman::where('pengiriman_id', $pengiriman_id)
                ->where('supplier_id', $request->supplier_id)
                ->update([
                    'status_pengiriman' => 'Sampai',
                    'waktu_sampai' => now()
                ]);

            DB::commit();
            return back()->with('success', 'Konfirmasi sampai berhasil! Menunggu supplier menerima barang.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal konfirmasi: ' . $e->getMessage()]);
        }
    }

    // Konfirmasi pengiriman selesai
    public function selesai($pengiriman_id)
    {
        DB::beginTransaction();
        try {
            $pengiriman = Pengiriman::with('detailPengiriman')->findOrFail($pengiriman_id);

            // Cek apakah semua sudah diterima supplier
            $semuaDiterima = $pengiriman->detailPengiriman
                ->every(fn($detail) => $detail->status_pengiriman === 'Diterima');

            if (!$semuaDiterima) {
                return back()->withErrors(['error' => 'Masih ada barang yang belum diterima supplier!']);
            }

            // Update status pengiriman
            $pengiriman->update([
                'status' => 'Selesai',
                'waktu_selesai' => now()
            ]);

            DB::commit();
            return redirect()->route('supir.pengiriman.index')
                ->with('success', 'Pengiriman selesai!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menyelesaikan pengiriman: ' . $e->getMessage()]);
        }
    }


    // Supplier
    // Daftar barang masuk untuk supplier
    public function indexForSupplier()
    {
        $user = Auth::user();
        $pengirimanList = DetailPengiriman::with([
            'pengiriman.supir',
            'pengiriman.kendaraan',
            'produksi.barang',
            'subProses'
        ])
        ->where('supplier_id', $user->id)
        ->whereIn('status_pengiriman', ['Dalam Perjalanan', 'Sampai'])
        ->orderBy('created_at', 'desc')
        ->get()
        ->groupBy('pengiriman_id');

        return view('supplier.barangmasuk', compact('pengirimanList'));
    }

    // Konfirmasi barang diterima oleh supplier
    public function terima($detail_pengiriman_id)
    {
        $user = Auth::user();
        DB::beginTransaction();
        try {
            $detail = DetailPengiriman::findOrFail($detail_pengiriman_id);

            // Pastikan supplier yang login adalah pemilik barang ini
            if ($detail->supplier_id !== $user->id) {
                return back()->withErrors(['error' => 'Anda tidak berhak menerima barang ini!']);
            }

            // Pastikan status sudah sampai
            if ($detail->status_pengiriman !== 'Sampai') {
                return back()->withErrors(['error' => 'Barang belum sampai!']);
            }

            // Update status
            $detail->update([
                'status_pengiriman' => 'Diterima',
                'waktu_diterima' => now()
            ]);

            DB::commit();
            return back()->with('success', 'Barang berhasil diterima!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Gagal menerima barang: ' . $e->getMessage()]);
        }
    }
}
