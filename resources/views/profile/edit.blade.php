@extends('layouts.plain')
@section('content')

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto'] min-h-screen bg-white flex justify-center relative">

    <a href="{{ route('profile.show') }}"
       class="absolute left-6 top-6 text-[#6B5E4A] 
              text-3xl  ursor-pointer leading-none z-50">
        &#10006;
    </a>

    {{-- CONTAINER UTAMA --}}
    <div class="w-full max-w-2xl px-6 pt-8 pb-16 relative">

        {{-- HEADER BAR --}}
        <div class="w-full flex items-center justify-center">
            <h1 class="text-center -mt-3 text-3xl font-serif font-bold text-[#6B5E4A] pt-1">
                Edit Profile
            </h1>
        </div>

        {{-- CONTAINER FORM --}}
        <div class="w-full max-w-2xl mx-auto px-8 mt-6">

            {{-- FOTO PROFIL --}}
            <div class="flex justify-center mt-10">
                <div class="relative group cursor-pointer">

                    <img id="previewImage"
                         src="https://i.pinimg.com/736x/2c/cd/92/2ccd9299e5e85a992b7cdbaff342a2e0.jpg"
                         class="w-32 h-32 md:w-36 md:h-36
                                rounded-full object-cover border border-[#6B5E4A] shadow">

                    <div class="absolute inset-0 bg-black/40 rounded-full
                                opacity-0 group-hover:opacity-100
                                flex items-center justify-center text-white 
                                text-sm transition overlay-click">
                        Change Photo
                    </div>

                    <input type="file" id="imageInput" accept="image/*" class="hidden">

                </div>
            </div>

            {{-- FORM --}}
            <form class="mt-10 space-y-6">

                <div>
                    <label class="block text-black font-light mb-1">First Name *</label>
                    <input type="text"
                           class="w-full border border-black rounded-full px-5 py-2.5
                                  focus:ring-[#6B5E4A] focus:ring-2 focus:outline-none">
                </div>

                <div>
                    <label class="block text-black font-light mb-1">Last Name *</label>
                    <input type="text"
                           class="w-full border border-black rounded-full px-5 py-2.5
                                  focus:ring-[#6B5E4A] focus:ring-2 focus:outline-none">
                </div>

                <div>
                    <label for="dob"
                           class="block text-black font-light mb-1 cursor-pointer"
                           onclick="document.getElementById('dob').showPicker()">
                        Date of Birth *
                    </label>

                    <input id="dob" type="date"
                           onclick="this.showPicker()"
                           class="w-full border border-black rounded-full px-5 py-2.5
                                  text-[#6B5E4A] focus:ring-[#6B5E4A] focus:ring-2 focus:outline-none">
                </div>

                <button
                    class="w-full bg-[#6B5E4A] text-white py-2.5 rounded-full text-lg shadow 
                           hover:opacity-90 transition">
                    Save Changes
                </button>

            </form>

        </div>
    </div>
</div>

{{-- PREVIEW IMAGE SCRIPT --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const imageInput = document.getElementById('imageInput');
    const previewImage = document.getElementById('previewImage');
    const overlay = document.querySelector('.overlay-click');

    previewImage.addEventListener('click', () => imageInput.click());
    overlay.addEventListener('click', () => imageInput.click());

    imageInput.addEventListener('change', function () {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = e => previewImage.src = e.target.result;
            reader.readAsDataURL(file);
        }
    });
});
</script>

@endsection
