@extends('layouts.app_public')

@section('title', 'My Reservations')

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
            My Reservations
        </h1>

        <!-- Garis -->
        <div class="h-[2px] bg-[#C6B99D] mt-6 w-full"></div>
    </div>

    <!-- CONTENT -->
    <div class="w-full bg-white pt-10 px-6 pb-20">

        @if(isset($message))
            <!-- Belum ada reservasi -->
            <div class="text-center py-12">
                <p class="text-gray-500 text-xl mb-6">{{ $message }}</p>
                <a href="{{ route('user.reservasi.create') }}" 
                   class="inline-block bg-[#806B3F] text-white px-8 py-3 rounded-lg hover:bg-[#6B5831] transition">
                    Buat Reservasi Pertama
                </a>
            </div>
        @else
            <!-- Group by bulan -->
            @php
                $groupedReservations = $reservations->groupBy(function($item) {
                    return \Carbon\Carbon::parse($item->tanggal_reservasi)->format('F Y');
                });
            @endphp

            @foreach($groupedReservations as $month => $monthReservations)
                <div class="mb-10">
                    <!-- Nama Bulan -->
                    <h2 class="text-2xl text-[#6B5E4A] font-semibold mb-4 ml-4 md:ml-6 lg:ml-8">
                        {{ $month }}
                    </h2>

                    <!-- Reservasi List -->
                    <div class="space-y-4">
                        @foreach($monthReservations as $reservation)
                            <div class="history-item w-full max-w-[88%] mx-auto
                                        border border-black rounded-lg 
                                        px-5 py-5 shadow-sm cursor-pointer
                                        hover:bg-gray-50 transition
                                        flex justify-between items-center"
                                 data-reservation="{{ json_encode($reservation) }}">

                                <div class="flex-1">
                                    <span class="text-black font-light text-[18px] block">
                                        {{ \Carbon\Carbon::parse($reservation->tanggal_reservasi)->format('l, d F Y') }}
                                    </span>
                                    <span class="text-sm text-gray-600">
                                        Status: 
                                        <span class="font-semibold
                                            @if($reservation->status == 'confirmed') text-green-600
                                            @elseif($reservation->status == 'requested') text-blue-600
                                            @elseif($reservation->status == 'cancelled') text-red-600
                                            @else text-gray-600
                                            @endif">
                                            {{ ucfirst($reservation->status) }}
                                        </span>
                                    </span>
                                </div>

                                <span class="text-black font-medium text-[20px]">
                                    {{ \Carbon\Carbon::parse($reservation->jam_reservasi)->format('H:i') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
            <div>
                <a href="{{ route('user.reservasi.create') }}"
                    class="bg-[#FBF7E7] text-[#806B3F] text-lg text-lg md:text-2xl px-8 py-3 rounded-lg 
                            font-abril font-bold border border-[#806B3F] flex justify-center items-center
                            hover:bg-[#806B3F]/70 hover:text-white hover:scale-105 transition-all duration-200 inline-block
                            translate-y-0 md:translate-y-24">
                    Add New Reservation
                </a>
            </div>
        @endif

    </div>

    <!-- Modal Detail Reservasi -->
    @include('user.reservasi.modal-detail')

</div>

<script>
document.querySelectorAll('.history-item').forEach(item => {
    item.addEventListener('click', function () {
        const data = JSON.parse(this.dataset.reservation);
        
        // Populate modal with reservation data
        document.getElementById('modalDate').textContent = new Date(data.tanggal_reservasi).toLocaleDateString('id-ID', {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        });
        
        document.getElementById('modalTime').textContent = data.jam_reservasi.substring(0, 5);
        document.getElementById('modalStatus').textContent = data.status.toUpperCase();
        
        // Status color
        const statusEl = document.getElementById('modalStatus');
        statusEl.className = 'font-bold ';
        if (data.status === 'confirmed') statusEl.classList.add('text-green-600');
        else if (data.status === 'requested') statusEl.classList.add('text-blue-600');
        else if (data.status === 'cancelled') statusEl.classList.add('text-red-600');
        else statusEl.classList.add('text-gray-600');
        
        document.getElementById('modalTotal').textContent = 'Rp ' + Number(data.total_pembayaran).toLocaleString('id-ID');
        document.getElementById('modalPaymentStatus').textContent = data.status_pembayaran || 'Belum Dibayar';
        
        // Show modal
        document.getElementById('reservationModal').classList.remove('hidden');
    });
});

document.getElementById('reservationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});

document.getElementById('closeModal').addEventListener('click', function() {
    document.getElementById('reservationModal').classList.add('hidden');
});
</script>

@endsection