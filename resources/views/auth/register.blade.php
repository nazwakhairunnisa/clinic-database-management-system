<x-guest-layout>
    <div class="min-h-screen w-full relative bg-white overflow-hidden">

        {{-- BACKGROUND SPLIT FULLSCREEN --}}
        <div class="absolute inset-0 grid grid-cols-2">
            <div class="bg-white"></div>
            <div class="relative">
                <img src="{{ asset('images/signup.jpg') }}" alt="Spa treatment"
                     class="w-full h-full object-center transform translate-x-[30px] md:-translate-y-[180px]" />
            </div>
        </div>

        {{-- LOGO --}}
        <img src="{{ asset('images/register.png') }}" alt="Logo"
             class="absolute bottom-0 right-0 w-40 h-40 md:w-52 md:h-52 object-contain z-50" />

        {{-- LAYER KONTEN --}}
        <div class="relative z-10 min-h-screen flex flex-col items-center md:items-start justify-center px-4 md:px-20">

            <h1 class="text-4xl md:text-5xl font-serif font-bold tracking-wide text-[#806B3F] text-center md:text-left mb-6 md:mb-10 md:translate-x-[160px] md:translate-y-[20px]">
                Sign Up
            </h1>

            <div class="w-full max-w-sm md:max-w-md md:w-full md:origin-top-left md:scale-95 md:translate-x-[55px]">
                <div class="bg-white rounded-lg border border-[#806B3F] shadow-[6px_6px_8px_rgba(0,0,0,0.4)] px-6 py-6 md:px-10 md:py-7">

                    <form method="POST" action="{{ route('register') }}" class="space-y-4">
                        @csrf

                        {{-- Username --}}
                        <div>
                            <x-input-label for="name" :value="__('Username')" class="text-[#806B3F] text-lg" />
                            <x-text-input id="name" type="text" name="name" :value="old('name')" required autofocus
                                          class="block mt-1 w-full !rounded-3xl border border-gray-300 bg-[#f9efd7] focus:border-[#806B3F] focus:ring-[#806B3F] text-sm" />
                        </div>

                        {{-- Phone Number --}}
                        <div>
                            <x-input-label for="phone" :value="__('Phone Number')" class="text-[#806B3F] text-lg" />
                            <x-text-input id="phone" type="text" name="phone" :value="old('phone')" required
                                          class="block mt-1 w-full !rounded-3xl border border-gray-300 bg-[#f9efd7] focus:border-[#806B3F] focus:ring-[#806B3F] text-sm" />
                        </div>

                        {{-- Email --}}
                        <div>
                            <x-input-label for="email" :value="__('Email')" class="text-[#806B3F] text-lg" />
                            <x-text-input id="email" type="email" name="email" :value="old('email')" required
                                          class="block mt-1 w-full !rounded-3xl border border-gray-300 bg-[#f9efd7] focus:border-[#806B3F] focus:ring-[#806B3F] text-sm" />
                        </div>

                        {{-- Password --}}
                        <div>
                            <x-input-label for="password" :value="__('Password')" class="text-[#806B3F] text-lg" />
                            <div class="relative">
                                <x-text-input id="password" type="password" name="password" required
                                              class="block mt-1 w-full !rounded-3xl border border-gray-300 bg-[#f9efd7] focus:border-[#806B3F] focus:ring-[#806B3F] text-sm pr-12" />
                                <button type="button" onclick="togglePw('password', this)"
                                        class="absolute inset-y-0 right-4 flex items-center cursor-pointer">
                                    <svg class="h-6 w-6 text-gray-500 eye-open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <svg class="h-6 w-6 text-gray-500 eye-closed hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 3l18 18M4.5 4.5A10.05 10.05 0 002.458 12c1.274 4.057 5.065 7 9.542 7 2.053 0 3.977-.62 5.563-1.682M9.88 9.88A3 3 0 0114.12 14.12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-[#806B3F] text-lg" />
                            <div class="relative">
                                <x-text-input id="password_confirmation" type="password" name="password_confirmation" required
                                              class="block mt-1 w-full !rounded-3xl border border-gray-300 bg-[#f9efd7] focus:border-[#806B3F] focus:ring-[#806B3F] text-sm pr-12" />
                                <button type="button" onclick="togglePw('password_confirmation', this)"
                                        class="absolute inset-y-0 right-4 flex items-center cursor-pointer">
                                    <svg class="h-6 w-6 text-gray-500 eye-open" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <svg class="h-6 w-6 text-gray-500 eye-closed hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 3l18 18M4.5 4.5A10.05 10.05 0 002.458 12c1.274 4.057 5.065 7 9.542 7 2.053 0 3.977-.62 5.563-1.682M9.88 9.88A3 3 0 0114.12 14.12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <div class="mt-6 flex flex-col items-center gap-6">
                            <x-primary-button class="!bg-[#806B3F] hover:!bg-[#6b5831] border-0 !text-sm !font-serif !font-bold px-8 py-2.5 rounded-lg justify-center">
                                {{ __('Sign Up') }}
                            </x-primary-button>
                        </div>

                        {{-- Link Login --}}
                        <p class="text-center text-xs sm:text-sm text-gray-600 mt-6">
                            {{ __('Already registered?') }}
                            <a href="{{ route('login') }}" class="font-semibold text-[#806B3F] hover:underline">
                                {{ __('Log in') }}
                            </a>
                        </p>

                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>

<script>
function togglePw(inputId, btn) {
    const pw = document.getElementById(inputId);
    const eyeOpen = btn.querySelector('.eye-open');
    const eyeClosed = btn.querySelector('.eye-closed');

    if (pw.type === "password") {
        pw.type = "text";
        eyeOpen.classList.add('hidden');
        eyeClosed.classList.remove('hidden');
    } else {
        pw.type = "password";
        eyeOpen.classList.remove('hidden');
        eyeClosed.classList.add('hidden');
    }
}
</script>
