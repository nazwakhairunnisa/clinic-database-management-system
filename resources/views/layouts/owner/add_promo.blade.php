@extends('layouts.owner.app')

@section('pageTitle', 'Daftar Promo')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif] bg-[#F8F6F1] min-h-screen flex justify-center items-start pt-10 sm:pt-14">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-6 sm:p-8 transition-all duration-300">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-BLACK">Add Promo</h2>
            <a href="{{ route('owner.promo') }}" 
               class="text-gray-400 hover:text-gray-600 transition">
               <i class="fa-solid fa-xmark text-xl"></i>
            </a>
        </div>

        {{-- FORM --}}
<form class="space-y-5">

    {{-- ROW 1 --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-medium text-gray-600">
                Nama Promo <span class="text-red-500">*</span>
            </label>
            <input type="text"
                class="w-full border border-gray-300 rounded-lg px-3 py-2
                focus:ring-2 focus:ring-[#EED892] outline-none text-sm">
        </div>

        <div>
            <label class="text-sm font-medium text-gray-600">
                Harga Promo <span class="text-red-500">*</span>
            </label>
            <input type="text"
                class="w-full border border-gray-300 rounded-lg px-3 py-2
                focus:ring-2 focus:ring-[#EED892] outline-none text-sm">
        </div>
    </div>

    {{-- ROW 2 --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label class="text-sm font-medium text-gray-600">Periode Mulai</label>
            <input type="date"
                class="w-full border border-gray-300 rounded-lg px-3 py-2
                focus:ring-2 focus:ring-[#EED892] outline-none text-sm">
        </div>

        <div>
            <label class="text-sm font-medium text-gray-600">Periode Selesai</label>
            <input type="date"
                class="w-full border border-gray-300 rounded-lg px-3 py-2
                focus:ring-2 focus:ring-[#EED892] outline-none text-sm">
        </div>
    </div>

    {{-- IMAGE UPLOAD --}}
    <div>
        <label class="text-sm font-medium text-gray-600">Upload Image</label>
        <input type="file" accept="image/*"
            onchange="previewImage(event)"
            class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-gray-50">
        <p class="text-xs text-gray-500 mt-1">PNG, JPG, WebP, SVG — Max 2MB</p>

        {{-- PREVIEW --}}
        <div id="imagePreview" class="mt-3 hidden">
            <p class="text-sm font-medium mb-1">Preview foto:</p>
            <img id="preview" class="w-40 h-40 object-cover rounded-lg border">
        </div>
    </div>

    {{-- BUTTON SAVE --}}
    <div class="pt-2">
        <button type="submit"
            class="bg-[#2F9CCA] text-white px-6 py-2 rounded-lg hover:bg-[#1788b4] transition">
            Save
        </button>
    </div>

</form>

{{-- SCRIPT PREVIEW --}}
<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const wrapper = document.getElementById('imagePreview');

    preview.src = URL.createObjectURL(event.target.files[0]);
    wrapper.classList.remove('hidden');
}
</script>


@endsection