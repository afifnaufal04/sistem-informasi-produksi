<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    public function create()
    {
        return view('admin.register');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'role' => ['required', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Rules\Password::min(3)],
            
            // Validasi untuk supplier
            'tipe_supplier' => ['required_if:role,supplier', 'nullable', 'in:internal,eksternal'],
            'waktu_antar' => [
                'required_if:tipe_supplier,eksternal', 
                'nullable', 
                'integer', 
                'min:1'
            ],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'username' => $request->username,
            'role' => $request->role,
            'password' => Hash::make($request->password),
            'work_status' => $request->role === 'supplier' ? 'tersedia' : null,
            'tipe_supplier' => $request->role === 'supplier' ? $request->tipe_supplier : null,
            'waktu_antar' => ($request->role === 'supplier' && $request->tipe_supplier === 'eksternal') 
                ? $request->waktu_antar 
                : null,
        ]);

        event(new Registered($user));

        return redirect()->route('admin.register')
            ->with('success', 'User baru berhasil ditambahkan.');
    }
}
