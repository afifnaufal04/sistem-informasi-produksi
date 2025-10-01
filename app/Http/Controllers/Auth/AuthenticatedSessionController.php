<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class AuthenticatedSessionController extends Controller
{
    // ini untuk loginnya
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($request->only('username', 'password'))) {
            return back()->withErrors([
                'username' => 'Username atau password salah.',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();

        // Redirect berdasarkan atribut role (ternary)
        return redirect()->intended(
            $user->role === 'marketing' ? route('marketing.dashboard') :
            ($user->role === 'ppic' ? route('ppic.dashboard') :
            ($user->role === 'keprod' ? route('keprod.dashboard') :
            ($user->role === 'qc' ? route('qc.dashboard') :
            ($user->role === 'purchasing' ? route('purchasing.dashboard') :
            ($user->role === 'gudang' ? route('gudang.dashboard') :
            ($user->role === 'packing' ? route('packing.dashboard') :
            ($user->role === 'direktur' ? route('direktur.dashboard') :
            ($user->role === 'supplier' ? route('supplier.dashboard') :
            ($user->role === 'supir' ? route('supir.dashboard') :
            route('home')))))))))));
    }

    // ini untuk logout
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/'); // kembali ke halaman login setelah logout
    }
}
