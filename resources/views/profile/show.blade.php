@extends('layouts.plain')

@section('title', 'Profile')
@section('content')

<div class="font-['Roboto'] min-h-screen bg-white">

    <!-- ===================== HEADER BOX ===================== -->
    <div class="bg-[#F6F2E7] border-b border-[#C6B99D] pt-6 pb-8 px-6 w-full relative">

        <!-- Tombol X -->
        <a href="{{ route('home') }}"
           class="absolute left-6 top-4 text-[#6B5E4A] text-5xl font-bold cursor-pointer">
            &times;
        </a>

        <!-- Title -->
        <h1 class="text-center text-2xl md:text-3xl font-serif font-bold text-[#6B5E4A]">
            Profile
        </h1>

        <!-- Garis -->
        <div class="h-[2px] bg-[#C6B99D] mt-6 w-full"></div>

        <!-- Foto -->
        <div class="flex justify-center mt-9">
            <div class="relative w-fit">

                <img src="https://i.pinimg.com/736x/2c/cd/92/2ccd9299e5e85a992b7cdbaff342a2e0.jpg"
                     class="w-32 h-32 md:w-36 md:h-36 rounded-full object-cover border-2 border-[#6B5E4A]">

                <a href="{{ route('profile.edit') }}"
                   class="absolute bottom-1 right-1 
                          w-9 h-9 md:w-12 md:h-12
                          flex items-center justify-center 
                          bg-[#6B5E4A] text-white 
                          rounded-full border border-black shadow 
                          text-lg md:text-2xl">
                    <img src="{{ asset('images/pencil.png') }}" class="w-4 h-4 md:w-6 md:h-6" alt="">
                </a>
            </div>
        </div>

        <p class="mt-6 text-center text-2xl font-light text-[#6B5E4A]">
            Jane Doe 23
        </p>
    </div>

    <!-- ===================== HISTORY SECTION ===================== -->
    <div class="w-full bg-white pt-10 px-6 pb-20">

        <!-- Judul History -->
        <h2 class="text-3xl font-serif font-bold text-[#6B5E4A] mb-4 ml-4 md:ml-6 lg:ml-8">
            History
        </h2>

        <!-- Nama Bulan -->
        <p class="text-2xl text-black font-normal mb-4 ml-4 md:ml-6 lg:ml-8">
            October
        </p>

        <div class="history-item w-full max-w-[88%] mx-auto
                    border border-black rounded-lg 
                    px-5 py-5 shadow-sm cursor-pointer
                    flex justify-between items-center">

            <span class="text-black font-light text-[20px]">
                Wednesday, 10 October 2025
            </span>

            <span class="text-black font-medium text-[20px]">
                14:00
            </span>
        </div>

        @include('profile.partials.modal-reservation')
    </div>

    {{-- JS untuk modal --}}
    <script>
        document.querySelectorAll('.history-item').forEach(item => {
            item.addEventListener('click', function () {
                document.getElementById('historyModal').classList.remove('hidden');
            });
        });

        document.getElementById('historyModal')
            .addEventListener('click', function(e) {
                if (e.target === this) this.classList.add('hidden');
            });
    </script>

</div>

@endsection
