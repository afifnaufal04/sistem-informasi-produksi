<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     */
    public function authenticate(): void
    {
        // 1. Cek dulu apakah user SUDAH terkunci dari percobaan sebelumnya
        $this->ensureIsNotRateLimited();

        // 2. Coba Login
        if (! Auth::attempt($this->only('username', 'password'), $this->boolean('remember'))) {
            
            // 3. Jika GAGAL, catat kegagalan (denda 30 detik)
            RateLimiter::hit($this->throttleKey(), 30);

            // --- LOGIKA BARU DI SINI ---
            // Cek langsung: Apakah ini kegagalan ke-3?
            if (RateLimiter::tooManyAttempts($this->throttleKey(), 3)) {
                
                // Jika ya, langsung picu event Lockout
                event(new Lockout($this));

                // Ambil sisa waktu (seharusnya 30 detik karena baru saja gagal)
                $seconds = RateLimiter::availableIn($this->throttleKey());

                // Lempar error "Terlalu banyak percobaan" SEKARANG JUGA
                throw ValidationException::withMessages([
                    'username' => trans('auth.throttle', [
                        'seconds' => $seconds,
                        'minutes' => ceil($seconds / 60),
                    ]),
                ]);
            }
            // ---------------------------

            // Jika belum ke-3, lempar error password salah biasa
            throw ValidationException::withMessages([
                'username' => 'Maaf, Username atau Password Anda salah!',
            ]);
        }

        // Jika berhasil login, hapus catatan kegagalan
        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 3)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'username' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->input('username')).'|'.$this->ip());
    }
}