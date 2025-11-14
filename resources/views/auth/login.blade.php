<x-guest-layout>
    {{-- FULLSCREEN WRAPPER --}}
    <div class="min-h-screen w-full relative bg-white overflow-hidden">

        {{-- BACKGROUND SPLIT FULLSCREEN --}}
        <div class="absolute inset-0 grid grid-cols-2">
            <div class="bg-white"></div>

            <div class="relative">
                <img src="{{ asset('images/signup.jpg') }}"
                    alt="Spa treatment"
                    class="w-full h-full object-center transform translate-x-[30px] md:-translate-y-[180px]" />
            </div>
        </div>

        {{-- LOGO DI KANAN BAWAH --}}
        <img src="{{ asset('images/register.png') }}"
            alt="Logo"
            class="absolute bottom-0 right-0 w-40 h-40 md:w-52 md:h-52 object-contain z-50" />

        {{-- LAYER KONTEN --}}
        <div class="relative z-10 min-h-screen flex flex-col items-center md:items-start justify-center px-4 md:px-20">

            {{-- JUDUL --}}
            <h1 class="text-4xl md:text-5xl font-serif font-bold tracking-wide text-[#806B3F]
                       text-center md:text-left mb-6 md:mb-10 md:translate-x-[180px] md:translate-y-[20px]">
                Log In
            </h1>

            {{-- CARD FORM --}}
            <div class="w-full max-w-sm md:max-w-md md:w-full md:origin-top-left md:scale-95 md:translate-x-[55px]">
                <div class="bg-white rounded-lg border border-[#806B3F]
                            shadow-[6px_6px_8px_rgba(0,0,0,0.4)]
                            px-6 py-6 md:px-10 md:py-7 md:border-[1px]">

                    <!-- Session Status -->
                    <x-auth-session-status class="mb-4" :status="session('status')" />

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <!-- Email -->
                        <div>
                            <x-input-label for="email" :value="__('Email')" class="text-[#806B3F] text-xl" />
                            <x-text-input id="email"
                                class="block mt-1 w-full !rounded-3xl border border-gray-300 bg-[#f9efd7]
                                       focus:border-[#806B3F] focus:ring-[#806B3F] text-sm"
                                type="email" name="email" :value="old('email')" required autofocus
                                autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div>
                            <x-input-label for="password" :value="__('Password')" class="text-[#806B3F] text-xl" />

                            <div class="relative">
                                <x-text-input id="password"
                                    class="block mt-1 w-full !rounded-3xl border border-gray-300 bg-[#f9efd7]
                                           focus:border-[#806B3F] focus:ring-[#806B3F] text-sm pr-12"
                                    type="password" name="password" required autocomplete="current-password" />

                                {{-- IKON MATA --}}
                                <button type="button"
                                    onclick="togglePw()"
                                    class="absolute inset-y-0 right-4 flex items-center cursor-pointer">

                                    <svg id="pw-eye" xmlns="http://www.w3.org/2000/svg"
                                         class="h-6 w-6 text-gray-500"
                                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7
                                                 c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>

                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="flex items-center justify-between mt-2">
                            <label for="remember_me" class="inline-flex items-center">
                                <input id="remember_me" type="checkbox"
                                       class="rounded border-gray-300 text-[#806B3F] shadow-sm focus:ring-[#806B3F]"
                                       name="remember">
                                <span class="ms-2 text-xs sm:text-sm text-gray-700">
                                    {{ __('Remember me') }}
                                </span>
                            </label>

                            @if (Route::has('password.request'))
                                <a class="text-xs sm:text-sm text-[#806B3F] hover:underline"
                                   href="{{ route('password.request') }}">
                                    {{ __('Forgot your password?') }}
                                </a>
                            @endif
                        </div>

                        <!-- Tombol -->
                        <div class="mt-6 flex flex-col items-center gap-6">
                            <x-primary-button
                                class="!bg-[#806B3F] hover:!bg-[#6b5831] border-0 !text-sm !font-serif !font-bold px-8 py-2.5 rounded-lg justify-center">
                                {{ __('Log In') }}
                            </x-primary-button>
                        </div>

                        <!-- Link Register -->
                        <p class="text-center text-xs sm:text-sm text-gray-600 mt-6">
                            {{ __("Don't have an account?") }}
                            <a href="{{ route('register') }}"
                               class="font-semibold text-[#806B3F] hover:underline">
                                {{ __('Sign Up') }}
                            </a>
                        </p>

                    </form>

                </div>
            </div>

        </div>
    </div>
</x-guest-layout>

<script>
function togglePw() {
    const pw = document.getElementById('password');
    const eye = document.getElementById('pw-eye');

    if (pw.type === "password") {
        pw.type = "text";

        // CLOSED EYE (Benar, tidak ketarik dan bentuknya full)
        eye.outerHTML = `
            <svg id="pw-eye" xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 text-gray-500"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7
                       a10.05 10.05 0 013.237-4.594M9.88 9.88A3 3 0 0114.12 14.12M6.228 6.228L3 3m0 0l18 18m-18-18l3.228 3.228
                       m15.771 11.771A10.05 10.05 0 0021 12c-1.274-4.057-5.065-7-9.542-7
                       a10.05 10.05 0 00-1.875.175" />
            </svg>
        `;

    } else {
        pw.type = "password";

        // OPEN EYE (Normal kembali)
        eye.outerHTML = `
            <svg id="pw-eye" xmlns="http://www.w3.org/2000/svg"
                class="h-6 w-6 text-gray-500"
                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7
                       c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
            </svg>
        `;
    }
}
</script>
