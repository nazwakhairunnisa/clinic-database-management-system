@extends('layouts.app_public')

@section('content')
<section class="bg-white py-20 px-8">
  <div class="max-w-7xl mx-auto">

    {{-- Judul --}}
    <div class="flex flex-col items-center mb-10">
      <h2 class="text-7xl font-abril font-semibold text-[#806B3F] tracking-wide text-center">
        Promo
      </h2>
    </div>

    {{-- Grid Promo --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-12 mt-20">
      
      @forelse($promos as $promo)
        <div class="text-center">
          {{-- Promo Image --}}
          <div class="aspect-square w-full max-w-[360px] mx-auto flex items-center justify-center bg-[#f8f4ec] rounded-xl shadow-md mb-8 overflow-hidden transition-transform duration-300 hover:scale-105">
            @if($promo->gambar_promo)
              <img src="{{ asset('storage/' . $promo->gambar_promo) }}" 
                   alt="{{ $promo->nama_promo }}" 
                   class="object-cover w-full h-full rounded-xl">
            @else
              <div class="flex items-center justify-center w-full h-full bg-gray-200">
                <span class="text-gray-400">No Image</span>
              </div>
            @endif
          </div>

          {{-- Promo Name --}}
          <h3 class="text-[#8C6B3F] text-2xl font-abril font-semibold mb-2">
            {{ $promo->nama_promo }}
          </h3>

          {{-- Period --}}
          <p class="text-[#8C6B3F] text-xl font-serif font-light">Available until</p>
          <p class="text-[#8C6B3F] text-xl font-serif font-light">
            {{ \Carbon\Carbon::parse($promo->periode_mulai)->format('F d') }} - 
            {{ \Carbon\Carbon::parse($promo->periode_selesai)->format('F d, Y') }}
          </p>

          {{-- Price Info --}}
          <div class="mt-4">
            <span class="text-red-500 text-lg line-through">
              Rp {{ number_format($promo->treatment->harga, 0, ',', '.') }}
            </span>
            <span class="text-green-600 text-2xl font-bold ml-2">
              Rp {{ number_format($promo->harga_promo, 0, ',', '.') }}
            </span>
          </div>
        </div>
      @empty
        <div class="col-span-full text-center py-12">
          <p class="text-gray-500 text-xl">Belum ada promo tersedia saat ini</p>
        </div>
      @endforelse

    </div>

    {{-- Pagination --}}
    @if($promos->hasPages())
      <div class="mt-12 flex justify-center">
        {{ $promos->links() }}
      </div>
    @endif

  </div>
</section>
@endsection