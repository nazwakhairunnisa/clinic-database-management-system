@extends('layouts.owner.app')

@section('pageTitle', 'Jadwal Reservasi')

@section('content')

{{-- Iconify --}}
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

{{-- STYLE FIXED UNTUK MENGHILANGKAN ICON BAWAAN --}}
<style>
/* Hilangkan chevron default bawaan select (SEMUA browser) */
select {
    -webkit-appearance: none !important;
    -moz-appearance: none !important;
    appearance: none !important;
    background-image: none !important;
}

select::-ms-expand {
    display: none !important;
}

/* Hilangkan icon default date input */
input[type="date"]::-webkit-calendar-picker-indicator {
    display: none !important;
}

/* Hilangkan underline chrome */
select, select *,
input[type="date"], input[type="date"] * {
    text-decoration: none !important;
}

/* Hilangkan outline */
select:focus,
input[type="date"]:focus {
    outline: none !important;
    border: none !important;
    box-shadow: inset 0 0 0 2px #000 !important;
}

/* Dropdown putih */
select option {
    background: white !important;
    color: black !important;
}

/* Chevron tanpa animasi */
.chev-transition {
    transition: none !important;
}

.rotate-180 {
    transform: rotate(180deg);
}

/* Border di table */
.table-bordered tbody tr {
    border-bottom: 1px solid #000;
}
</style>



