@vite('resources/css/app.css')

<nav class="fixed top-0 left-0 w-full bg-white shadow-[0_4px_20px_rgba(150,150,150,0.2)] z-50">
    <div class="max-w-7xl mx-auto flex items-center justify-between px-8 h-[90px]">

        {{-- LOGO --}}
        <div class="flex items-center">
            <img src="{{ asset('images/logo.jpg') }}" 
                 alt="Logo" 
                 class="h-[60px] w-auto object-contain">
        </div>

        {{-- NAV MENU --}}
        <ul class="hidden md:flex items-center space-x-6 text-[#806B3F] text-[22px] font-light ml-24">

            <li><a href="{{ url('/') }}#home" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Home</a></li>

            <li><a href="{{ url('/') }}#about" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">About Us</a></li>

            <li><a href="{{ url('/') }}#treatment" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Treatment</a></li>

            <li><a href="{{ url('/') }}#promo" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Promo</a></li>

            <li><a href="{{ url('/') }}#contact" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Contact</a></li>

            {{-- RESERVATION --}}
            <li>
                <a href="{{ route('user.reservation.my') }}" 
                   class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">
                   Reservations
                </a>
            </li>

        </ul>

        {{-- PROFILE ICON --}}
        <div class="hidden md:block">
            <a href="{{ route('profile.show') }}">
                <img src="{{ asset('images/profile.png') }}"
                     class="h-[60px] w-[60px] object-contain scale-125 p-1 hover:opacity-70 transition">
            </a>
        </div>

        {{-- MOBILE MENU BUTTON --}}
        <button id="menu-btn-auth" class="md:hidden text-[#806B3F] focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none"
                 viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>

    </div>

    {{-- MOBILE MENU --}}
    <div id="mobile-menu-auth"
         class="hidden md:hidden flex flex-col items-center py-4 space-y-3 text-lg text-[#806B3F] bg-white shadow-md">

        <a href="{{ url('/') }}#home" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Home</a>
        <a href="{{ url('/') }}#about" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">About Us</a>
        <a href="{{ url('/') }}#treatment" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Treatment</a>
        <a href="{{ url('/') }}#promo" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Promo</a>
        <a href="{{ url('/') }}#contact" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Contact</a>

        <a href="{{ route('user.reservation.my') }}" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Reservations</a>

        <a href="{{ route('profile.show') }}" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Profile</a>

    </div>
</nav>

<script>
    document.getElementById('menu-btn-auth').addEventListener('click', () => {
        document.getElementById('mobile-menu-auth').classList.toggle('hidden');
    });
</script>
