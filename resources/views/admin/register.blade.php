@extends('layouts.allApp')

@section('title', 'Tambah User')
@section('role', 'Admin')

@section('content')
<div class="px-6">
    <h1 class="text-4xl font-bold text-center mb-6">Tambah User Baru</h1>
    
    <!-- Untuk Menampilkan Notifikasi -->
    @if (session('success'))
        <div id="alert-success" class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
            <span onclick="document.getElementById('alert-success').remove();" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
                <svg class="fill-current h-6 w-6 text-green-700" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z"/>
                </svg>
            </span>
        </div>
    @endif

    @if (session('error'))
        <div id="alert-error" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
            <span onclick="document.getElementById('alert-error').remove();" class="absolute top-0 bottom-0 right-0 px-4 py-3 cursor-pointer">
                <svg class="fill-current h-6 w-6 text-red-700" role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Close</title>
                    <path d="M14.348 5.652a1 1 0 00-1.414 0L10 8.586 7.066 5.652a1 1 0 10-1.414 1.414L8.586 10l-2.934 2.934a1 1 0 101.414 1.414L10 11.414l2.934 2.934a1 1 0 001.414-1.414L11.414 10l2.934-2.934a1 1 0 000-1.414z"/>
                </svg>
            </span>
        </div>
    @endif

    <script>
        // Auto close after 5 seconds
        setTimeout(() => {
            let successAlert = document.getElementById('alert-success');
            if (successAlert) successAlert.remove();

            let errorAlert = document.getElementById('alert-error');
            if (errorAlert) errorAlert.remove();
        }, 5000);
    </script>

    <div class="bg-white p-6 rounded-lg shadow-md">
        <form method="POST" action="{{ route('admin.register.store') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input id="name" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Username -->
                <div>
                    <label for="username" class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                    <input id="username" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" type="text" name="username" value="{{ old('username') }}" required autocomplete="username" />
                    @error('username')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input id="email" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
                    @error('email')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role -->
                <div>
                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select id="role" name="role" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" required onchange="toggleSupplierFields()">
                        <option value="" disabled selected>Pilih Role</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="marketing" {{ old('role') == 'marketing' ? 'selected' : '' }}>Marketing</option>
                        <option value="ppic" {{ old('role') == 'ppic' ? 'selected' : '' }}>PPIC</option>
                        <option value="keprod" {{ old('role') == 'keprod' ? 'selected' : '' }}>Keprod</option>
                        <option value="qc" {{ old('role') == 'qc' ? 'selected' : '' }}>QC</option>
                        <option value="purchasing" {{ old('role') == 'purchasing' ? 'selected' : '' }}>Purchasing</option>
                        <option value="gudang" {{ old('role') == 'gudang' ? 'selected' : '' }}>Gudang</option>
                        <option value="packing" {{ old('role') == 'packing' ? 'selected' : '' }}>Packing</option>
                        <option value="direktur" {{ old('role') == 'direktur' ? 'selected' : '' }}>Direktur</option>
                        <option value="supplier" {{ old('role') == 'supplier' ? 'selected' : '' }}>Supplier</option>
                        <option value="supir" {{ old('role') == 'supir' ? 'selected' : '' }}>Supir</option>
                    </select>
                    @error('role')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input id="password" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" type="password" name="password" required autocomplete="new-password" />
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <input id="password_confirmation" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" type="password" name="password_confirmation" required autocomplete="new-password" />
                    @error('password_confirmation')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Field khusus Supplier (hidden by default) -->
            <div id="supplierFields" style="display: none;" class="mt-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Informasi Supplier</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tipe Supplier -->
                    <div>
                        <label for="tipe_supplier" class="block text-sm font-medium text-gray-700 mb-2">
                            Tipe Supplier <span class="text-red-500">*</span>
                        </label>
                        <select id="tipe_supplier" name="tipe_supplier" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" onchange="toggleWaktuAntar()">
                            <option value="">-- Pilih Tipe Supplier --</option>
                            <option value="internal" {{ old('tipe_supplier') == 'internal' ? 'selected' : '' }}>Internal</option>
                            <option value="eksternal" {{ old('tipe_supplier') == 'eksternal' ? 'selected' : '' }}>Eksternal</option>
                        </select>
                        <p class="mt-1 text-xs text-gray-500">Internal: Supplier dalam pabrik | Eksternal: Supplier luar pabrik</p>
                        @error('tipe_supplier')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Waktu Antar (hanya untuk eksternal) -->
                    <div id="waktuAntarField" style="display: none;">
                        <label for="waktu_antar" class="block text-sm font-medium text-gray-700 mb-2">
                            Waktu Antar (Menit) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="waktu_antar" name="waktu_antar" min="1" value="{{ old('waktu_antar') }}" class="block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500" placeholder="Contoh: 60">
                        <p class="mt-1 text-xs text-gray-500">Estimasi waktu pengiriman dari supplier ke pabrik</p>
                        @error('waktu_antar')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end mt-6 space-x-4">
                <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    Tambah User
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleSupplierFields() {
    const role = document.getElementById('role').value;
    const supplierFields = document.getElementById('supplierFields');
    const tipeSupplierInput = document.getElementById('tipe_supplier');
    
    if (role === 'supplier') {
        supplierFields.style.display = 'block';
        tipeSupplierInput.required = true;
        
        // Trigger waktu antar field jika tipe sudah dipilih
        toggleWaktuAntar();
    } else {
        supplierFields.style.display = 'none';
        tipeSupplierInput.required = false;
        tipeSupplierInput.value = '';
        
        // Reset waktu antar
        document.getElementById('waktu_antar').required = false;
        document.getElementById('waktu_antar').value = '';
        document.getElementById('waktuAntarField').style.display = 'none';
    }
}

function toggleWaktuAntar() {
    const tipeSupplier = document.getElementById('tipe_supplier').value;
    const waktuAntarField = document.getElementById('waktuAntarField');
    const waktuAntarInput = document.getElementById('waktu_antar');
    
    if (tipeSupplier === 'eksternal') {
        waktuAntarField.style.display = 'block';
        waktuAntarInput.required = true;
    } else {
        waktuAntarField.style.display = 'none';
        waktuAntarInput.required = false;
        waktuAntarInput.value = '';
    }
}

// Trigger on page load untuk menangani old input
document.addEventListener('DOMContentLoaded', function() {
    toggleSupplierFields();
});
</script>
@endsection