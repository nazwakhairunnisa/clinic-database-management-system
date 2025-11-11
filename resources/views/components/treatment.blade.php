<section class="bg-white px-0 sm:px-8 pt-8 sm:pt-12 pb-12 sm:pb-16 -mt-[127px]">
  <div class="max-w-7xl mx-auto w-full">

    {{-- Title --}}
    <div class="flex flex-col items-center mb-6 sm:mb-10 translate-y-2 sm:translate-y-4 md:translate-y-12">
      <h2 class="text-[40px] sm:text-[50px] md:text-[60px] font-abril font-bold text-[#806B3F] tracking-wide text-center">
        Treatment
      </h2>
    </div>

    {{-- View More --}}
    <div class="flex justify-center sm:justify-end mb-10 sm:mb-12 px-4 sm:px-0">
      <a href="{{ route('treatment.all') }}"
         class="text-[#806B3F] font-abril font-light hover:underline text-[20px] sm:text-[26px] md:text-[30px]">
        View More &gt;&gt;
      </a>
    </div>

    {{-- Treatment Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-16 justify-items-center px-4 sm:px-0 mb-0">

      {{-- Card 1 --}}
      <div class="bg-white border border-[#806B3F] p-6 sm:p-8 w-full sm:w-[360px] md:w-[380px] h-auto flex flex-col shadow-lg 
                  transition-all duration-300 hover:scale-105 rounded-xl hover:shadow-2xl">
        <img src="{{ asset('images/treatment1.jpg') }}" alt="Facial Detox"
             class="w-full h-[220px] sm:h-[260px] md:h-[300px] rounded-lg object-cover mb-6">
        <h3 class="text-2xl sm:text-3xl font-bold text-[#806B3F] font-abril mb-3 text-left">Facial Detox</h3>
        <div class="bg-[#FBF7E7] border-2 border-[#806B3F] px-4 py-1 rounded-lg text-[#806B3F] font-abril font-light text-xl sm:text-2xl w-fit mb-4">
          RP 119.000
        </div>
        <p class="text-black text-left text-[16px] sm:text-lg md:text-xl leading-relaxed">
          Treatment ini membantu mencerahkan wajah dan mengangkat sel kulit mati untuk hasil yang glowing maksimal.
        </p>
      </div>

      {{-- Card 2 --}}
      <div class="bg-white border border-[#806B3F] p-6 sm:p-8 w-full sm:w-[360px] md:w-[380px] h-auto flex flex-col shadow-lg 
                  transition-all duration-300 hover:scale-105 rounded-xl hover:shadow-2xl">
        <img src="{{ asset('images/treatment2.jpg') }}" alt="Peeling Brightening"
             class="w-full h-[220px] sm:h-[260px] md:h-[300px] rounded-lg object-cover mb-6">
        <h3 class="text-2xl sm:text-3xl font-bold text-[#806B3F] font-abril mb-3 text-left">Peeling Brightening</h3>
        <div class="bg-[#FBF7E7] border-2 border-[#806B3F] px-4 py-1 rounded-lg text-[#806B3F] font-abril font-light text-xl sm:text-2xl w-fit mb-4">
          RP 129.000
        </div>
        <p class="text-black text-left text-[16px] sm:text-lg md:text-xl leading-relaxed">
          Membantu mengangkat sel kulit mati dan membuat kulit tampak cerah alami serta lebih halus.
        </p>
      </div>

      {{-- Card 3 --}}
      <div class="bg-white border border-[#806B3F] p-6 sm:p-8 w-full sm:w-[360px] md:w-[380px] h-auto flex flex-col shadow-lg 
                  transition-all duration-300 hover:scale-105 rounded-xl hover:shadow-2xl">
        <img src="{{ asset('images/treatment3.jpg') }}" alt="Glow Booster"
             class="w-full h-[220px] sm:h-[260px] md:h-[300px] rounded-lg object-cover mb-6">
        <h3 class="text-2xl sm:text-3xl font-bold text-[#806B3F] font-abril mb-3 text-left">Glow Booster</h3>
        <div class="bg-[#FBF7E7] border-2 border-[#806B3F] px-4 py-1 rounded-lg text-[#806B3F] font-abril font-light text-xl sm:text-2xl w-fit mb-4">
          RP 149.000
        </div>
        <p class="text-black text-left text-[16px] sm:text-lg md:text-xl leading-relaxed">
          Treatment yang menutrisi kulit secara mendalam agar tampak lebih segar dan bercahaya sepanjang hari.
        </p>
      </div>

    </div>
  </div>
</section>
