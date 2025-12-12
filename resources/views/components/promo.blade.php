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
        Dapatkan penawaran terbaik untuk perawatan kulit Anda! 
        Promo spesial dengan harga terjangkau dan hasil maksimal. 
        Jangan lewatkan kesempatan emas ini untuk tampil lebih percaya diri dengan kulit yang sehat dan bercahaya.
      </p>
    </div>

    {{-- Kartu promo kanan --}}
    <div class="bg-white/70 shadow-md py-6 px-4 w-[320px] md:w-[310px] flex flex-col items-center relative mx-auto mt-6 md:mt-0 md:translate-x-[40px]">
      
      <a href="{{ route('allpromo') }}" 
         class="text-[#8C6B3F] text-3xl font-abril font-light hover:underline hover:decoration-[#8C6B3F] mb-4 self-end mr-2 transition-all duration-200">
        View More >>
      </a>

      @if($promos->isNotEmpty())
        {{-- Promo Pertama --}}
        @php $firstPromo = $promos->first(); @endphp
        <div class="overflow-hidden w-full max-w-[300px] md:max-w-[400px] lg:max-w-[450px] mx-auto">
          @if($firstPromo->gambar_promo)
            <img src="{{ asset('storage/' . $firstPromo->gambar_promo) }}" 
                 alt="{{ $firstPromo->nama_promo }}" 
                 class="w-[85%] h-auto object-cover rounded-xl transition-all duration-300 mx-auto">
          @else
            <div class="w-[85%] h-48 bg-gray-200 rounded-xl flex items-center justify-center mx-auto">
              <span class="text-gray-400">No Image</span>
            </div>
          @endif
          
          <div class="p-3 text-center">
            <p class="text-[#8C6B3F] text-2xl font-abril font-semibold mb-1">
              {{ $firstPromo->nama_promo }}
            </p>
            <p class="text-[#8C6B3F] text-xl font-abril font-light">Available until</p>
            <p class="text-[#8C6B3F] text-lg font-abril font-light">
              {{ \Carbon\Carbon::parse($firstPromo->periode_mulai)->format('M d') }} - 
              {{ \Carbon\Carbon::parse($firstPromo->periode_selesai)->format('M d, Y') }}
            </p>
          </div>
        </div>

        {{-- Promo Kedua (jika ada) --}}
        @if($promos->count() > 1)
          @php $secondPromo = $promos->skip(1)->first(); @endphp
          <div class="overflow-hidden w-full mt-6">
            @if($secondPromo->gambar_promo)
              <img src="{{ asset('storage/' . $secondPromo->gambar_promo) }}" 
                   alt="{{ $secondPromo->nama_promo }}" 
                   class="w-[85%] h-auto object-cover rounded-xl transition-all duration-300 mx-auto">
            @else
              <div class="w-[85%] h-32 bg-gray-200 rounded-xl flex items-center justify-center mx-auto">
                <span class="text-gray-400">No Image</span>
              </div>
            @endif
          </div>
        @endif
      @else
        {{-- Fallback jika belum ada promo --}}
        <div class="text-center py-8">
          <p class="text-gray-500 text-lg">Belum ada promo tersedia saat ini</p>
          <p class="text-gray-400 text-sm mt-2">Nantikan promo menarik dari kami!</p>
        </div>
      @endif

    </div>

  </div>

</section>