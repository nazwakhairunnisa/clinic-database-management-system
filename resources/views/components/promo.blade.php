<section class="relative w-full min-h-screen overflow-hidden -mt-[64px] flex items-center justify-center">

  {{-- Background image --}}
  <div class="absolute inset-0 -z-10">
    <img 
      src="{{ asset('images/bg-promo.jpg') }}" 
      alt="hero background" 
      class="w-full h-full object-cover">
  </div>

  {{-- Ellipse overlay --}}
  <div class="absolute inset-0 flex justify-start items-start overflow-hidden">
    <img 
      src="{{ asset('images/Ellipse1.png') }}" 
      alt="ellipse background"
      class="w-[700px] h-auto -translate-x-32 -translate-y-[95px] object-contain"
    />
  </div>

  {{-- Konten utama --}}
  <div class="relative w-full max-w-7xl px-6 flex flex-col md:flex-row items-start justify-between gap-8">

    {{-- Teks Promo --}}
    <div class="z-10 flex flex-col mt-[60px] pl-[20px] md:mt-[50px] md:pl-[40px]">
      <h2 class="font-abril font-semibold text-[#8C6B3F] text-[64px] mb-5 -translate-x-2">
        Promo
      </h2>
      <p class="text-black text-[25px] leading-snug max-w-[400px] md:ml-5">
        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. 
        Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. 
        Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
      </p>
    </div>

    {{-- Kartu promo kanan --}}
    <div class="bg-white/70 shadow-md py-6 px-4 w-[320px] md:w-[310px] flex flex-col items-center relative mx-auto mt-6 md:mt-0 md:translate-x-[40px]">
      
      <a href="{{ route('allpromo') }}" 
         class="text-[#8C6B3F] text-3xl font-abril font-light hover:underline hover:decoration-[#8C6B3F] mb-4 self-end mr-2 transition-all duration-200">
        View More >>
      </a>

      <div class="overflow-hidden w-full max-w-[300px] md:max-w-[400px] lg:max-w-[450px] mx-auto">
        <img src="{{ asset('images/promo1.jpg') }}" alt="Promo 1" 
             class="w-[85%] h-auto object-cover rounded-xl transition-all duration-300 mx-auto">
        <div class="p-3 text-center">
          <p class="text-[#8C6B3F] text-3xl font-abril font-light">Available until</p>
          <p class="text-[#8C6B3F] text-2xl font-abril font-light">October 15 - October 25</p>
        </div>
      </div>

      <div class="overflow-hidden w-full mt-6">
        <img src="{{ asset('images/promo4.jpg') }}" alt="Promo 2" 
             class="w-[85%] h-auto object-cover rounded-xl transition-all duration-300 mx-auto">
      </div>

    </div>

  </div>

</section>
