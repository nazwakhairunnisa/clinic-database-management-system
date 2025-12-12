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

        {{-- ERROR MESSAGES --}}
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat error pada form:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

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

            {{-- Durasi --}}
            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Durasi (menit) <span class="text-red-500">*</span></label>
                <input type="number" name="durasi" value="{{ old('durasi', $treatment->durasi) }}" min="1"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#EED892] focus:outline-none @error('durasi') border-red-500 @enderror">
                @error('durasi')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
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
                <div class="mb-3" id="currentImage">
                    <img src="{{ $treatment->foto_url }}" alt="Current" class="w-48 h-48 object-cover rounded-lg border">
                    <p class="text-sm text-gray-500 mt-1">Foto saat ini</p>
                </div>
                @endif
                
                <input type="file" name="foto_treatment" accept="image/*" id="fotoInput"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#EED892] focus:outline-none @error('foto_treatment') border-red-500 @enderror"
                    onchange="previewImage(event)">
                <p class="text-sm text-gray-500 mt-1">Biarkan kosong jika tidak ingin mengubah foto</p>
                @error('foto_treatment')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
                
                {{-- Image Preview untuk foto BARU --}}
                <div id="imagePreview" class="mt-3 hidden">
                    <p class="text-sm font-medium mb-2 text-green-600">Preview foto baru:</p>
                    <img id="preview" class="w-48 h-48 object-cover rounded-lg border">
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
