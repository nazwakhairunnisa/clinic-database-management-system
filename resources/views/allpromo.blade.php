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

{{-- Certificate --}}
<section class="py-20 bg-white flex flex-col items-center relative overflow-hidden">
  <h2 class="text-6xl font-serif font-semibold text-[#806B3F] mb-12">Certificate</h2>

  <div id="certWrapper" class="flex gap-8 overflow-x-auto scroll-smooth snap-x snap-mandatory w-full px-[50vw]">
    <div class="flex-none w-[50vw]"></div>

    <div class="cert flex-none w-[500px] h-[650px] snap-center cursor-pointer transition-all duration-700 ease-in-out">
      <img src="{{ asset('images/sertifikat1.jpg') }}" class="w-full h-full object-contain transition-all duration-700 ease-in-out" alt="Certificate 1">
    </div>

    <div class="cert flex-none w-[500px] h-[650px] snap-center cursor-pointer transition-all duration-700 ease-in-out">
      <img src="{{ asset('images/sertifikat2.jpg') }}" class="w-full h-full object-contain transition-all duration-700 ease-in-out" alt="Certificate 2">
    </div>

    <div class="cert flex-none w-[500px] h-[650px] snap-center cursor-pointer transition-all duration-700 ease-in-out">
      <img src="{{ asset('images/sertifikat3.jpg') }}" class="w-full h-full object-contain transition-all duration-700 ease-in-out" alt="Certificate 3">
    </div>

    <div class="flex-none w-[50vw]"></div>
  </div>
</section>

<style>
#certWrapper::-webkit-scrollbar {
  display: none;
}

.cert img {
  opacity: 0.5;
  transform: scale(0.85);
  filter: blur(1px);
  margin-top: -100px;
  transition: all 0.6s ease;
}

.cert.active img {
  opacity: 1;
  transform: translateY(-15px) scale(1.05);
  filter: drop-shadow(10px 12px 5px rgba(0, 0, 0, 0.35))
          drop-shadow(3px 5px 10px rgba(128, 107, 63, 0.25));
}

.cert.active:hover img {
  transform: translateY(-15px) scale(1.05);
}
</style>

<script>
document.addEventListener("DOMContentLoaded", () => {
  const certs = document.querySelectorAll(".cert");
  const wrapper = document.getElementById("certWrapper");

  let activeIndex = 1;
  certs[activeIndex].classList.add("active");
  centerCert(certs[activeIndex]);

  certs.forEach((cert, i) => {
    cert.addEventListener("click", () => {
      certs.forEach(c => c.classList.remove("active"));
      cert.classList.add("active");
      centerCert(cert);
      activeIndex = i;
    });
  });

  function centerCert(cert) {
    const offset = cert.offsetLeft - (wrapper.offsetWidth / 2) + (cert.offsetWidth / 2);
    wrapper.scrollTo({ left: offset, behavior: "smooth" });
  }
});
</script>

@endsection
