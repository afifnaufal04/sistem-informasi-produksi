{{-- resources/views/direktur/dashboard.blade.php --}}
@extends('layouts.allApp')

@section('title', 'Dashboard')
@section('role', 'Direktur')

@section('content')
    <div class="px-6">
        <h1 class="text-2xl font-bold">Selamat Datang di Dashboard Direktur</h1>
        <p class="mt-2 text-gray-600">Halo, {{ optional(Auth::user())->name }}! Kamu login sebagai
            <b>{{ optional(Auth::user())->role }}</b>.</p>
    </div>
@endsection