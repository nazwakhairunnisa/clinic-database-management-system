@extends('layouts.app_public')
@section('content')

<section class="bg-white relative pt-[120px] pb-10">
  <div class="max-w-6xl mx-auto flex flex-col md:flex-row items-start justify-between px-8 gap-12">

    {{-- MAPS --}}
    <div class="w-full md:w-1/2 flex justify-center -translate-y-8">
      <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1994.476146985937!2d98.46397753869334!3d3.762128293299712!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3031324dc8bfc5bb%3A0x9c82d2c84a3d60a3!2sClay%20Aesthetic%20Clinic!5e0!3m2!1sid!2sid!4v1698256495874!5m2!1sid!2sid"
        width="100%"
        height="350"
        style="border:0; border-radius:10px;"
        allowfullscreen
        loading="lazy"
        referrerpolicy="no-referrer-when-downgrade">
      </iframe>
    </div>

    {{-- Konten Teks --}}
    <div class="flex flex-col items-center md:items-end text-center md:text-right space-y-4 -translate-y-8">
      <h2 class="font-serif font-semibold text-6xl text-[#806B3F] mb-7">
        Contact Us
      </h2>

      <p class="text-2xl text-[#806B3F] font-serif">Jl. Jendral Ahmad Yani (Kp.Kruni), Stabat</p>
      <p class="text-2xl text-[#806B3F] font-serif">082161835144</p>
      <p class="text-2xl text-[#806B3F] font-serif">@clayskinthetic</p>
      <p class="text-2xl text-[#806B3F] font-serif">Senin - Sabtu 09.00 - 18.00 WIB</p>
    </div>
  </div>
</section>

<footer 
  class="relative text-white py-16 px-8 mt-0 bg-cover bg-center"
  style="background-image: url('{{ asset('images/footer-bg.jpg') }}');">

  {{-- Overlay warna agar teks lebih jelas --}}
  <div class="absolute inset-0 bg-[#000000]/25"></div>

  {{-- Konten Footer --}}
  <div class="relative z-10 max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-start md:items-center gap-8">

    {{-- Logo --}}
    <div class="flex flex-col items-center md:items-start translate-x-[70px] translate-y-[10px]">
      <img 
        src="{{ asset('images/footer-logo.png') }}"
        alt="Clay Skinthetic Clinic"
        class="w-[120px] mb-2 scale-[2] origin-center -translate-y-[10px]">
    </div>

    {{-- Contact Info --}}
    <div class="text-center md:text-left -mt-[50px] -ml-[210px]">
      <h3 class="font-serif font-semibold text-3xl mb-2">Contact Information</h3>
      <p class="text-xl leading-tight">082161835144</p>
      <p class="text-xl leading-tight">Jl. Jendral Ahmad Yani<br>(Kp.Kruni), Stabat</p>
    </div>

    {{-- Social Media --}}
    <div class="text-center md:text-left">
      <h3 class="font-serif font-semibold text-3xl -mt-[60px] -translate-x-[190px] mb-2">Our Social Media</h3>
      <a 
        href="https://www.instagram.com/clayskinthetic?igsh=aTRuaDh1dW5xb25l"
        target="_blank"
        class="-ml-[190px] inline-block transition-transform duration-300 hover:scale-110 hover:brightness-125">
        <img 
          src="{{ asset('images/instagram.png') }}" 
          alt="Instagram"
          class="w-[60px] h-[60px]">
      </a>
    </div>
  </div>

  {{-- Garis bawah + copyright --}}
  <div class="relative z-10 flex justify-center translate-y-[25px]">
    <div class="w-[95%] border-t border-white text-right text-[18px] pr-10 translate-y-[2px]">
      <span class="relative top-[18px]">© 2025 Clay Skinthetic Clinic. All rights reserved.</span>
    </div>
  </div>

</footer>
@endsection
