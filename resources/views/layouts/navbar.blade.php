@vite('resources/css/app.css')

<nav class="fixed top-0 left-0 w-full shadow-md z-50 h-[90px] flex items-center bg-white">
    <div class="max-w-7xl mx-auto w-full flex items-center justify-between px-8">

        {{-- logo --}}
        <div class="flex items-center">
            <img src="{{ asset('images/logo.jpg') }}" alt="Logo" class="h-[60px] auto ml-[-px]">
        </div>

        {{-- menu --}}
        <ul class="hidden md:flex space-x-6 text-[20px] ml-[120px] font-md text-[#806B3F]">
            <li><a href="{{ route('home') }}"class="relative px-5 py-2 rounded-xl transition-all duration-400 ease-in-out
              hover:bg-[#FBF7E7]/80">
              Home</a></li>

            <li><a href="{{ route('about') }}"class="relative px-5 py-2 rounded-xl transition-all duration-400 ease-in-out
              hover:bg-[#FBF7E7]/80">
              About Us</a></li>

            <li><a href="{{ route('treatment') }}"class="relative px-5 py-2 rounded-xl transition-all duration-400 ease-in-out
              hover:bg-[#FBF7E7]/80">
              Treatment</a></li>

            <li><a href="{{ route('promo') }}"class="relative px-5 py-2 rounded-xl transition-all duration-400 ease-in-out
              hover:bg-[#FBF7E7]/80">
              Promo</a></li>

            <li><a href="{{ route('Contact') }}"class="relative px-5 py-2 rounded-xl transition-all duration-400 ease-in-out
              hover:bg-[#FBF7E7]/80">
              Contact</a></li>
        </ul>

        {{-- sign up button --}}
        <div>
            <a href="#" 
               class="border border-[#806B3F] text-[#806B3F] text-[17px] bg-[#FBF7E7]  px-10 py-3 rounded-lg font-serif font-semibold
               hover:bg-[#806B3F] hover:text-white hover:scale-105 transition-all duration-200">
                Sign Up
            </a>
        </div>
    </div>
</nav>
