@vite('resources/css/app.css')

{{-- Iconify --}}
  <script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<nav class="fixed top-0 left-0 w-full bg-white shadow-[0_4px_20px_rgba(150,150,150,0.2)] z-50">
  <div class="max-w-7xl mx-auto flex items-center justify-between pl-8 pr-12 h-[90px]">

    {{-- LOGO --}}
    <div class="flex items-center">
      <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-[70px] w-auto object-contain">
    </div>

    {{-- MENU NAV --}}
    <div class="ml-[210px]">
      <ul class="hidden md:flex items-center space-x-6 text-[#806B3F] text-[20px] font-light">
        <li><a href="{{ url('/') }}#home" class="px-4 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Home</a></li>
        <li><a href="{{ url('/') }}#about" class="px-4 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">About Us</a></li>
        <li><a href="{{ url('/') }}#treatment" class="px-4 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Treatment</a></li>
        <li><a href="{{ url('/') }}#promo" class="px-4 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Promo</a></li>
        <li><a href="{{ url('/') }}#contact" class="px-4 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Contact</a></li>
        
        {{-- TAMPILKAN RESERVATIONS HANYA UNTUK USER BIASA (role: user) --}}
        @auth
          @if(auth()->user()->isUser())
            <li>
              <a href="{{ route('user.reservasi.my') }}" 
                class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">
                Reservations
              </a>
            </li>
          @endif
        @endauth
      </ul>
    </div>

    <div class="flex-1"></div>

    {{-- KANAN: Tampilkan berdasarkan status login & role --}}
    <div class="flex items-right space-x-6">
      @auth
        @if(auth()->user()->isUser())
          {{-- User Biasa: Tampilkan Icon Profile --}}
          <a href="{{ route('user.profile.show') }}">
            <img src="{{ asset('images/profile.png') }}"
                 class="h-[60px] w-[60px] object-contain scale-123 p-1 hover:opacity-70 transition">
          </a>

          {{-- TOMBOL LOGOUT --}}
          <form method="POST" action="{{ route('logout') }}" class="inline">
            @csrf
            <button type="submit" class="flex items-center justify bg-[#FBF7E7]/80 rounded-lg hover:bg-gray-300 px-3 py-5 transition">
              <iconify-icon icon="tabler:logout" class="text-2xl text-gray-700"></iconify-icon>
            </button>
          </form>
        @elseif(auth()->user()->isSuperAdmin() || auth()->user()->isDokter())
          {{-- Owner/Dokter: Tombol ke Dashboard Owner --}}
          <a href="{{ route('owner.dashboard') }}"
             class="border border-[#806B3F] bg-[#FBF7E7] text-[#806B3F] font-abril font-semibold
                    text-[20px] px-8 py-3 rounded-lg hover:bg-[#806B3F] hover:text-white
                    transition-all duration-200">
            Dashboard
          </a>
        @elseif(auth()->user()->isAdmin())
          {{-- Admin: Tombol ke Dashboard Admin --}}
          <a href="{{ route('admin.dashboard') }}"
             class="border border-[#806B3F] bg-[#FBF7E7] text-[#806B3F] font-abril font-semibold
                    text-[20px] px-8 py-3 rounded-lg hover:bg-[#806B3F] hover:text-white
                    transition-all duration-200">
            Dashboard
          </a>
        @endif
      @else
        {{-- Belum Login: Tampilkan Tombol Sign Up --}}
        <a href="{{ route('register') }}"
           class="border border-[#806B3F] bg-[#FBF7E7] text-[#806B3F] font-abril font-semibold
                  text-[22px] px-10 py-3 rounded-lg hover:bg-[#806B3F] hover:text-white
                  transition-all duration-200">
          Sign Up
        </a>
      @endauth
    </div>

    {{-- HAMBURGER MENU (mobile) --}}
    <button id="menu-btn" class="md:hidden text-[#806B3F] focus:outline-none">
      <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
              d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
  </div>

  {{-- MENU MOBILE --}}
  <div id="mobile-menu"
      class="hidden absolute top-[90px] left-0 w-full bg-white shadow-md md:hidden flex flex-col items-center py-4 space-y-3 text-lg text-[#806B3F]">
    <a href="{{ url('/') }}#home" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Home</a>
    <a href="{{ url('/') }}#about" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">About Us</a>
    <a href="{{ url('/') }}#treatment" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Treatment</a>
    <a href="{{ url('/') }}#promo" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Promo</a>
    <a href="{{ url('/') }}#contact" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Contact</a>
    
    @auth
    @if(auth()->user()->isUser())
      {{-- Menu untuk User Biasa --}}
      <a href="{{ route('user.reservasi.my') }}" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Reservations</a>
      <a href="{{ route('user.profile.show') }}" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Profile</a>
      
      {{-- TAMBOL LOGOUT --}}
      <form method="POST" action="{{ route('logout') }}" class="w-full text-center">
        @csrf
        <button type="submit" 
                class="px-5 py-2 rounded-xl hover:bg-red-100 text-red-600 transition-all duration-300">
          Logout
        </button>
      </form>
    @elseif(auth()->user()->isSuperAdmin() || auth()->user()->isDokter())
      {{-- Menu untuk Owner/Dokter --}}
      <a href="{{ route('owner.dashboard') }}" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Dashboard</a>
    @elseif(auth()->user()->isAdmin())
      {{-- Menu untuk Admin --}}
      <a href="{{ route('admin.dashboard') }}" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Dashboard</a>
    @endif
  @else
    {{-- Tombol Sign Up untuk yang belum login --}}
    <a href="{{ route('register') }}"
      class="border border-[#806B3F] text-[#806B3F] px-6 py-2 rounded-lg bg-[#FBF7E7] font-abril font-semibold
              hover:bg-[#806B3F] hover:text-white transition-all duration-200">
      Sign Up
    </a>
  @endauth
  </div>

</nav>

<script>
  const menuBtn = document.getElementById('menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  menuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
  });
</script>