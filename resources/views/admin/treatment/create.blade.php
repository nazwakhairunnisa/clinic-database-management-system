@extends('layouts.admin')

@section('pageTitle', 'Daftar Treatment')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

{{-- BACKGROUND + CENTER --}}
<div class="font-['Roboto',sans-serif] bg-[#F8F6F1] min-h-screen flex justify-center items-start pt-10 sm:pt-14">

    {{-- CARD WRAPPER --}}
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-6 sm:p-8 relative">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-black">Add Treatment</h2>

            {{-- Close Button (X) --}}
            <a href="{{ route('admin.treatment.index') }}"
                class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </a>
        </div>

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong>Error:</strong> {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                <strong>Validation Errors:</strong>
                <ul class="list-disc list-inside mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORM --}}
        <form action="{{ route('admin.treatment.store') }}" method="POST" enctype="multipart/form-data" id="treatmentForm">
            @csrf

            {{-- Name & Harga --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-5">
                <div>
                    <label class="text-sm font-medium text-gray-600">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_treatment" value="{{ old('nama_treatment') }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1
                        focus:ring-2 focus:ring-[#EED892] outline-none @error('nama_treatment') border-red-500 @enderror"
                        placeholder="">
                    @error('nama_treatment')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="text-sm font-medium text-gray-600">Harga <span class="text-red-500">*</span></label>
                    <input type="number" name="harga" value="{{ old('harga') }}" step="0.01" min="0"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1
                        focus:ring-2 focus:ring-[#EED892] outline-none @error('harga') border-red-500 @enderror"
                        placeholder="">
                    @error('harga')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

           
    <div class="mb-5">
        <label class="text-sm font-medium text-gray-600">Durasi (menit) <span class="text-red-500">*</span></label>
        <input type="number" name="durasi" value="{{ old('durasi') }}" min="1"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1
            focus:ring-2 focus:ring-[#EED892] outline-none @error('durasi') border-red-500 @enderror"
            placeholder="">
        @error('durasi')
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

            {{-- Deskripsi --}}
            <div class="mb-5">
                <label class="text-sm font-medium text-gray-600">Deskripsi</label>
                <textarea name="deskripsi" rows="4"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1
                    focus:ring-2 focus:ring-[#EED892] outline-none @error('deskripsi') border-red-500 @enderror"
                    placeholder="">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Upload Image --}}
            <div class="mb-6">
                <label class="text-sm font-medium text-gray-600">Upload Image</label>
                <input type="file" name="foto_treatment" accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 mt-1
                    focus:ring-2 focus:ring-[#EED892] outline-none @error('foto_treatment') border-red-500 @enderror"
                    onchange="previewImage(event)">
                @error('foto_treatment')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                <p class="text-xs text-gray-500 mt-1">PNG, JPG, WEBP — Max 2MB</p>

                {{-- Preview --}}
                <div id="imagePreview" class="mt-3 hidden">
                    <img id="preview" class="w-40 h-40 object-cover rounded-lg border">
                </div>
            </div>

            {{-- Submit Buttons --}}
           <div class="flex justify-start space-x-3">
    <button type="submit"
        class="bg-[#2F9CCA] text-white px-6 py-2 rounded-lg hover:bg-[#1788b4] transition">
        Add
    </button>

        </form>

    </div>
</div>

<script>
function previewImage(event) {
    const preview = document.getElementById('preview');
    const container = document.getElementById('imagePreview');
    const file = event.target.files[0];

    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            preview.src = e.target.result;
            container.classList.remove('hidden');
        }
        reader.readAsDataURL(file);
    }
}
</script>

@endsection
