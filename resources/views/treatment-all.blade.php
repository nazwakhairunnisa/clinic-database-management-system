@extends('layouts.app_public')

@section('content')
<section class="bg-white px-0 sm:px-8 pt-8 sm:pt-12 pb-12 sm:pb-16 -mt-[127px]">
  <div class="max-w-7xl mx-auto w-full">

    {{-- Title --}}
    <div class="flex flex-col items-center mb-6 sm:mb-10 translate-y-[6rem] sm:translate-y-[8rem] md:translate-y-[7rem]">
      <h2 class="text-[40px] sm:text-[50px] md:text-[60px] font-abril font-bold text-[#806B3F] tracking-wide text-center">
        Treatment
      </h2>
    </div>

    {{-- Filter Button --}}
    <div class="relative w-full h-[100px] mb-8 sm:mb-12 px-6 sm:px-8">
      <a class="absolute top-[20px] sm:top-[65px] left-[200px] sm:left-[40px]
                border border-[#806B3F] text-[#806B3F] bg-[#FBF7E7] font-abril
                w-[150px] sm:w-[180px] h-[60px] sm:h-[50px]
                flex items-center justify-center
                rounded-lg text-xl sm:text-3xl font-light shadow-md">
        All
      </a>
    </div>

    {{-- Treatment Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 sm:gap-16 justify-items-center px-4 sm:px-0 mb-0">

      {{-- Card 1 --}}
      <div class="bg-white border border-[#806B3F] p-6 sm:p-8 w-full sm:w-[360px] md:w-[380px] flex flex-col shadow-lg 
                  transition-all duration-300 hover:scale-105 rounded-xl hover:shadow-2xl">
        <img src="{{ asset('images/treatment1.jpg') }}" alt="Peeling Brightening"
             class="w-full h-[220px] sm:h-[260px] md:h-[300px] rounded-lg object-cover mb-6">
        <h3 class="text-2xl sm:text-3xl font-bold text-[#806B3F] font-abril mb-3">Peeling Brightening</h3>
        <div class="bg-[#FBF7E7] border-2 border-[#806B3F] px-4 py-1 rounded-lg text-[#806B3F] font-abril font-light text-xl sm:text-2xl w-fit mb-4">
          RP 199.000
        </div>
        <p class="text-black text-[16px] sm:text-lg md:text-xl leading-relaxed">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.
        </p>
      </div>

      {{-- Card 2 --}}
      <div class="bg-white border border-[#806B3F] p-6 sm:p-8 w-full sm:w-[360px] md:w-[380px] flex flex-col shadow-lg 
                  transition-all duration-300 hover:scale-105 rounded-xl hover:shadow-2xl">
        <img src="{{ asset('images/treatment2.jpg') }}" alt="Facial Detox"
             class="w-full h-[220px] sm:h-[260px] md:h-[300px] rounded-lg object-cover mb-6">
        <h3 class="text-2xl sm:text-3xl font-bold text-[#806B3F] font-abril mb-3">Facial Detox</h3>
        <div class="bg-[#FBF7E7] border-2 border-[#806B3F] px-4 py-1 rounded-lg text-[#806B3F] font-abril font-light text-xl sm:text-2xl w-fit mb-4">
          RP 119.000
        </div>
        <p class="text-black text-[16px] sm:text-lg md:text-xl leading-relaxed">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.
        </p>
      </div>

      {{-- Card 3 --}}
      <div class="bg-white border border-[#806B3F] p-6 sm:p-8 w-full sm:w-[360px] md:w-[380px] flex flex-col shadow-lg 
                  transition-all duration-300 hover:scale-105 rounded-xl hover:shadow-2xl">
        <img src="{{ asset('images/treatment3.jpg') }}" alt="Glasskin Booster"
             class="w-full h-[220px] sm:h-[260px] md:h-[300px] rounded-lg object-cover mb-6">
        <h3 class="text-2xl sm:text-3xl font-bold text-[#806B3F] font-abril mb-3">Glasskin Booster</h3>
        <div class="bg-[#FBF7E7] border-2 border-[#806B3F] px-4 py-1 rounded-lg text-[#806B3F] font-abril font-light text-xl sm:text-2xl w-fit mb-4">
          RP 599.000
        </div>
        <p class="text-black text-[16px] sm:text-lg md:text-xl leading-relaxed">
          Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.
        </p>
      </div>

    </div>
  </div>
</section>
@endsection
