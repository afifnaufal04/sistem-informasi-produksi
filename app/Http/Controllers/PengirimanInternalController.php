<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengiriman;
use App\Models\DetailPengiriman;
use App\Models\Produksi;
use App\Models\User;
use App\Models\SubProses;
use App\Models\ProgresProduksi;
use Illuminate\Support\Facades\DB;

class PengirimanInternalController extends Controller
{
    // 📝 Form Tambah Pengiriman Internal (tanpa supir & QC)
    public function create()
    {
        $produksi = Produksi::with(['barang', 'progresProduksi'])
            ->get()
            ->map(function ($item) {
                $totalTerdistribusi = $item->progresProduksi->sum('jumlah');
                $item->sisa_pengiriman = $item->jumlah_produksi - $totalTerdistribusi;
                return $item;
            });

        $subProsesTujuan = SubProses::whereNotIn('nama_sub_proses', ['Packing'])->get();
        $supplier = User::where('role', 'supplier')
                    ->where('tipe_supplier', 'internal')
                    ->get();
        $qc = User::where('role', 'qc')->get();

        return view('keprod.createPengirimanInternal', compact(
            'produksi',
            'subProsesTujuan',
            'supplier',
            'qc'
        ));
    }

    // 💾 Simpan Pengiriman Internal
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

            'tanggal_pengiriman.required' => 'Tanggal pengiriman wajib diisi.',
            'tanggal_selesai.required' => 'Tanggal selesai wajib diisi.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal pengiriman.',
        ];

        $request->validate($rules, $messages);

        DB::beginTransaction();
        try {
            $errorMessages = [];

            // Validasi semua item dulu
            foreach ($request->produksi_id as $i => $produksiID) {
                $subProsesSaatIni = $request->sub_proses_saat_ini[$i] ?? null;
                $subProsesTujuan = $request->sub_proses_tujuan[$i];
                $jumlah = $request->jumlah_pengiriman[$i];
                $butuhBp = $request->butuh_bp[$i] ?? 0;

                $produksi = Produksi::with(['barang.bahanPendukungBarang.bahanPendukung'])
                    ->findOrFail($produksiID);

                $maxJumlah = 0;
                
                if ($subProsesSaatIni) {
                    $progresSaatIni = ProgresProduksi::where('produksi_id', $produksiID)
                        ->where('sub_proses_id', $subProsesSaatIni)
                        ->first();

                    if (!$progresSaatIni || $progresSaatIni->sdh_selesai <= 0) {
                        $errorMessages[] = "Item " . ($i + 1) . " : Sub proses saat ini tidak memiliki stok!";
                        continue;
                    }

                    $maxJumlah = $progresSaatIni->sdh_selesai;
                } else {
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

                // Validasi bahan pendukung
                if ($butuhBp == 1) {
                    $subProsesTujuanObj = SubProses::find($subProsesTujuan);
                    $subProsesSaatIniObj = $subProsesSaatIni ? SubProses::find($subProsesSaatIni) : null;
                    
                    $startSubProsesId = $subProsesSaatIniObj ? $subProsesSaatIniObj->sub_proses_id + 1 : 1;
                    $endSubProsesId = $subProsesTujuanObj->sub_proses_id;

                    $bahanPendukung = $produksi->barang->bahanPendukungBarang
                        ->whereBetween('sub_proses_id', [$startSubProsesId, $endSubProsesId]);

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

                    foreach ($kebutuhanPerBahan as $bpId => $data) {
                        if ($data['total_butuh'] > $data['stok']) {
                            $errorMessages[] = "Item #" . ($i + 1) . ": Stok {$data['nama']} tidak cukup! (Butuh: {$data['total_butuh']}, Tersedia: {$data['stok']})";
                        }
                    }
                }
            }

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
            // TENTUKAN STATUS PENGIRIMAN INTERNAL
            // ========================================
            // Jika ada barang yang butuh BP → "Menunggu Gudang"
            // Jika tidak ada yang butuh BP → "Selesai" (langsung selesai)
            $statusPengiriman = $adaYangButuhBP ? 'Menunggu Gudang' : 'Selesai';

            // Buat pengiriman internal
            $pengiriman = Pengiriman::create([
                'supir_id' => null,
                'qc_id' => $request->qc_id,
                'kendaraan_id' => null,
                'tanggal_pengiriman' => $request->tanggal_pengiriman,
                'tanggal_selesai' => $request->tanggal_selesai,
                'status' => $statusPengiriman, // Status dinamis
                'tipe_pengiriman' => 'internal',
            ]);

            foreach ($request->produksi_id as $i => $produksiID) {
                $subProsesSaatIni = $request->sub_proses_saat_ini[$i] ?? null;
                $subProsesTujuan = $request->sub_proses_tujuan[$i];
                $jumlah = $request->jumlah_pengiriman[$i];
                $supplierID = $request->supplier_id[$i];
                $butuhBp = $request->butuh_bp[$i] ?? 0;

                $produksi = Produksi::with(['barang.bahanPendukungBarang.bahanPendukung'])
                    ->findOrFail($produksiID);

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
                
                // TAMBAHKAN KE SUB PROSES TUJUAN
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
                // Tambahkan ke dlm_proses (karena baru dikirim, belum diambil)
                $progresTujuan->dlm_proses += $jumlah;
                // Update jumlah total
                $progresTujuan->jumlah = $progresTujuan->dlm_proses + $progresTujuan->sdh_selesai;
                $progresTujuan->save();
                
                if ($produksi->status_produksi == 'Pending') {
                    $produksi->update(['status_produksi' => 'Diproses']);
                }

                // ========================================
                // TENTUKAN GUDANG_KONFIRMASI
                // ========================================
                // Jika butuh BP = 1 → gudang_konfirmasi = false (perlu konfirmasi)
                // Jika butuh BP = 0 → gudang_konfirmasi = true (tidak perlu konfirmasi)
                $gudangKonfirmasi = $butuhBp == 1 ? false : true;

                $statusPengirimanDetail = $butuhBp == 1 ? 'Belum Diantar' : 'Sampai';

                DetailPengiriman::create([
                    'pengiriman_id'       => $pengiriman->pengiriman_id,
                    'produksi_id'         => $produksiID,
                    'sub_proses_id'       => $subProsesTujuan,
                    'supplier_id'         => $supplierID,
                    'jumlah_pengiriman'   => $jumlah,
                    'butuh_bp'            => $butuhBp,
                    'gudang_konfirmasi'   => $gudangKonfirmasi, // Dinamis berdasarkan butuh_bp
                    'status_pengiriman'   => $statusPengirimanDetail,
                ]);
            }

            DB::commit();
            
            $message = $adaYangButuhBP 
                ? 'Pengiriman internal berhasil dibuat! Menunggu konfirmasi QC untuk bahan pendukung.'
                : 'Pengiriman internal berhasil dibuat dan langsung selesai!';
            
            return redirect()->route('keprod.pengiriman.index')
                ->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['errors' => 'Gagal menyimpan: ' . $e->getMessage()])->withInput();
        }
    }

    // Method untuk ambil sub proses saat ini (sama seperti eksternal)
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
                    'jumlah' => $item->sdh_selesai,
                ];
            });

        return response()->json($subproses);
    }

    // Method untuk ambil bahan pendukung (sama seperti eksternal)
    public function getBahanPendukung($produksi_id, $sub_proses_tujuan)
    {
        $current = request()->query('current', 1);
        $produksi = Produksi::findOrFail($produksi_id);
        $start = $current > 1 ? $current + 1 : 1;

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
}