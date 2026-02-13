<x-guest-layout>
    
    <div class="min-h-screen flex items-center justify-center">
        <div class="w-full max-w-sm px-6">
            
            {{-- KOTAK HIJAU UTAMA --}}
            <div class="bg-[#10a83a] rounded-2xl p-8 shadow-lg text-center">
                
                {{-- LOGO --}}
                <div class="mb-6">
                    <img src="{{ asset('storage/kwas_putih_2.png') }}" alt="KWaS Logo" class="mx-auto mb-2" style="width:120px;height:auto;" />
                </div>

                {{-- AREA PESAN ERROR / COUNTDOWN --}}
                <div id="alert-container" class="mb-4 hidden">
                    <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded relative text-sm font-bold" role="alert">
                        <span class="block sm:inline" id="countdown-text"></span>
                    </div>
                </div>

                <form method="POST" action="{{ route('login') }}" id="login-form">
                    @csrf

                    {{-- INPUT USERNAME --}}
                    <div class="mb-4">
                        <input id="username" name="username" type="text" required autofocus placeholder="Username" value="{{ old('username') }}" class="w-full bg-white rounded-md px-4 py-3 placeholder-gray-500 text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-300" />
                        
                        @if($errors->has('username') && !str_contains($errors->first('username'), 'detik') && !str_contains($errors->first('username'), 'seconds'))
                            <x-input-error :messages="$errors->get('username')" class="mt-2 text-sm text-yellow-200" />
                        @endif
                    </div>

                    {{-- === INPUT PASSWORD (METODE FLEXBOX) === --}}
                    {{-- Kita buat wadah putih (bg-white) di sini, bukan di inputnya --}}
                    <div class="mb-4">
                        <div class="flex items-center w-full bg-white rounded-md overflow-hidden focus-within:ring-2 focus-within:ring-green-300">
                            
                            {{-- Input dibuat transparan (bg-transparent) agar menyatu dengan wadah --}}
                            <input 
                                id="password" 
                                name="password" 
                                type="password" 
                                required 
                                placeholder="Password" 
                                value="{{ old('password') }}" 
                                class="flex-1 bg-transparent px-4 py-3 placeholder-gray-500 text-gray-700 border-none focus:ring-0 focus:outline-none w-full" 
                            />
                            
                            {{-- Tombol Mata ditaruh SEBELAH input (bukan ditumpuk) --}}
                            <button type="button" id="togglePasswordBtn" class="px-4 text-gray-500 hover:text-gray-700 focus:outline-none cursor-pointer flex items-center justify-center h-full">
                                
                                {{-- Icon Mata (Show) --}}
                                <svg id="icon-eye" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                
                                {{-- Icon Mata Coret (Hide) --}}
                                <svg id="icon-eye-slash" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 hidden">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>

                        {{-- Error Message Password --}}
                        @if($errors->has('password'))
                            <div class="mt-2 text-left">
                                <x-input-error :messages="$errors->get('password')" class="text-sm text-yellow-200" />
                            </div>
                        @endif
                    </div>
                    {{-- === END INPUT PASSWORD === --}}

                    {{-- CHECKBOX INGAT SAYA --}}
                    <div class="flex items-center justify-start mt-2 text-sm text-white mb-6">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer select-none">
                            <input id="remember_me" type="checkbox" name="remember" class="rounded border-white focus:ring-white accent-black w-4 h-4" style="accent-color: black;">
                            <span class="ms-2 font-medium">{{ __('Ingat saya') }}</span>
                        </label>
                    </div>

                    {{-- TOMBOL LOGIN --}}
                    <div class="mt-6">
                        <button type="submit" id="btn-login" class="w-40 mx-auto bg-white text-black rounded-md py-2 px-6 hover:bg-black hover:text-white transition-all duration-200 font-bold shadow-md disabled:opacity-50 disabled:cursor-not-allowed transform active:scale-95">
                            Masuk
                        </button>
                    </div>

                    {{-- LINK LUPA PASSWORD --}}
                    <div class="mt-4 text-xs text-white/90 text-center">
                        @if (Route::has('password.request'))
                            <a class="underline hover:text-white transition-colors" href="{{ route('password.request') }}">Lupa password?</a>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- SCRIPT SWEETALERT --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('status'))
        <script>
            Swal.fire({
                title: 'Berhasil!',
                text: "{{ session('status') }}", 
                icon: 'success',
                confirmButtonText: 'Oke, Siap!',
                confirmButtonColor: '#10a83a', 
                background: '#fff',
                color: '#333'
            });
        </script>
    @endif

    {{-- JAVASCRIPT LOGIKA --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            
            // 1. LOGIKA SHOW/HIDE PASSWORD
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.getElementById('togglePasswordBtn');
            const iconEye = document.getElementById('icon-eye');          
            const iconEyeSlash = document.getElementById('icon-eye-slash'); 

            if(toggleBtn && passwordInput) {
                toggleBtn.addEventListener('click', function(e) {
                    e.preventDefault(); 
                    e.stopPropagation();

                    // Cek tipe saat ini
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);

                    // Toggle Icon
                    if (type === 'text') {
                        iconEye.classList.add('hidden');
                        iconEyeSlash.classList.remove('hidden');
                    } else {
                        iconEye.classList.remove('hidden');
                        iconEyeSlash.classList.add('hidden');
                    }
                });
            }

            // 2. LOGIKA RATE LIMIT (COUNTDOWN)
            @if ($errors->has('username'))
                const errorMessage = "{!! $errors->first('username') !!}";
                const match = errorMessage.match(/(\d+)\s*(seconds|detik)/i); 
                
                if (match) {
                    let secondsLeft = parseInt(match[1]); 
                    const alertContainer = document.getElementById('alert-container');
                    const countdownText = document.getElementById('countdown-text');
                    const btnLogin = document.getElementById('btn-login');
                    const inputs = document.querySelectorAll('input');

                    alertContainer.classList.remove('hidden');
                    
                    btnLogin.disabled = true;
                    btnLogin.innerText = "Tunggu...";
                    btnLogin.classList.add('opacity-50', 'cursor-not-allowed');
                    inputs.forEach(input => input.disabled = true);
                    if(toggleBtn) toggleBtn.disabled = true;

                    countdownText.innerHTML = `Terlalu banyak percobaan. Coba lagi dalam <strong>${secondsLeft}</strong> detik.`;

                    const interval = setInterval(() => {
                        secondsLeft--;

                        if (secondsLeft <= 0) {
                            clearInterval(interval);
                            alertContainer.classList.add('hidden');
                            btnLogin.disabled = false;
                            btnLogin.innerText = "Masuk";
                            btnLogin.classList.remove('opacity-50', 'cursor-not-allowed');
                            inputs.forEach(input => input.disabled = false);
                            if(toggleBtn) toggleBtn.disabled = false;
                        } else {
                            countdownText.innerHTML = `Terlalu banyak percobaan. Coba lagi dalam <strong>${secondsLeft}</strong> detik.`;
                        }
                    }, 1000);
                }
            @endif
        });
    </script>
</x-guest-layout>