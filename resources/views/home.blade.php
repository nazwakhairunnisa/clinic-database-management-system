@extends('layouts.app_public')
@section('content')

<section class="relative h-[100vh] flex items-center justify-center text-center -mt-[10px]">

  {{-- Background image --}}
  <div class="absolute inset-0 z-0">
    <img 
      src="{{ asset('images/bg.jpg') }}" 
      alt="hero background" 
      class="w-full h-full object-cover">
  </div>

  {{-- Overlay --}}
  <div class="relative z-20 text-white -mt-[20px]">
    <h1 class="text-[55px] font-serif font-semibold pt-14 pb-8 [text-shadow:2px_2px_8px_rgba(0,0,0,1)]">
      Reveal Your Glow Within’
    </h1>

    <p class="text-[35px] font-serif font-semibold text-[#806B3F] bg-[#FBF7E7]/90 inline-block px-6 py-2 rounded-lg shadow-md border border-[#806B3F]">
      Clay Skinthetic Clinic
    </p>

    <div class="mt-[110px]">
      <a href="#"
         class="bg-[#FBF7E7] text-[#806B3F] text-2xl px-6 py-3 rounded-lg font-serif font-semibold border border-[#806B3F]
         hover:bg-[#806B3F]/70 hover:text-white hover:scale-105 transition-all duration-200">
        Book Now
      </a>
    </div>
  </div>

  {{-- Tombol WhatsApp --}}
  <a href="https://wa.me/628123456789" target="_blank"
     class="absolute bottom-3 right-5 z-20">
    <img src="{{ asset('images/wa.png') }}" alt="WhatsApp"
         class="w-14 h-14 drop-shadow-lg hover:scale-110 transition-transform duration-300">
  </a>

</section>
@endsection
