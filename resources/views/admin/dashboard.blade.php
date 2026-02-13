{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.allApp')

@section('title', 'Dashboard Admin')
@section('role', 'Admin')

@section('content')
    <div class="px-6">
        <h1 class="text-2xl font-bold">Selamat Datang di Dashboard Admin</h1>
        <p class="mt-2 text-gray-600">Halo, {{ optional(Auth::user())->name }}! Kamu login sebagai
            <b>{{ optional(Auth::user())->role }}</b>.</p>
    </div>
@endsection