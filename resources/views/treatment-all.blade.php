@extends('layouts.app_public')
@section('content')

<section class="bg-white relative min-h-screen flex flex-col items-center justify-center text-center pt-[90px] pb-20">
  <div class="max-w-7xl mx-auto w-full">

    {{-- Title --}}
    <div class="flex flex-col items-center mb-3">
      <h2 class="text-6xl font-serif font-semibold text-[#806B3F] tracking-wide text-center relative top-4">
        Treatment
      </h2>
    </div>

    {{-- Filter --}}
    <div class="flex justify-start mb-12 px-14 relative top-5">
      <div class="bg-[#FBF7E7] border border-[#806B3F] rounded-lg px-20 py-2 flex justify-center items-center">
        <a class="text-2xl text-[#806B3F] font-serif font-semibold">All</a>
      </div>
    </div>

    {{-- Treatment Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-10 justify-items-center">

      {{-- Card 1 --}}
      <div class="bg-white border border-[#806B3F] p-6 w-[380px] h-[590px] flex flex-col justify-between shadow-md 
                  transition-transform duration-300 hover:scale-105 rounded-2xl">
        <img src="{{ asset('images/treatment1.jpg') }}" alt="Peeling Brightening"
             class="w-full h-[300px] rounded-md object-contain">
        <h3 class="text-3xl font-semibold text-[#806B3F] font-serif text-left -mt-16">Facial Detox</h3>
        <div class="bg-[#FBF7E7] border-2 border-[#806B3F] px-5 py-1 rounded-md text-[#806B3F] 
                    font-medium text-lg w-fit -mt-14">
          RP 119.000
        </div>
        <p class="text-black text-left text-xl leading-relaxed -mt-14">
          Treatment ini membantu mencerahkan wajah dan mengangkat sel kulit mati untuk hasil yang glowing maksimal.
        </p>
      </div>

      {{-- Card 2 --}}
      <div class="bg-white border border-[#806B3F] p-6 w-[380px] h-[590px] flex flex-col justify-between shadow-md 
                  transition-transform duration-300 hover:scale-105 rounded-2xl">
        <img src="{{ asset('images/treatment2.jpg') }}" alt="Peeling Brightening"
             class="w-full h-[300px] rounded-md object-contain">
        <h3 class="text-3xl font-semibold text-[#806B3F] font-serif text-left -mt-16">Facial Detox</h3>
        <div class="bg-[#FBF7E7] border-2 border-[#806B3F] px-5 py-1 rounded-md text-[#806B3F] 
                    font-medium text-lg w-fit -mt-14">
          RP 119.000
        </div>
        <p class="text-black text-left text-xl leading-relaxed -mt-14">
          Treatment ini membantu mencerahkan wajah dan mengangkat sel kulit mati untuk hasil yang glowing maksimal.
        </p>
      </div>

      {{-- Card 3 --}}
      <div class="bg-white border border-[#806B3F] p-6 w-[380px] h-[590px] flex flex-col justify-between shadow-md 
                  transition-transform duration-300 hover:scale-105 rounded-2xl">
        <img src="{{ asset('images/treatment3.jpg') }}" alt="Peeling Brightening"
             class="w-full h-[300px] rounded-md object-contain">
        <h3 class="text-3xl font-semibold text-[#806B3F] font-serif text-left -mt-16">Facial Detox</h3>
        <div class="bg-[#FBF7E7] border-2 border-[#806B3F] px-5 py-1 rounded-md text-[#806B3F] 
                    font-medium text-lg w-fit -mt-14">
          RP 119.000
        </div>
        <p class="text-black text-left text-xl leading-relaxed -mt-14">
          Treatment ini membantu mencerahkan wajah dan mengangkat sel kulit mati untuk hasil yang glowing maksimal.
        </p>
      </div>

    </div>
  </div>
</section>

@endsection
