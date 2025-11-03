@extends('layouts.app_public')
@section('content')

<section class="bg-white min-h-screen flex items-center justify-center py-16 px-8">
  <div class="max-w-7xl mx-auto w-full">

    {{-- Title --}}
    <div class="flex flex-col items-center mb-3">
      <h2 class="text-6xl font-serif font-semibold text-[#806B3F] tracking-wide text-center relative top-4">
        Treatment
      </h2>
    </div>

    {{-- View More --}}
    <div class="flex justify-end mb-12 pr-6">
      <a href="{{ route('treatment.all') }}"
         class="text-[#806B3F] font-serif font-light hover:underline text-2xl relative top-5">
        View More >>
      </a>
    </div>

    {{-- Treatment Cards --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-19 justify-items-center">

      {{-- Card 1 --}}
      <div class="bg-white border border-[#806B3F] p-6 w-[380px] h-[590px] flex flex-col justify-between shadow-lg
                  transition-transform duration-300 hover:scale-105 rounded-2xl">
        <img src="{{ asset('images/treatment1.jpg') }}" alt="Peeling Brightening"
             class="w-full h-[300px] rounded-lg object-contain">
        <h3 class="text-3xl font-semibold text-[#806B3F] font-serif text-left -mt-16">Facial Detox</h3>
        <div class="bg-[#FBF7E7] border-2 border-[#806B3F] px-5 py-1 rounded-lg text-[#806B3F] font-serif font-light text-2xl w-fit -mt-14">
          RP 119.000
        </div>
        <p class="text-black text-left text-xl leading-relaxed -mt-14">
          Treatment ini membantu mencerahkan wajah dan mengangkat sel kulit mati untuk hasil yang glowing maksimal.
        </p>
      </div>

      {{-- Card 2 --}}
      <div class="bg-white border border-[#806B3F] p-6 w-[380px] h-[590px] flex flex-col justify-between shadow-lg
                  transition-transform duration-300 hover:scale-105 rounded-2xl">
        <img src="{{ asset('images/treatment2.jpg') }}" alt="Peeling Brightening"
             class="w-full h-[300px] rounded-lg object-contain">
        <h3 class="text-3xl font-semibold text-[#806B3F] font-serif text-left -mt-16">Facial Detox</h3>
        <div class="bg-[#FBF7E7] border-2 border-[#806B3F] px-5 py-1 rounded-lg text-[#806B3F] font-serif font-light text-2xl w-fit -mt-14">
          RP 119.000
        </div>
        <p class="text-black text-left text-xl leading-relaxed -mt-14">
          Treatment ini membantu mencerahkan wajah dan mengangkat sel kulit mati untuk hasil yang glowing maksimal.
        </p>
      </div>

      {{-- Card 3 --}}
      <div class="bg-white border border-[#806B3F] p-6 w-[380px] h-[590px] flex flex-col justify-between shadow-lg
                  transition-transform duration-300 hover:scale-105 rounded-2xl">
        <img src="{{ asset('images/treatment3.jpg') }}" alt="Peeling Brightening"
             class="w-full h-[300px] rounded-lg object-contain">
        <h3 class="text-3xl font-semibold text-[#806B3F] font-serif text-left -mt-16">Facial Detox</h3>
        <div class="bg-[#FBF7E7] border-2 border-[#806B3F] px-5 py-1 rounded-lg text-[#806B3F] font-serif font-light text-2xl w-fit -mt-14">
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