<div class="max-w-full font-['Roboto',sans-serif]">

    {{-- HEADER FILTER --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pt-6">

        

            <div class="flex flex-wrap gap-2 sm:gap-3 items-center w-full sm:w-auto">

    {{-- Search --}}
    <div class="relative flex-grow sm:flex-grow-0">
        <input type="text" 
            placeholder="Search patient, treatments, etc"
            class="border border-white rounded-full pl-10 pr-4 h-9 w-60 text-sm">
        <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500"></i>
    </div>

    {{-- Dropdown Treatments --}}
    <div class="relative">
        <select id="treatmentSelect"
            class="bg-[#EED892] text-[#4A3B1C] 
                   pl-4 pr-10 h-9 rounded-full text-sm cursor-pointer">
            <option>Treatments</option>
            <option>Facial</option>
            <option>Laser</option>
        </select>

        <iconify-icon 
            id="chevTreatment"
            icon="tabler:chevron-down"
            class="text-[#4A3B1C] text-base absolute right-3 top-1/2 
                   -translate-y-1/2 chev-transition">
        </iconify-icon>
    </div>

    {{-- DATE --}}
    <div class="relative">

        <iconify-icon 
            icon="tabler:calendar"
            class="text-[#4A3B1C] text-base absolute left-3 top-1/2 
                   -translate-y-1/2 pointer-events-none">
        </iconify-icon>

        <input 
            id="dateInput" 
            type="date"
            class="bg-[#EED892] text-[#4A3B1C] 
                   pl-10 pr-10 h-9 rounded-full text-sm cursor-pointer">

        <iconify-icon 
            id="chevDate"
            icon="tabler:chevron-down"
            class="text-[#4A3B1C] text-base absolute right-3 top-1/2 
                   -translate-y-1/2 cursor-pointer chev-transition">
        </iconify-icon>

    </div>

    {{-- STATUS --}}
    <div class="relative">
        <select id="statusSelect"
            class="bg-[#EED892] text-[#4A3B1C] 
                   pl-4 pr-10 h-9 rounded-full text-sm cursor-pointer">
            <option>Status</option>
            <option>Completed</option>
            <option>In Progress</option>
            <option>Scheduled</option>
            <option>Cancelled</option>
        </select>

        <iconify-icon 
            id="chevStatus"
            icon="tabler:chevron-down"
            class="text-[#4A3B1C] text-base absolute right-3 top-1/2 
                   -translate-y-1/2">
        </iconify-icon>
    </div>

    {{-- EXPORT MOBILE (SAMPING STATUS) --}}
    <button
        class="sm:hidden flex items-center justify-center bg-[#806B3F] text-white px-4 py-2 rounded-full
        hover:bg-[#A18F5E] text-sm shadow-sm">
        <iconify-icon icon="bx:export" class="text-xl mr-1"></iconify-icon>
        Export
    </button>

</div>



{{-- EXPORT DESKTOP (NORMAL POSISI KANAN) --}}
<div class="hidden sm:flex">
    <button
        class="flex items-center justify-center bg-[#806B3F] text-white px-4 py-2 rounded-full
        hover:bg-[#A18F5E] text-sm shadow-sm">
        
        <iconify-icon icon="bx:export" class="text-xl mr-1"></iconify-icon>
        Export
    </button>
</div>
</div>





    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 overflow-x-auto">
        <table class="min-w-[700px] w-full text-sm table-bordered border-collapse">
            <thead>
    <tr class="bg-gray-100/60 text-gray-700">
        <th class="px-4 py-3 text-left rounded-l-lg border-b border-gray-300 font-light">No</th>
        <th class="px-4 py-3 text-left border-b border-gray-300 font-light">ID Patient</th>
        <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Patient</th>
        <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Treatment</th>
        <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Date & Time</th>
        <th class="px-4 py-3 text-left rounded-r-lg border-b border-gray-300 font-light">Status</th>
    </tr>
</thead>

            <tbody class="text-gray-700">
                @foreach([
                    ['no'=>1,'id'=>'P-001','name'=>'Susi Susanti','treatment'=>'Facial Rejuvenation','datetime'=>'1–5 Oktober 2025','status'=>'Completed','statusColor'=>'green'],
                    ['no'=>2,'id'=>'P-002','name'=>'Aliyah Runa','treatment'=>'Laser Hair Removal','datetime'=>'2–6 Oktober 2025','status'=>'In Progress','statusColor'=>'blue'],
                    ['no'=>3,'id'=>'P-003','name'=>'Asep','treatment'=>'Botox Injection','datetime'=>'3–7 Oktober 2025','status'=>'Scheduled','statusColor'=>'yellow'],
                    ['no'=>4,'id'=>'P-004','name'=>'Rudi Hartono','treatment'=>'Chemical Peels','datetime'=>'4–8 Oktober 2025','status'=>'Cancelled','statusColor'=>'red'],
                ] as $row)
                
                <tr class="bg-white hover:bg-gray-50 border-b border-gray-200">
                    <td class="px-4 py-3">{{ $row['no'] }}</td>
                    <td class="px-4 py-3">{{ $row['id'] }}</td>
                    <td class="px-4 py-3">{{ $row['name'] }}</td>
                    <td class="px-4 py-3">{{ $row['treatment'] }}</td>
                    <td class="px-4 py-3">{{ $row['datetime'] }}</td>
                    <td class="px-4 py-3">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full
                            {{ $row['statusColor']=='green' ? 'bg-green-200 text-green-700' : '' }}
                            {{ $row['statusColor']=='blue' ? 'bg-blue-200 text-blue-700' : '' }}
                            {{ $row['statusColor']=='yellow' ? 'bg-yellow-200 text-yellow-700' : '' }}
                            {{ $row['statusColor']=='red' ? 'bg-red-200 text-red-700' : '' }}">
                            {{ $row['status'] }}
                        </span>
                    </td>
                </tr>

                @endforeach
            </tbody>
        </table>
    </div>

</div>




{{-- SCRIPT FIXED --}}
<script>
document.addEventListener("DOMContentLoaded", () => {

    function setupDropdown(selectId, iconId) {
        const select = document.getElementById(selectId);
        const icon = document.getElementById(iconId);

        select.addEventListener("click", () => {
            icon.classList.add("rotate-180");
        });

        select.addEventListener("change", () => {
            icon.classList.remove("rotate-180");
        });

        document.addEventListener("click", (e) => {
            if (!select.contains(e.target)) {
                icon.classList.remove("rotate-180");
            }
        });
    }

    setupDropdown("treatmentSelect", "chevTreatment");
    setupDropdown("statusSelect", "chevStatus");

    // DATE
    const dateInput = document.getElementById("dateInput");
    const chevDate = document.getElementById("chevDate");

    chevDate.addEventListener("click", () => {
        dateInput.showPicker();
        chevDate.classList.add("rotate-180");
    });

    dateInput.addEventListener("change", () => {
        chevDate.classList.remove("rotate-180");
    });

    document.addEventListener("click", (e) => {
        if (!dateInput.contains(e.target) && !chevDate.contains(e.target)) {
            chevDate.classList.remove("rotate-180");
        }
    });

});
</script>



@endsection
