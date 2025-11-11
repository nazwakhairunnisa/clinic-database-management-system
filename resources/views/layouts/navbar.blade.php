@vite('resources/css/app.css')

<nav class="fixed top-0 left-0 w-full bg-white shadow-[0_4px_20px_rgba(150,150,150,0.2)] z-50">
  <div class="max-w-7xl mx-auto flex items-center justify-between pl-8 pr-12 h-[90px]">

    {{-- LOGO --}}
    <div class="flex items-center">
      <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-[70px] w-auto object-contain">
    </div>

    {{-- MENU NAV --}}
    <div class="ml-[210px]">
      <ul class="hidden md:flex items-center space-x-8 text-[#806B3F] text-[24px] font-light">
        <li><a href="{{ url('/') }}#home" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Home</a></li>
        <li><a href="{{ url('/') }}#about" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">About Us</a></li>
        <li><a href="{{ url('/') }}#treatment" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Treatment</a></li>
        <li><a href="{{ url('/') }}#promo" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Promo</a></li>
        <li><a href="{{ url('/') }}#contact" class="px-5 py-2 rounded-xl hover:bg-[#FBF7E7]/80 transition-all duration-300">Contact</a></li>
      </ul>
    </div>

    <div class="flex-1"></div>

    {{-- BUTTON SIGN UP (desktop) --}}
    <div class="hidden md:block">
      <a href="#"
         class="border border-[#806B3F] bg-[#FBF7E7] text-[#806B3F] font-abril font-semibold
                text-[22px] px-10 py-3 rounded-lg hover:bg-[#806B3F] hover:text-white
                transition-all duration-200">
        Sign Up
      </a>
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
    <a href="#"
      class="border border-[#806B3F] text-[#806B3F] px-6 py-2 rounded-lg bg-[#FBF7E7] font-abril font-semibold
              hover:bg-[#806B3F] hover:text-white transition-all duration-200">
      Sign Up
    </a>
  </div>

</nav>

<script>
  const menuBtn = document.getElementById('menu-btn');
  const mobileMenu = document.getElementById('mobile-menu');
  menuBtn.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
  });
</script>
