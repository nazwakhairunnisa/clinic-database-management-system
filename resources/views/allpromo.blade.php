@extends('layouts.app_public')
@section('content')

<section class="bg-white py-20 px-8">
  <div class="max-w-7xl mx-auto">

    {{-- Judul --}}
    <div class="flex flex-col items-center mb-10">
      <h2 class="text-6xl font-serif font-semibold text-[#806B3F] tracking-wide text-center">
        Promo
      </h2>
    </div>

    {{-- Grid Promo --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12 mt-20 translate-x-4">
      
      {{-- Card 1 --}}
      <div class="text-center">
        <div class="aspect-square w-[360px] flex items-center justify-center bg-[#f8f4ec] rounded-xl shadow-md mb-8 overflow-hidden transition-transform duration-300 hover:scale-105">
          <img src="{{ asset('images/promo1.jpg') }}" alt="Promo 1" class="object-cover w-full h-full rounded-xl">
        </div>
        <p class="text-[#8C6B3F] text-3xl font-serif font-light -translate-x-4">Available until</p>
        <p class="text-[#8C6B3F] text-3xl font-serif font-light -translate-x-4">October 15 - October 25</p>
      </div>

      {{-- Card 2 --}}
      <div class="text-center">
        <div class="aspect-square w-[360px] flex items-center justify-center bg-[#f8f4ec] rounded-xl shadow-md mb-8 overflow-hidden transition-transform duration-300 hover:scale-105">
          <img src="{{ asset('images/promo4.jpg') }}" alt="Promo 2" class="object-cover w-full h-full rounded-xl">
        </div>
        <p class="text-[#8C6B3F] text-3xl font-serif font-light -translate-x-4">Available until</p>
        <p class="text-[#8C6B3F] text-3xl font-serif font-light -translate-x-4">October 15 - October 25</p>
      </div>

      {{-- Card 3 --}}
      <div class="text-center">
        <div class="aspect-square w-[360px] flex items-center justify-center bg-[#f8f4ec] rounded-xl shadow-md mb-8 overflow-hidden transition-transform duration-300 hover:scale-105">
          <img src="{{ asset('images/promo1.jpg') }}" alt="Promo 3" class="object-cover w-full h-full rounded-xl">
        </div>
        <p class="text-[#8C6B3F] text-3xl font-serif font-light -translate-x-4">Available until</p>
        <p class="text-[#8C6B3F] text-3xl font-serif font-light -translate-x-4">October 15 - October 25</p>
      </div>

      {{-- Baris ke-2 --}}
      <div class="text-center mt-8">
        <div class="aspect-square w-[360px] flex items-center justify-center bg-[#f8f4ec] rounded-xl shadow-md mb-8 overflow-hidden transition-transform duration-300 hover:scale-105">
          <img src="{{ asset('images/promo4.jpg') }}" alt="Promo 4" class="object-cover w-full h-full rounded-xl">
        </div>
        <p class="text-[#8C6B3F] text-3xl font-serif font-light -translate-x-4">Available until</p>
        <p class="text-[#8C6B3F] text-3xl font-serif font-light -translate-x-4">October 15 - October 25</p>
      </div>

      <div class="text-center mt-8">
        <div class="aspect-square w-[360px] flex items-center justify-center bg-[#f8f4ec] rounded-xl shadow-md mb-8 overflow-hidden transition-transform duration-300 hover:scale-105">
          <img src="{{ asset('images/promo1.jpg') }}" alt="Promo 5" class="object-cover w-full h-full rounded-xl">
        </div>
        <p class="text-[#8C6B3F] text-3xl font-serif font-light -translate-x-4">Available until</p>
        <p class="text-[#8C6B3F] text-3xl font-serif font-light -translate-x-4">October 15 - October 25</p>
      </div>

      <div class="text-center mt-8">
        <div class="aspect-square w-[360px] flex items-center justify-center bg-[#f8f4ec] rounded-xl shadow-md mb-8 overflow-hidden transition-transform duration-300 hover:scale-105">
          <img src="{{ asset('images/promo4.jpg') }}" alt="Promo 6" class="object-cover w-full h-full rounded-xl">
        </div>
        <p class="text-[#8C6B3F] text-3xl font-serif font-light -translate-x-4">Available until</p>
        <p class="text-[#8C6B3F] text-3xl font-serif font-light -translate-x-4">October 15 - October 25</p>
      </div>

    </div>
  </div>
</section>


@endsection
