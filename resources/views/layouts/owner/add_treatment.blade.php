@extends('layouts.owner.app')

@section('pageTitle', 'Add Treatment')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif] bg-gray-100 min-h-screen flex justify-center items-start pt-10 sm:pt-14">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-6 sm:p-8 transition-all duration-300">
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-[#806B3F]">Add Treatment</h2>
            {{-- Tombol X untuk kembali --}}
            <a href="{{ route('owner.treatment') }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </a>
        </div>

        {{-- Form --}}
        <form class="space-y-5" enctype="multipart/form-data">
            {{-- Name & Harga --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Name <span class="text-red-500">*</span></label>
                    <input type="text" placeholder=""
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm">
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Harga <span class="text-red-500">*</span></label>
                    <input type="text" placeholder=""
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm">
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="text-sm font-medium text-gray-600">Deskripsi</label>
                <textarea rows="4" placeholder="Tuliskan deskripsi treatment..."
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm resize-none"></textarea>
            </div>

            {{-- Upload Gambar --}}
            <div>
                <label class="text-sm font-medium text-gray-600">Upload Image</label>
                <div class="border border-gray-300 rounded-lg px-3 py-2 bg-gray-50">
                    <input type="file" accept=".png,.jpg,.jpeg,.webp,.svg"
                        class="w-full text-sm text-gray-600 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#EED892] file:text-[#4A3B1C] hover:file:bg-[#EBDDAF] transition-all duration-200" />
                    
                </div>
                <p class="text-xs text-gray-500 mt-1">PNG, JPG, WEBP, SVG — Max 2MB</p>
            </div>

            {{-- Tombol Submit --}}
            <div class="pt-2">
                <button type="submit"
                    class="bg-[#806B3F] text-white px-6 py-2 rounded-lg hover:bg-[#A18F5E] transition-all duration-200 shadow-md hover:shadow-lg">
                    Add
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
