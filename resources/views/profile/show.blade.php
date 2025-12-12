@extends('layouts.app_public')

@section('title', 'Profile')

@section('content')
<div class="font-['Roboto'] min-h-screen bg-white">

    <!-- HEADER BOX -->
    <div class="bg-[#F6F2E7] border-b border-[#C6B99D] pt-6 pb-8 px-6 w-full relative">

        <!-- Tombol X -->
        <a href="{{ route('user.dashboard') }}"
           class="absolute left-6 top-4 text-[#6B5E4A] text-5xl font-bold cursor-pointer hover:text-[#4A3F2E]">
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

                <img src="{{ asset('images/profile.png') }}"
                     class="w-32 h-32 md:w-36 md:h-36 rounded-full object-cover border-2 border-[#6B5E4A]">

                <a href="{{ route('user.profile.edit') }}"
                   class="absolute bottom-1 right-1 
                          w-9 h-9 md:w-12 md:h-12
                          flex items-center justify-center 
                          bg-[#6B5E4A] text-white 
                          rounded-full border border-black shadow 
                          text-lg md:text-2xl hover:bg-[#4A3F2E] transition">
                    <img src="{{ asset('images/pencil.png') }}" class="w-4 h-4 md:w-6 md:h-6" alt="Edit">
                </a>
            </div>
        </div>

        <p class="mt-6 text-center text-2xl font-light text-[#6B5E4A]">
            {{ Auth::user()->username }}
        </p>

        <p class="text-center text-sm text-gray-600 mt-2">
            {{ Auth::user()->email }}
        </p>
    </div>

    <!-- QUICK STATS -->
    <div class="w-full bg-white pt-8 px-6">
        <div class="max-w-4xl mx-auto grid grid-cols-2 gap-6">
            
            <a href="{{ route('user.reservasi.my') }}"
               class="bg-[#F6F2E7] border border-[#C6B99D] rounded-lg p-6 text-center hover:bg-[#EDE7D8] transition">
                <p class="text-3xl font-bold text-[#6B5E4A]">{{ $totalReservations ?? 0 }}</p>
                <p class="text-sm text-gray-600 mt-2">Total Reservasi</p>
            </a>

            <div class="bg-[#F6F2E7] border border-[#C6B99D] rounded-lg p-6 text-center">
                <p class="text-3xl font-bold text-green-600">{{ $completedReservations ?? 0 }}</p>
                <p class="text-sm text-gray-600 mt-2">Selesai</p>
            </div>

        </div>
    </div>

    <!-- HISTORY SECTION -->
    <div class="w-full bg-white pt-10 px-6 pb-20">

        <!-- Header dengan tombol View All -->
        <div class="flex justify-between items-center mb-6 ml-4 md:ml-6 lg:ml-8 mr-4 md:mr-6 lg:mr-8">
            <h2 class="text-3xl font-serif font-bold text-[#6B5E4A]">
                Recent History
            </h2>
            <a href="{{ route('user.reservasi.my') }}" 
               class="text-[#806B3F] hover:underline text-lg">
                View All &gt;&gt;
            </a>
        </div>

        @if(isset($recentReservations) && $recentReservations->isNotEmpty())
            @php
                $groupedRecent = $recentReservations->groupBy(function($item) {
                    return \Carbon\Carbon::parse($item->tanggal_reservasi)->format('F Y');
                });
            @endphp

            @foreach($groupedRecent as $month => $monthReservations)
                <!-- Nama Bulan -->
                <p class="text-2xl text-black font-normal mb-4 ml-4 md:ml-6 lg:ml-8">
                    {{ $month }}
                </p>

                <div class="space-y-3 mb-6">
                    @foreach($monthReservations->take(3) as $reservation)
                        <div class="history-item w-full max-w-[88%] mx-auto
                                    border border-black rounded-lg 
                                    px-5 py-5 shadow-sm cursor-pointer hover:bg-gray-50 transition
                                    flex justify-between items-center"
                             onclick="window.location.href='{{ route('user.reservasi.my') }}'">

                            <span class="text-black font-light text-[18px]">
                                {{ \Carbon\Carbon::parse($reservation->tanggal_reservasi)->format('l, d F Y') }}
                            </span>

                            <span class="text-black font-medium text-[20px]">
                                {{ \Carbon\Carbon::parse($reservation->jam_reservasi)->format('H:i') }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @else
            <div class="text-center py-12">
                <p class="text-gray-500 text-lg mb-4">Belum ada riwayat reservasi</p>
                <a href="{{ route('user.reservasi.create') }}" 
                   class="inline-block bg-[#806B3F] text-white px-8 py-3 rounded-lg hover:bg-[#6B5831] transition">
                    Buat Reservasi Sekarang
                </a>
            </div>
        @endif

    </div>

</div>
@endsection