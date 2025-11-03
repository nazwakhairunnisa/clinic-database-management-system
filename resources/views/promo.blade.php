@extends('layouts.app_public')
@section('content')

<section class="relative h-[100vh] -mt-[10px] overflow-hidden">

  {{-- Background image --}}
  <div class="absolute inset-0 z-0">
    <img 
      src="{{ asset('images/bg-promo.jpg') }}" 
      alt="hero background" 
      class="w-full h-full object-cover"
    >
  </div>

  {{-- Ellipse overlay --}}
  <div class="absolute inset-0 flex justify-start items-start overflow-hidden">
    <img 
      src="{{ asset('images/Ellipse1.png') }}" 
      alt="ellipse background"
      class="w-[700px] h-auto -translate-x-32 -translate-y-[95px] object-contain"
    />
  </div>

  {{-- Teks Promo --}}
  <div class="relative z-10 flex flex-col justify-start h-full pl-[100px] pt-[60px]">
    <h2 class="font-serif font-semibold text-[#8C6B3F] text-[64px] mb-5 -translate-x-8">
      Promo
    </h2>
    <p class="text-black text-[25px] leading-snug max-w-[400px] -translate-x-2">
      Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
      incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
      exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
      dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
    </p>
  </div>

  {{-- Bagian kanan (overlay putih + konten promo) --}}
  <div class="absolute top-0 right-[130px] w-[400px] bg-white/70 shadow-md py-6 flex flex-col items-center overflow-hidden">

    {{-- View More --}}
    <a href="{{ route('allpromo') }}" 
       class="relative z-20 text-[#8C6B3F] text-3xl font-serif font-light
              hover:underline hover:decoration-[#8C6B3F] mb-4 self-end mr-4 transition-all duration-200">
      View More >>
    </a>

    {{-- Promo 1 --}}
    <div class="rounded-xl">
      <img src="{{ asset('images/promo1.jpg') }}" alt="Promo 1" class="w-full h-[265px] rounded-xl">
      <div class="p-3 text-center">
        <p class="text-[#8C6B3F] text-2xl font-serif font-light">Available until</p>
        <p class="text-[#8C6B3F] text-2xl font-serif font-light">October 15 - October 25</p>
      </div>
    </div>

    {{-- Promo 2 --}}
    <div class="rounded-xl mt-6">
      <img src="{{ asset('images/promo4.jpg') }}" alt="Promo 2" class="w-full h-[265px] rounded-xl object-cover">
    </div>

  </div>

</section>
@endsection
