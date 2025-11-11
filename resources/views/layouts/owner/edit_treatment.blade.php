@extends('layouts.owner.app')

@section('pageTitle', 'Daftar Treatment')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif] bg-gray-100 min-h-screen flex justify-center items-start pt-10 sm:pt-14">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-6 sm:p-8 transition-all duration-300">
        
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-[#806B3F]">Edit Treatment</h2>
            {{-- Tombol X untuk kembali --}}
            <a href="{{ route('owner.treatment') }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </a>
        </div>

        {{-- Form Edit --}}
        <form action="{{ route('owner.treatment.update', $treatment->id_treatment) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PUT')

            {{-- Name & Harga --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="text-sm font-medium text-gray-600">Name <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_treatment"
                        value="{{ old('nama_treatment', $treatment->nama_treatment) }}"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm @error('nama_treatment') border-red-500 @enderror">
                    @error('nama_treatment')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-sm font-medium text-gray-600">Harga <span class="text-red-500">*</span></label>
                    <input type="number" name="harga"
                        value="{{ old('harga', $treatment->harga) }}" step="0.01" min="0"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm @error('harga') border-red-500 @enderror">
                    @error('harga')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="text-sm font-medium text-gray-600">Deskripsi</label>
                <textarea name="deskripsi" rows="4"
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm resize-none @error('deskripsi') border-red-500 @enderror">{{ old('deskripsi', $treatment->deskripsi) }}</textarea>
                @error('deskripsi')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Upload Gambar --}}
            <div>
                <label class="text-sm font-medium text-gray-600">Upload Image</label>

                @if($treatment->foto_treatment)
                    <div class="mt-2 mb-3">
                        <img src="{{ $treatment->foto_url }}" alt="Current" class="w-40 h-40 object-cover rounded-lg border">
                        <p class="text-xs text-gray-500 mt-1">Foto saat ini</p>
                    </div>
                @endif

                <div class="border border-gray-300 rounded-lg px-3 py-2 bg-gray-50">
                    <input type="file" name="foto_treatment" accept=".png,.jpg,.jpeg,.webp,.svg"
                        class="w-full text-sm text-gray-600 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:text-sm file:font-medium file:bg-[#EED892] file:text-[#4A3B1C] hover:file:bg-[#EBDDAF] transition-all duration-200 @error('foto_treatment') border-red-500 @enderror"
                        onchange="previewImage(event)">
                </div>
                <p class="text-xs text-gray-500 mt-1">PNG, JPG, WEBP, SVG — Max 2MB<br>Biarkan kosong jika tidak ingin mengubah foto</p>
                @error('foto_treatment')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror

                {{-- Preview Gambar Baru --}}
                <div id="imagePreview" class="mt-3 hidden">
                    <p class="text-sm font-medium mb-1">Preview foto baru:</p>
                    <img id="preview" class="w-40 h-40 object-cover rounded-lg border">
                </div>
            </div>

            {{-- Tombol Save --}}
            <div class="pt-2 flex justify-end space-x-3">
                <a href="{{ route('owner.treatment') }}"
                    class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-all duration-200 shadow-sm">
                    Batal
                </a>
                <button type="submit"
                    class="bg-[#806B3F] text-white px-6 py-2 rounded-lg hover:bg-[#A18F5E] transition-all duration-200 shadow-md hover:shadow-lg">
                    Save
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
