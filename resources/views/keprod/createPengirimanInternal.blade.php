@extends('layouts.allApp')

@section('title', 'Tambah Pengiriman Internal')
@section('role', 'Kepala Produksi')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-md mt-8">

        <h2 class="text-2xl font-bold mb-6 text-gray-700">Tambah Pengiriman Internal</h2>
        <p class="text-sm text-gray-600 mb-4">Pengiriman internal akan langsung diterima oleh supplier tanpa proses QC &
            supir</p>

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong class="font-bold">Ada kesalahan:</strong>
                <ul class="mt-2 ml-4 list-disc">
                    @foreach ($errors->all() as $error)
                        <li>{!! $error !!}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('keprod.pengirimanInternal.store') }}" method="POST" class="space-y-5">
            @csrf

            @php
                $oldCount = max(1, count(old('produksi_id', [])));
            @endphp
            <div id="pengirimanWrapper" class="space-y-5">
                @for ($i = 0; $i < $oldCount; $i++)
                    <div class="pengiriman-item border rounded-lg p-4 bg-gray-50 space-y-4 relative">

                        <button type="button"
                            class="removePengiriman absolute -top-2 -right-2 bg-red-600 text-white rounded-full w-7 h-7 flex items-center justify-center shadow">✕</button>

                        <div>
                            <label class="font-medium text-gray-700">Produksi</label>
                            <select name="produksi_id[]" required
                                class="w-full mt-1 px-3 py-2 border rounded-lg produksiSelect">
                                <option value="">-- Pilih Produksi --</option>
                                @foreach($produksi as $item)
                                    <option value="{{ $item->produksi_id }}" {{ old('produksi_id.' . $i) == $item->produksi_id ? 'selected' : '' }}>{{ $item->barang->nama_barang }} | Sisa: {{ $item->sisa_pengiriman }}
                                    </option>
                                @endforeach
                            </select>
                            @error('produksi_id.' . $i)
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="font-medium text-gray-700">Sub Proses Saat Ini</label>
                            <select name="sub_proses_saat_ini[]"
                                class="w-full mt-1 px-3 py-2 border rounded-lg subProsesSaatIni"
                                data-old="{{ old('sub_proses_saat_ini.' . $i) }}">
                                <option value="">-- Belum Ada Proses Sebelumnya --</option>
                                @if(old('sub_proses_saat_ini.' . $i))
                                    <option value="{{ old('sub_proses_saat_ini.' . $i) }}" selected>-- (Sebelumnya dipilih) --
                                    </option>
                                @endif
                            </select>
                        </div>

                        <div>
                            <label class="font-medium text-gray-700">Sub Proses Tujuan</label>
                            <select name="sub_proses_tujuan[]" required
                                class="subProsesTujuanSelect w-full mt-1 px-3 py-2 border rounded-lg">
                                <option value="">-- Pilih Sub Proses Tujuan --</option>
                                @foreach($subProsesTujuan as $sp)
                                    <option value="{{ $sp->sub_proses_id }}" {{ old('sub_proses_tujuan.' . $i) == $sp->sub_proses_id ? 'selected' : '' }}>{{ $sp->nama_sub_proses }}</option>
                                @endforeach
                            </select>
                            @error('sub_proses_tujuan.' . $i)
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="font-medium text-gray-700">Supplier</label>
                            <select name="supplier_id[]" required class="w-full mt-1 px-3 py-2 border rounded-lg">
                                <option value="">-- Pilih Supplier --</option>
                                @foreach($supplier as $user)
                                    <option value="{{ $user->id }}" {{ old('supplier_id.' . $i) == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id.' . $i)
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="font-medium text-gray-700">Jumlah Pengiriman</label>
                            <input type="number" min="1" name="jumlah_pengiriman[]" required
                                class="w-full mt-1 px-3 py-2 border rounded-lg jumlahPengiriman"
                                value="{{ old('jumlah_pengiriman.' . $i) }}">
                            @error('jumlah_pengiriman.' . $i)
                                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div>
                            <label class="inline-flex items-center">
                                <input type="hidden" name="butuh_bp[]" value="{{ old('butuh_bp.' . $i, 0) }}" class="hiddenBp">
                                <input type="checkbox" class="useBahanCheckbox" value="1" {{ old('butuh_bp.' . $i) == 1 ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Gunakan Bahan Pendukung</span>
                            </label>

                            <div class="bahanPendukungContainer {{ old('butuh_bp.' . $i) == 1 ? '' : 'hidden' }} mt-3">
                                <table class="w-full text-sm border">
                                    <thead>
                                        <tr class="bg-gray-200 text-gray-700">
                                            <th class="px-2 py-1 border">Nama Bahan</th>
                                            <th class="px-2 py-1 border">Stok</th>
                                            <th class="px-2 py-1 border">Jumlah / Unit</th>
                                            <th class="px-2 py-1 border">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                @endfor
            </div>

            <button type="button" id="addPengiriman"
                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg mt-3">+ Tambah Pengiriman</button>

            <div>
                <label class="font-medium text-gray-700">QC</label>
                <select name="qc_id" required class="w-full mt-1 px-3 py-2 border rounded-lg">
                    <option value="">-- Pilih QC --</option>
                    @foreach($qc as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="font-medium text-gray-700">Tanggal Order</label>
                    <input type="date" name="tanggal_pengiriman" required class="w-full mt-1 px-3 py-2 border rounded-lg"
                        value="{{ old('tanggal_pengiriman') }}">
                    @error('tanggal_pengiriman')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                </div>
                <div>
                    <label class="font-medium text-gray-700">Tanggal Selesai (Deadline)</label>
                    <input type="date" name="tanggal_selesai" required class="w-full mt-1 px-3 py-2 border rounded-lg"
                        value="{{ old('tanggal_selesai') }}">
                    @error('tanggal_selesai')<div class="text-red-600 text-sm mt-1">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium">Simpan</button>
            </div>

            <script>
                function initEvents() {
                    // Remove item
                    document.querySelectorAll('.removePengiriman').forEach(btn => {
                        btn.onclick = function () {
                            if (document.querySelectorAll('.pengiriman-item').length > 1) {
                                this.closest('.pengiriman-item').remove();
                            } else alert("Minimal harus ada 1 pengiriman!");
                        };
                    });

                    // Bahan checkbox
                    document.querySelectorAll('.useBahanCheckbox').forEach(chk => {
                        chk.onchange = function () {
                            const item = this.closest('.pengiriman-item');
                            const box = item.querySelector('.bahanPendukungContainer');
                            const hidden = item.querySelector('.hiddenBp');
                            hidden.value = this.checked ? 1 : 0;

                            if (this.checked) {
                                box.classList.remove('hidden');
                                loadBahanPendukung(item);
                            } else box.classList.add('hidden');
                        };

                        // initialize on load
                        if (chk.checked) {
                            const item = chk.closest('.pengiriman-item');
                            item.querySelector('.bahanPendukungContainer').classList.remove('hidden');
                            setTimeout(() => loadBahanPendukung(item), 250);
                        }
                    });

                    // Produksi -> load sub proses
                    document.querySelectorAll('.produksiSelect').forEach(sel => {
                        const loadSub = async function (selected = null) {
                            const produksiId = this.value;
                            const item = this.closest('.pengiriman-item');
                            const dropdown = item.querySelector('.subProsesSaatIni');

                            dropdown.innerHTML = `<option value="">Loading...</option>`;

                            if (!produksiId) {
                                dropdown.innerHTML = `<option value="">-- Belum Ada Proses Sebelumnya --</option>`;
                                return;
                            }

                            const res = await fetch(`/keprod/pengiriman/subproses/${produksiId}`);
                            const data = await res.json();

                            let html = `<option value="">-- Belum Ada Proses Sebelumnya --</option>`;
                            data.forEach(d => {
                                html += `<option value="${d.id}">${d.nama} | Jumlah: ${d.jumlah}</option>`;
                            });

                            dropdown.innerHTML = html;

                            // set previously selected if present
                            if (selected) dropdown.value = selected;
                        };

                        sel.addEventListener('change', function () { loadSub.call(this); });
                        sel.addEventListener('click', function () { loadSub.call(this); });

                        // initialize if there is an old sub proses value
                        const oldVal = sel.closest('.pengiriman-item').querySelector('.subProsesSaatIni').dataset.old || '';
                        if (sel.value) loadSub.call(sel, oldVal);
                    });

                    document.querySelectorAll('.subProsesSaatIni').forEach(sel => {
                        sel.onchange = function () {
                            const item = this.closest('.pengiriman-item');
                            const chk = item.querySelector('.useBahanCheckbox');
                            if (chk.checked) loadBahanPendukung(item);
                        };
                    });

                    document.querySelectorAll('.subProsesTujuanSelect').forEach(sel => {
                        sel.onchange = function () {
                            const item = this.closest('.pengiriman-item');
                            const chk = item.querySelector('.useBahanCheckbox');
                            if (chk.checked) loadBahanPendukung(item);
                        };
                    });
                }

                async function loadBahanPendukung(item) {
                    const produksiId = item.querySelector('.produksiSelect').value;
                    let subProsesSaatIniRaw = item.querySelector('.subProsesSaatIni').value;
                    const subProsesSaatIni = subProsesSaatIniRaw === "" ? 0 : parseInt(subProsesSaatIniRaw);
                    const subProsesTujuan = item.querySelector('.subProsesTujuanSelect').value;
                    const jumlah = parseInt(item.querySelector('.jumlahPengiriman').value || 0);
                    const tbody = item.querySelector('.bahanPendukungContainer tbody');

                    tbody.innerHTML = `<tr><td colspan='4' class='border text-center'>Loading...</td></tr>`;

                    if (!produksiId) {
                        tbody.innerHTML = `<tr><td colspan='4' class='border text-center text-red-600'>Pilih produksi dulu!</td></tr>`;
                        return;
                    }

                    if (!subProsesTujuan) {
                        tbody.innerHTML = `<tr><td colspan='4' class='border text-center text-red-600'>Pilih sub proses tujuan dulu!</td></tr>`;
                        return;
                    }

                    if (subProsesSaatIni && subProsesTujuan <= subProsesSaatIni) {
                        tbody.innerHTML = `<tr><td colspan='4' class='border text-center text-red-600'>
                Sub proses tujuan tidak boleh kurang atau sama dengan sub proses saat ini!
            </td></tr>`;
                        return;
                    }

                    const res = await fetch(`/keprod/bahan-pendukung-internal/${produksiId}/${subProsesTujuan}?current=${subProsesSaatIni}`);
                    const data = await res.json();

                    tbody.innerHTML = '';

                    if (!data.length) {
                        tbody.innerHTML = `<tr><td colspan='4' class='border text-center'>Tidak ada bahan</td></tr>`;
                        return;
                    }

                    data.forEach(row => {
                        const total = jumlah * row.kebutuhan;
                        tbody.innerHTML += `
                <tr>
                    <td class="border px-2 py-1">${row.nama_bahan}</td>
                    <td class="border px-2 py-1">${row.stok}</td>
                    <td class="border px-2 py-1 kebutuhanValue">${row.kebutuhan}</td>
                    <td class="border px-2 py-1 totalValue">${total}</td>
                </tr>`;
                    });

                    item.querySelector('.jumlahPengiriman').oninput = function () {
                        tbody.querySelectorAll('tr').forEach(tr => {
                            const kebutuhan = parseInt(tr.querySelector('.kebutuhanValue').innerText);
                            tr.querySelector('.totalValue').innerText = (this.value || 0) * kebutuhan;
                        });
                    };
                }

                initEvents();

                document.getElementById('addPengiriman').onclick = function () {
                    const wrapper = document.getElementById('pengirimanWrapper');
                    const clone = wrapper.children[0].cloneNode(true);

                    clone.querySelectorAll('input, select').forEach(el => {
                        if (el.type === 'checkbox') el.checked = false; else el.value = "";
                    });
                    clone.querySelector('.bahanPendukungContainer tbody').innerHTML = '';
                    clone.querySelector('.bahanPendukungContainer').classList.add('hidden');
                    clone.querySelector('.useBahanCheckbox').checked = false;
                    clone.querySelector('.hiddenBp').value = 0;

                    wrapper.appendChild(clone);
                    initEvents();
                };
            </script>

        </form>
    </div>
@endsection