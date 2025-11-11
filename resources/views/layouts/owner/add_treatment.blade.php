@extends('layouts.owner.app')

@section('pageTitle', 'Add Treatment')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif] bg-gray-100 min-h-screen flex justify-center items-start pt-10 sm:pt-14">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-6 sm:p-8 transition-all duration-300">
    {{-- Header --}}
    <div class="max-w-4xl mx-auto py-6">
        <div class="bg-white rounded-2xl shadow p-6">

            <h2 class="text-2xl font-bold text-[#806B3F] mb-6">Add Treatment</h2>

            {{-- Tombol X untuk kembali --}}
            <a href="{{ route('owner.treatment') }}" class="absolute top-6 right-6 text-gray-500 hover:text-gray-700">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </a>

            {{-- Form --}}
            <form action="{{ route('owner.treatment.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Name & Harga --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_treatment" value="{{ old('nama_treatment') }}"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#EED892] focus:outline-none @error('nama_treatment') border-red-500 @enderror"
                            placeholder="Contoh: Facial Rejuvenation">
                        @error('nama_treatment')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-gray-700 font-medium mb-2">Harga <span class="text-red-500">*</span></label>
                        <input type="number" name="harga" value="{{ old('harga') }}" step="0.01" min="0"
                            class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#EED892] focus:outline-none @error('harga') border-red-500 @enderror"
                            placeholder="Contoh: 350000">
                        @error('harga')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Durasi --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Durasi (menit) <span class="text-red-500">*</span></label>
                    <input type="number" name="durasi" value="{{ old('durasi') }}" min="1"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#EED892] focus:outline-none @error('durasi') border-red-500 @enderror"
                        placeholder="Contoh: 60">
                    @error('durasi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Deskripsi --}}
                <div class="mb-6">
                    <label class="block text-gray-700 font-medium mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="4"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#EED892] focus:outline-none @error('deskripsi') border-red-500 @enderror"
                        placeholder="Deskripsi singkat tentang treatment">{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Upload Gambar --}}
                <div class="mb-8">
                    <label class="block text-gray-700 font-medium mb-2">Upload Image</label>
                    <input type="file" name="foto_treatment" accept="image/*"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#EED892] focus:outline-none @error('foto_treatment') border-red-500 @enderror"
                        onchange="previewImage(event)">
                    @error('foto_treatment')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror

                    <p class="text-sm text-gray-500 mt-1">PNG, JPG, WEBP, SVG — Max 2MB</p>

                    {{-- Image Preview --}}
                    <div id="imagePreview" class="mt-3 hidden">
                        <img id="preview" class="w-48 h-48 object-cover rounded-lg border">
                    </div>
                </div>

                {{-- Tombol Submit --}}
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('owner.treatment') }}"
                        class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-[#806B3F] text-white px-6 py-2 rounded-lg hover:bg-[#A18F5E] transition">
                        Add
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(event) {
            const preview = document.getElementById('preview');
            const previewContainer = document.getElementById('imagePreview');
            const file = event.target.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>

@endsection