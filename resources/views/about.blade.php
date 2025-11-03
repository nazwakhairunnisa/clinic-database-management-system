@extends('layouts.app_public')

@section('content')

<section class="relative z-10 bg-white pt-[90px]">
    <div style="max-width: 1200px; margin: 0 auto; padding: 0 24px;">
        <div style="display: flex; flex-wrap: wrap; gap: 32px; align-items: flex-start;">

        {{-- teks About Us --}}
        <div style="flex: 1; min-width: 300px; margin-bottom: 48px;">
            <h2 class="font-serif font-semibold text-[60px] text-[#806B3F] mb-6">
                About Us</h2>  
            <p style="font-size: 28px; color: #000000ff; line-height: 1.2; padding-left: 50px; max-width: 600px;">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud
                exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
                dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
            </p>
        </div>

        {{-- gambar About Us --}}
        <div style="margin-bottom: 24px;">
            <img src="{{ asset('images/about.jpg') }}" alt="About Us" class="w-full h-[450px] object-cover">

        </div>

    </div>


<style>
  body {
    overflow-x: hidden;
  }
</style>

<section class="relative left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] w-screen flex justify-center bg-[#FBF7E7] border-y-2 border-[#bfa16c] py-8 mt-16">
  <div class="flex justify-between items-center w-full max-w-5xl text-center gap-16">

    <div class="flex flex-col items-center gap-6">
      <h3 class="font-serif font-semibold text-6xl -translate-x-20 text-[#806B3F]">29</h3>
      <p class="text-2xl text-black -translate-x-20">Total Treatment</p>
    </div>
    <div class="w-[2px] h-28 -translate-x-12 bg-[#bfa16c]"></div>

    <div class="flex flex-col items-center gap-6">
      <h3 class="font-serif font-semibold text-6xl -translate-x-5 text-[#806B3F]">100+</h3>
      <p class="text-2xl text-black -translate-x-5">Patients</p>
    </div>
    <div class="w-[2px] h-28  bg-[#bfa16c]"></div>

    <div class="flex flex-col items-center gap-6">
      <h3 class="font-serif font-semibold text-6xl translate-x-8 text-[#806B3F]">11</h3>
      <p class="text-2xl text-black translate-x-8">Certificate</p>
    </div>
    <div class="w-[2px] h-28 translate-x-20 bg-[#bfa16c]"></div>

    <div class="flex flex-col items-center space-y-3">
      <h3 class="font-serif font-semibold text-6xl translate-x-24 text-[#806B3F]">3+</h3>
      <p class="text-2xl text-black translate-x-24">Years of Experience</p>
    </div>

  </div>

@endsection
