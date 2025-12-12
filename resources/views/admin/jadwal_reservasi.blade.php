@extends('layouts.admin')

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

    {{-- SUCCESS/ERROR MESSAGE --}}
    @if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
        <span class="block sm:inline">{{ session('success') }}</span>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    {{-- HEADER FILTER --}}
    <form method="GET" action="{{ route('admin.jadwal_reservasi.index') }}" id="filterForm">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 pt-6">

            <div class="flex flex-wrap gap-2 sm:gap-3 items-center w-full sm:w-auto">

                {{-- Search --}}
                <div class="relative flex-grow sm:flex-grow-0">
                    <input type="text" 
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search patient, treatments, etc"
                        class="border border-white rounded-full pl-10 pr-4 h-9 w-60 text-sm">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-2.5 text-gray-500"></i>
                </div>

                {{-- Dropdown Treatments --}}
                <div class="relative">
                    <select id="treatmentSelect" 
                        name="treatment"
                        class="bg-[#EED892] text-[#4A3B1C] pl-4 pr-10 h-9 rounded-full text-sm cursor-pointer"
                        onchange="document.getElementById('filterForm').submit()">
                        <option value="all">All Treatments</option>
                        @foreach($treatments as $treatment)
                        <option value="{{ $treatment->id_treatment }}" 
                            {{ request('treatment') == $treatment->id_treatment ? 'selected' : '' }}>
                            {{ $treatment->nama_treatment }}
                        </option>
                        @endforeach
                    </select>

                    <iconify-icon 
                        id="chevTreatment"
                        icon="tabler:chevron-down"
                        class="text-[#4A3B1C] text-base absolute right-3 top-1/2 -translate-y-1/2 chev-transition pointer-events-none">
                    </iconify-icon>
                </div>

                {{-- DATE --}}
                <div class="relative">
                    <iconify-icon 
                        icon="tabler:calendar"
                        class="text-[#4A3B1C] text-base absolute left-3 top-1/2 -translate-y-1/2 pointer-events-none">
                    </iconify-icon>

                    <input 
                        id="dateInput" 
                        name="date"
                        type="date"
                        value="{{ request('date') }}"
                        class="bg-[#EED892] text-[#4A3B1C] pl-10 pr-10 h-9 rounded-full text-sm cursor-pointer"
                        onchange="document.getElementById('filterForm').submit()">

                    <iconify-icon 
                        id="chevDate"
                        icon="tabler:chevron-down"
                        class="text-[#4A3B1C] text-base absolute right-3 top-1/2 -translate-y-1/2 cursor-pointer chev-transition">
                    </iconify-icon>
                </div>

                {{-- STATUS --}}
                <div class="relative">
                    <select id="statusSelect"
                        name="status"
                        class="bg-[#EED892] text-[#4A3B1C] pl-4 pr-10 h-9 rounded-full text-sm cursor-pointer"
                        onchange="document.getElementById('filterForm').submit()">
                        <option value="all">All Status</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="in-progress" {{ request('status') == 'in-progress' ? 'selected' : '' }}>In Progress</option>
                        <option value="scheduled" {{ request('status') == 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>

                    <iconify-icon 
                        id="chevStatus"
                        icon="tabler:chevron-down"
                        class="text-[#4A3B1C] text-base absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                    </iconify-icon>
                </div>

                {{-- Clear Filter --}}
                @if(request()->anyFilled(['search', 'treatment', 'date', 'status']))
                <a href="{{ route('admin.jadwal_reservasi.index') }}" 
                    class="sm:hidden flex items-center justify-center bg-gray-500 text-white px-4 py-2 rounded-full hover:bg-gray-600 text-sm shadow-sm">
                    <iconify-icon icon="mdi:filter-remove" class="text-xl mr-1"></iconify-icon>
                    Clear
                </a>
                @endif

                {{-- EXPORT MOBILE (SAMPING STATUS) --}}
                <a href="{{ route('admin.jadwal_reservasi.export', request()->query()) }}"
                    class="sm:hidden flex items-center justify-center bg-[#806B3F] text-white px-4 py-2 rounded-full hover:bg-[#A18F5E] text-sm shadow-sm">
                    <iconify-icon icon="bx:export" class="text-xl mr-1"></iconify-icon>
                    Export
                </a>
            </div>

            {{-- EXPORT DESKTOP (NORMAL POSISI KANAN) --}}
            <div class="hidden sm:flex gap-2">
                @if(request()->anyFilled(['search', 'treatment', 'date', 'status']))
                <a href="{{ route('admin.jadwal_reservasi.index') }}" 
                    class="flex items-center justify-center bg-gray-500 text-white px-4 py-2 rounded-full hover:bg-gray-600 text-sm shadow-sm">
                    <iconify-icon icon="mdi:filter-remove" class="text-xl mr-1"></iconify-icon>
                    Clear Filter
                </a>
                @endif

                <a href="{{ route('admin.jadwal_reservasi.export', request()->query()) }}"
                    class="flex items-center justify-center bg-[#806B3F] text-white px-4 py-2 rounded-full hover:bg-[#A18F5E] text-sm shadow-sm">
                    <iconify-icon icon="bx:export" class="text-xl mr-1"></iconify-icon>
                    Export
                </a>
            </div>
        </div>
    </form>

    {{-- TABLE --}}
    <div class="bg-white rounded-2xl shadow p-4 sm:p-6 overflow-x-auto">
        @if($reservasi->count() > 0)
        <table class="min-w-[700px] w-full text-sm table-bordered border-collapse">
            <thead>
                <tr class="bg-gray-100/60 text-gray-700">
                    <th class="px-4 py-3 text-left rounded-l-lg border-b border-gray-300 font-light">No</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">ID Patient</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Patient</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Treatment</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Date & Time</th>
                    <th class="px-4 py-3 text-left border-b border-gray-300 font-light">Status</th>
                    <th class="px-4 py-3 text-left rounded-r-lg border-b border-gray-300 font-light">Action</th>
                </tr>
            </thead>

            <tbody class="text-gray-700">
                @foreach($reservasi as $index => $item)
                <tr class="bg-white hover:bg-gray-50 border-b border-gray-200">
                    <td class="px-4 py-3">{{ $reservasi->firstItem() + $index }}</td>
                    <td class="px-4 py-3">P-{{ str_pad($item->id_pasien, 4, '0', STR_PAD_LEFT) }}</td>
                    <td class="px-4 py-3">
                        <div>
                            <div class="font-medium">{{ $item->pasien->nama_depan }} {{ $item->pasien->nama_belakang }}</div>
                            <div class="text-xs text-gray-500">{{ $item->pasien->no_telepon }}</div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="max-w-[200px]">
                            {{ $item->treatments_list }}
                            @if($item->detailReservasi->count() > 1)
                            <span class="text-xs text-gray-500">({{ $item->detailReservasi->count() }} items)</span>
                            @endif
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div>
                            <div>{{ $item->tanggal_reservasi->format('d M Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $item->jam_reservasi->format('H:i') }}</div>
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $item->status_badge['bg'] }} {{ $item->status_badge['text-color'] }}">
                            {{ $item->status_badge['text'] }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.jadwal_reservasi.show', $item->id_reservasi) }}" 
                                class="text-blue-600 hover:text-blue-800"
                                title="Detail">
                                <iconify-icon icon="mdi:eye" class="text-xl"></iconify-icon>
                            </a>
                            
                            @if($item->status == 'requested')
                            <button onclick="confirmReservasi({{ $item->id_reservasi }})" 
                                class="text-green-600 hover:text-green-800"
                                title="Konfirmasi">
                                <iconify-icon icon="mdi:check-circle" class="text-xl"></iconify-icon>
                            </button>
                            @endif

                            @if($item->status != 'cancelled' && $item->status != 'done')
                            <button onclick="cancelReservasi({{ $item->id_reservasi }})" 
                                class="text-red-600 hover:text-red-800"
                                title="Batalkan">
                                <iconify-icon icon="mdi:close-circle" class="text-xl"></iconify-icon>
                            </button>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Pagination --}}
        <div class="mt-4">
            {{ $reservasi->links() }}
        </div>

        @else
        <div class="text-center py-8 text-gray-500">
            <iconify-icon icon="mdi:calendar-blank" class="text-6xl mb-2"></iconify-icon>
            <p>Tidak ada data reservasi</p>
        </div>
        @endif
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", () => {

    function setupDropdown(selectId, iconId) {
        const select = document.getElementById(selectId);
        const icon = document.getElementById(iconId);

        if (!select || !icon) return;

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

    if (dateInput && chevDate) {
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
    }
});

// Confirm Reservasi
function confirmReservasi(id) {
    if (confirm('Konfirmasi reservasi ini?')) {
        updateStatus(id, 'confirmed', 'Reservasi dikonfirmasi oleh admin');
    }
}

// Cancel Reservasi
function cancelReservasi(id) {
    const alasan = prompt('Masukkan alasan pembatalan:');
    if (alasan) {
        updateStatus(id, 'cancelled', alasan);
    }
}

function updateStatus(id, status, keterangan) {
    fetch(`/admin/jadwal-reservasi/${id}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            status: status,
            keterangan: keterangan
        })
    })
    .then(r => {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    })
    .then(res => {
        alert(res.message);
        location.reload();
    })
    .catch(err => {
        alert('Gagal: ' + err.message);
        console.error(err);
    });
}
</script>

@endsection