@extends('layouts.owner.app')  

@section('pageTitle', 'Daftar Promo')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

{{-- ============================= --}}
{{-- STYLING AGAR SAMA PERSIS JADWAL --}}
{{-- ============================= --}}
<style>

    /* Hilangkan ikon default dropdown */
    select {
        -webkit-appearance: none;
        appearance: none;
    }

    /* Hilangkan icon default date */
    input[type="date"]::-webkit-calendar-picker-indicator {
        opacity: 0 !important;
    }

    /* Hilangkan underline & border */
    select, select *,
    input[type="date"], input[type="date"] * {
        text-decoration: none !important;
        outline: none !important;
        border: none !important;
        box-shadow: none !important;
    }

    /* Focus outline hitam untuk date */
    input[type="date"]:focus {
        box-shadow: inset 0 0 0 2px #000 !important;
    }

    /* Chevron rotate */
    .rotate-180 {
        transform: rotate(180deg);
    }
    .chev-transition {
        transition: transform 0.25s ease;
    }

    /* Border table */
    .table-bordered tbody tr {
        border-bottom: 1px solid #000;
    }

</style>


<div class="max-w-full font-['Roboto',sans-serif]">

  {{-- FULL HEADER FILTER RESPONSIVE --}}
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pt-6">

    {{-- LEFT GROUP — Search + Date --}}
    <div class="flex flex-wrap gap-2 sm:gap-3 items-center w-full sm:w-auto">

        {{-- SEARCH --}}
        <div class="relative w-60"> {{-- ⬅ MOBILE TIDAK FULL WIDTH --}}
            <input type="text"
                placeholder="Search promo"
                class="border border-white rounded-full pl-10 pr-4 py-2 w-full 
                       text-sm focus:outline-none focus:ring-2 focus:ring-[#EED892]">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500"></i>
        </div>

        {{-- DATE PICKER --}}
        <div class="relative">

            <iconify-icon 
                icon="tabler:calendar"
                class="text-[#4A3B1C] text-base absolute left-3 top-1/2 
                       -translate-y-1/2 pointer-events-none">
            </iconify-icon>

            <input type="date"
                id="promoDate"
                class="bg-[#EED892] text-[#4A3B1C] pl-10 pr-10 h-9
                       rounded-full text-sm cursor-pointer appearance-none">

            <iconify-icon 
                id="chevPromoDate"
                icon="tabler:chevron-down"
                class="text-[#4A3B1C] text-base absolute right-3 top-1/2 
                       -translate-y-1/2 cursor-pointer chev-transition">
            </iconify-icon>

        </div>

        {{-- EXPORT (MOBILE ONLY, SAMPING DATE PICKER) --}}
        <button
            class="flex sm:hidden items-center justify-center bg-[#806B3F] text-white px-3 py-2 rounded-full
            hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm">
            <iconify-icon icon="bx:export" class="text-xl"></iconify-icon>
        </button>

        {{-- ADD PROMO (MOBILE ONLY, SAMPING DATE PICKER) --}}
        <a href="{{ route('owner.promo.add') }}"
            class="flex sm:hidden items-center justify-center bg-[#806B3F] text-white px-3 py-2 rounded-full
            hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm">
            <iconify-icon icon="material-symbols:add-rounded" class="text-xl"></iconify-icon>
        </a>

    </div>



    {{-- RIGHT GROUP - DESKTOP ONLY --}}
    <div class="hidden sm:flex items-center gap-3">

        {{-- Export --}}
        <button
            class="flex items-center justify-center bg-[#806B3F] text-white px-4 py-2 rounded-full
            hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm">
            <iconify-icon icon="bx:export" class="text-xl mr-2"></iconify-icon>
            Export
        </button>

        {{-- Add Promo --}}
        <a href="{{ route('owner.promo.add') }}"
            class="flex items-center justify-center bg-[#806B3F] text-white px-4 py-2 rounded-full
            hover:bg-[#A18F5E] transition-all duration-300 text-sm shadow-sm">
            <iconify-icon icon="material-symbols:add-rounded" class="text-xl mr-2"></iconify-icon>
            Add Promo
        </a>

    </div>

</div>


    {{-- ============================= --}}
    {{-- 🔥 TABLE PROMO (Tetap Rapi) --}}
    {{-- ============================= --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 overflow-x-auto">
        
        <table class="min-w-[900px] w-full text-sm text-left border-collapse table-bordered">
            
            <thead>
    <tr class="bg-gray-100/60 text-gray-700">
        <th class="px-4 py-3 rounded-l-lg border-b border-gray-300 font-light">No</th>
        <th class="px-4 py-3 border-b border-gray-300 font-light">ID Promo</th>
        <th class="px-4 py-3 border-b border-gray-300 font-light">Nama Promo</th>
        <th class="px-4 py-3 border-b border-gray-300 font-light">Harga Promo</th>
        <th class="px-4 py-3 border-b border-gray-300 font-light">Periode Mulai</th>
        <th class="px-4 py-3 border-b border-gray-300 font-light">Periode Selesai</th>
        <th class="px-4 py-3 border-b border-gray-300 font-light">Gambar Promo</th>
        <th class="px-4 py-3 rounded-r-lg border-b border-gray-300 font-light">Action</th>
    </tr>
</thead>


            <tbody class="text-gray-700">

                @foreach([
                    ['no'=>1,'id'=>'PR-001','name'=>'Facial Rejuvenation','price'=>'Rp 350.000','mulai'=>'1 Nov 2025','selesai'=>'30 Nov 2025','img'=>'image1.jpg'],
                    ['no'=>2,'id'=>'PR-002','name'=>'Body Spa Glow','price'=>'Rp 299.000','mulai'=>'1 Nov 2025','selesai'=>'20 Nov 2025','img'=>'image2.jpg'],
                ] as $row)

                <tr class="bg-white hover:bg-gray-50 transition border-b border-gray-200">
                    <td class="px-4 py-3">{{ $row['no'] }}</td>
                    <td class="px-4 py-3">{{ $row['id'] }}</td>
                    <td class="px-4 py-3">{{ $row['name'] }}</td>
                    <td class="px-4 py-3">{{ $row['price'] }}</td>
                    <td class="px-4 py-3">{{ $row['mulai'] }}</td>
                    <td class="px-4 py-3">{{ $row['selesai'] }}</td>
                    <td class="px-4 py-3">{{ $row['img'] }}</td>

                    {{-- ACTION --}}
                    <td class="px-4 py-3 flex space-x-3">
                        <a href="{{ route('owner.promo.edit') }}"
                            class="text-gray-700 hover:text-black transition-all">
                            <iconify-icon icon="mingcute:edit-line" class="text-lg"></iconify-icon>
                        </a>

                    {{-- DELETE --}}
                        <button 
                            class="text-gray-700 hover:text-red-600 transition-all duration-200"
                            onclick="return confirm('Apakah kamu yakin ingin menghapus data ini?')">
                            <iconify-icon icon="material-symbols:delete-outline" class="text-lg"></iconify-icon>
                        </button>
                    </td>
                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>


{{-- ============================= --}}
{{-- SCRIPT – identical to jadwal.js --}}
{{-- ============================= --}}
<script>
document.addEventListener("DOMContentLoaded", () => {

    const promoDate = document.getElementById("promoDate");
    const chevPromoDate = document.getElementById("chevPromoDate");

    // Chevron click → open picker
    chevPromoDate.addEventListener("click", () => {
        promoDate.showPicker();
        chevPromoDate.classList.add("rotate-180");
    });

    // Input click → rotate
    promoDate.addEventListener("click", () => {
        promoDate.showPicker();
        chevPromoDate.classList.add("rotate-180");
    });

    // Blur → rotate back
    promoDate.addEventListener("blur", () => {
        chevPromoDate.classList.remove("rotate-180");
    });

});
</script>

@endsection
