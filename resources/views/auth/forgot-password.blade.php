<x-guest-layout>
    <div class="min-h-[calc(100vh-4rem)] flex items-center justify-center px-4 sm:px-6 py-10">
        <div class="w-full max-w-md">
            <div class="bg-white/90 backdrop-blur rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <div class="mb-6">
                    <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">
                        {{ __('Forgot your password?') }}
                    </h1>
                    <p class="text-sm text-gray-600 mt-1">
                        {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <x-primary-button class="w-full justify-center">
                        {{ __('Email Password Reset Link') }}
                    </x-primary-button>
                </form>
            </div>
        </div>
    </div>
</x-guest-layout>
