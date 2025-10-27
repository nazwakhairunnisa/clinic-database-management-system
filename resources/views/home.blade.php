@extends('layouts.app')

@section('content')
<div class="relative w-full h-screen overflow-hidden">

    <img src="{{ asset('images/bg.jng') }}" alt="Banner Clay Skinthetic" class="absolute inset-0 w-full h-full object-cover">

    <div class="absolute inset-0 bg-black bg-opacity-30"></div>

    <div class="absolute inset-0 flex flex-col items-center justify-center text-center text-white px-4">
        <h1 class="text-4xl md:text-5xl font-bold mb-4 drop-shadow-lg">Reveal Your Glow Within’</h1>
        <span class="bg-[#f5e6c8] text-[#6b4f2b] font-semibold text-xl px-4 py-2 rounded-md shadow-md mb-6">
            Clay Skinthetic Clinic
        </span>
        <a href="#" class="bg-[#f5e6c8] text-[#6b4f2b] font-semibold px-5 py-2 rounded-md hover:bg-[#e5d5b8] transition">
            Book Now
        </a>
    </div>

    <!-- ikon WhatsApp di pojok kanan bawah -->
    <a href="https://wa.me/6281234567890" target="_blank"
       class="absolute bottom-5 right-5">
        <img src=""
             alt="WhatsApp" class="w-14 h-14">
    </a>
</div>
@endsection
