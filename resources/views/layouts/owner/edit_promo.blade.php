@extends('layouts.owner.app')

@section('pageTitle', 'Daftar Promo')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

@php
    // DUMMY DATA PROMO (tanpa database)
    $promo = [
        'nama' => '',
        'harga' => '',
        'start' => '',
        'end' => '',
        'image' => ''
    ];
@endphp

<div class="font-['Roboto',sans-serif] bg-[#F8F6F1] min-h-screen flex justify-center items-start pt-10 sm:pt-14">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-4xl p-8 transition-all duration-300 relative">

        {{-- CLOSE BUTTON --}}
        <a href="{{ route('owner.promo') }}" class="absolute top-6 right-6 text-gray-500 hover:text-gray-700">
            <i class="fa-solid fa-xmark text-2xl"></i>
        </a>

        {{-- TITLE --}}
        <h2 class="text-2xl font-semibold text-gray-800 mb-8">Edit Promo</h2>

        {{-- FORM --}}
        <form class="space-y-6">

            {{-- ROW 1 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                {{-- Nama Promo --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        Nama Promo <span class="text-red-500">*</span>
                    </label>
                    <input type="text" value="{{ $promo['nama'] }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2
                        focus:ring-2 focus:ring-[#EED892] focus:outline-none">
                </div>

                {{-- Harga Promo --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        Harga Promo <span class="text-red-500">*</span>
                    </label>
                    <input type="number" value="{{ $promo['harga'] }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2
                        focus:ring-2 focus:ring-[#EED892] focus:outline-none">
                </div>

            </div>

            {{-- ROW 2 --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                {{-- Periode Mulai --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        Periode Mulai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" value="{{ $promo['start'] }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2
                        focus:ring-2 focus:ring-[#EED892] focus:outline-none">
                </div>

                {{-- Periode Selesai --}}
                <div>
                    <label class="block text-gray-700 font-medium mb-2">
                        Periode Selesai <span class="text-red-500">*</span>
                    </label>
                    <input type="date" value="{{ $promo['end'] }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2
                        focus:ring-2 focus:ring-[#EED892] focus:outline-none">
                </div>

            </div>

            {{-- Upload Image --}}
            <div>
                <label class="block text-gray-700 font-medium mb-2">
                    Upload Image
                </label>
                <input type="file" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-50">
                <p class="text-xs text-gray-500 mt-1">PNG, JPG, WebP, SVG — Max 2MB</p>
            </div>

            {{-- BUTTON SAVE --}}
            <div class="pt-2">
                <button type="submit"
                    class="bg-[#2F9CCA] text-white px-6 py-2 rounded-lg hover:bg-[#1788b4] transition">
                    Save
                </button>
            </div>

        </form>
    </div>
</div>

@endsection
