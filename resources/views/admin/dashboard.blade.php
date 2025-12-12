@extends('layouts.admin')

@section('pageTitle', 'Dashboard Admin')

@section('content')
<div class="p-4 sm:p-6 font-['Roboto']">

    {{-- GRID BESAR 3 KOLOM --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- KIRI — Summary cards + Patient Table --}}
        <div class="lg:col-span-2 flex flex-col gap-6">

            {{-- === SUMMARY CARDS (2 CARD) === --}}
            <div class="grid grid-cols-2 gap-4">

                {{-- Reservasi Hari Ini --}}
                <div class="bg-[#EED892] p-4 rounded-xl shadow flex items-center h-28">
                    <div class="w-12 h-12 rounded-full bg-[#FBF7E7]/80 flex items-center justify-center mr-3">
                        <iconify-icon icon="gridicons:scheduled" class="text-3xl text-black"></iconify-icon>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Reservasi Hari Ini</p>
                        <h2 class="text-xl font-bold text-black">{{ $reservasiHariIni }}</h2>
                    </div>
                </div>

                {{-- Total Pasien --}}
                <div class="bg-[#F5EAD5] p-4 rounded-xl shadow flex items-center h-28">
                    <div class="w-12 h-12 rounded-full bg-[#FBF7E7]/80 flex items-center justify-center mr-3">
                        <iconify-icon icon="mdi:people-outline" class="text-2xl text-black"></iconify-icon>
                    </div>

                    <div>
                        <p class="text-sm text-gray-600">Total Pasien</p>
                        <h2 class="text-xl font-bold text-black">{{ $totalPasien }}</h2>
                    </div>
                </div>

            </div>

            {{-- PATIENT STATUS TABLE --}}
            <div class="bg-white p-6 rounded-xl shadow overflow-x-auto"
                x-data='{
                    patients: @json($patientStatus),
                    treatmentFilter: "",
                    dateFilter: "",
                    statusFilter: "",
                    treatmentOpen: false,
                    dateOpen: false,
                    statusOpen: false,
                    
                    // ✅ FUNGSI HELPER UNTUK CONVERT STATUS
                    getStatusDisplay(rawStatus) {
                        const statusMap = {
                            "requested": "Requested",
                            "confirmed": "Confirmed", 
                            "done": "Treatment Done",
                            "waiting-payment": "Waiting Payment",
                            "completed": "Completed",
                            "cancelled": "Cancelled"
                        };
                        return statusMap[rawStatus] || rawStatus;
                    },
                    
                    // ✅ FUNGSI UNTUK WARNA STATUS
                    getStatusColor(rawStatus) {
                        const colorMap = {
                            "requested": "bg-gray-100 text-gray-700",
                            "confirmed": "bg-blue-100 text-blue-700",
                            "done": "bg-purple-100 text-purple-700",
                            "waiting-payment": "bg-yellow-100 text-yellow-700",
                            "completed": "bg-green-100 text-green-700",
                            "cancelled": "bg-red-100 text-red-700"
                        };
                        return colorMap[rawStatus] || "bg-gray-100 text-gray-700";
                    },
                    
                    get filteredPatients() {
                        return this.patients.filter(p => {
                            const statusDisplay = this.getStatusDisplay(p.status_raw);
                            return (
                                (this.treatmentFilter === "" || p.treatment_name === this.treatmentFilter) &&
                                (this.statusFilter === "" || statusDisplay === this.statusFilter) &&
                                (this.dateFilter === "" || p.datetime_full.startsWith(this.dateFilter))
                            );
                        });
                    }
                }'>

                {{-- HEADER --}}
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-bold">Status Reservasi</h2>

                    <a href="{{ route('admin.jadwal_reservasi.index') }}" 
                       class="bg-[#EBDDAF] text-[#806B3F] text-sm font-light px-4 py-1 rounded-full hover:bg-[#e2d193] transition">
                        View All
                    </a>
                </div>

                {{-- TABLE --}}
                <table class="w-full border-collapse text-sm min-w-[600px]">
                    <thead>
                        <tr class="bg-gray-100/50">
                            <th class="py-2 px-3 text-left font-light rounded-l-lg">No</th>
                            <th class="py-2 px-3 text-left font-light">Patient</th>

                            {{-- Treatment --}}
                            <th class="relative py-2 px-3 text-left font-light">
                                <div class="flex items-center gap-1 cursor-pointer" @click="treatmentOpen=!treatmentOpen">
                                    Treatment
                                    <div class="flex flex-col leading-[0.5] ml-1 opacity-60">
                                        <iconify-icon icon='tabler:caret-up' class="text-[10px]"></iconify-icon>
                                        <iconify-icon icon='tabler:caret-down' class="text-[10px] -mt-1"></iconify-icon>
                                    </div>
                                </div>

                                <div x-show="treatmentOpen"
                                    @click.away="treatmentOpen=false"
                                    class="absolute left-0 mt-1 bg-white shadow-lg border rounded-lg z-50 w-44 text-sm">

                                    <button class="block w-full px-3 py-2 hover:bg-gray-100 text-left"
                                        @click="treatmentFilter=''; treatmentOpen=false">All</button>

                                    <template x-for="item in [...new Set(patients.map(p=>p.treatment_name))]">
                                        <button class="block w-full px-3 py-2 hover:bg-gray-100 text-left"
                                            @click="treatmentFilter=item; treatmentOpen=false"
                                            x-text="item"></button>
                                    </template>
                                </div>
                            </th>

                            {{-- Date --}}
                            <th class="relative py-2 px-3 text-left font-light">
                                <div class="flex items-center gap-1 cursor-pointer" @click="dateOpen=!dateOpen">
                                    Date & Time
                                    <div class="flex flex-col leading-[0.5] ml-1 opacity-60">
                                        <iconify-icon icon='tabler:caret-up' class="text-[10px]"></iconify-icon>
                                        <iconify-icon icon='tabler:caret-down' class="text-[10px] -mt-1"></iconify-icon>
                                    </div>
                                </div>

                                <div x-show="dateOpen"
                                    @click.away="dateOpen=false"
                                    class="absolute left-0 mt-1 bg-white shadow-lg border rounded-lg z-50 w-36 text-sm">

                                    <button class="block w-full px-3 py-2 hover:bg-gray-100 text-left"
                                        @click="dateFilter=''; dateOpen=false">All</button>

                                    <template x-for="dt in [...new Set(patients.map(p=>p.reservation_date))]">
                                        <button class="block w-full px-3 py-2 hover:bg-gray-100 text-left"
                                            @click="dateFilter=dt; dateOpen=false"
                                            x-text="dt"></button>
                                    </template>
                                </div>
                            </th>

                            {{-- Status --}}
                            <th class="relative py-2 px-3 text-left font-light rounded-r-lg">
                                <div class="flex items-center gap-1 cursor-pointer" @click="statusOpen=!statusOpen">
                                    Status
                                    <div class="flex flex-col leading-[0.5] ml-1 opacity-60">
                                        <iconify-icon icon='tabler:caret-up' class="text-[10px]"></iconify-icon>
                                        <iconify-icon icon='tabler:caret-down' class="text-[10px] -mt-1"></iconify-icon>
                                    </div>
                                </div>

                                <div x-show="statusOpen"
                                    @click.away="statusOpen=false"
                                    class="absolute left-0 mt-1 bg-white shadow-lg border rounded-lg z-50 w-40 text-sm">

                                    <button class="block w-full px-3 py-2 hover:bg-gray-100 text-left"
                                        @click="statusFilter=''; statusOpen=false">All</button>
                                    
                                    <button class="block w-full px-3 py-2 hover:bg-gray-100 text-left"
                                        @click="statusFilter='Scheduled'; statusOpen=false">Scheduled</button>
                                    
                                    <button class="block w-full px-3 py-2 hover:bg-gray-100 text-left"
                                        @click="statusFilter='Confirmed'; statusOpen=false">Confirmed</button>
                                    
                                    <button class="block w-full px-3 py-2 hover:bg-gray-100 text-left"
                                        @click="statusFilter='Treatment Done'; statusOpen=false">Treatment Done</button>
                                    
                                    <button class="block w-full px-3 py-2 hover:bg-gray-100 text-left"
                                        @click="statusFilter='Waiting Payment'; statusOpen=false">Waiting Payment</button>
                                    
                                    <button class="block w-full px-3 py-2 hover:bg-gray-100 text-left"
                                        @click="statusFilter='Completed'; statusOpen=false">Completed</button>
                                    
                                    <button class="block w-full px-3 py-2 hover:bg-gray-100 text-left"
                                        @click="statusFilter='Cancelled'; statusOpen=false">Cancelled</button>
                                </div>
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y">
                        <template x-for="(patient,index) in filteredPatients" :key="patient.id_reservasi">
                            <tr class="bg-gray-50/30">

                                <td class="py-2 px-3" x-text="index+1"></td>
                                <td class="py-2 px-3" x-text="patient.patient_name"></td>
                                <td class="py-2 px-3" x-text="patient.treatment_name"></td>
                                <td class="py-2 px-3" x-text="patient.datetime_full"></td>

                                <td class="py-2 px-3">
                                    {{-- ✅ STATUS BADGE (READ-ONLY, NO DROPDOWN) --}}
                                    <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full"
                                          :class="getStatusColor(patient.status_raw)"
                                          x-text="getStatusDisplay(patient.status_raw)">
                                    </span>
                                </td>

                            </tr>
                        </template>
                    </tbody>
                </table>

            </div>

        </div>

        {{-- KANAN — Calendar + Jadwal Treatment --}}
        <div class="lg:col-span-1 flex flex-col gap-6"
             x-data="calendarComponent()"
             x-init="initCalendar()">

            {{-- CALENDAR --}}
            <div class="bg-white p-4 md:p-6 rounded-xl shadow">

                {{-- Header --}}
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">

                        {{-- Month --}}
                        <select x-model.number="currentMonth"
                                @change="updateCalendar()"
                                class="appearance-none bg-transparent border-none text-base font-bold pr-10 pl-1 cursor-pointer outline-none">
                            <template x-for="(month, index) in monthNames" :key="index">
                                <option :value="index" x-text="month"></option>
                            </template>
                        </select>

                        {{-- Year --}}
                        <span class="font-bold text-base" x-text="currentYear"></span>

                    </div>
                </div>

                {{-- Hari --}}
                <div class="mt-3 text-xs text-gray-400">

                    <div class="grid grid-cols-7 text-center mb-1">
                        <template x-for="day in ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']">
                            <span class="font-semibold" x-text="day"></span>
                        </template>
                    </div>

                    <div class="grid grid-cols-7 text-center gap-y-1">

                        <template x-for="blank in firstDayOfMonth">
                            <div></div>
                        </template>

                        <template x-for="n in daysInMonth" :key="n">
                            <div
                                @click="selectDate(n)"
                                class="flex items-center justify-center w-8 h-8 mx-auto rounded-full cursor-pointer transition"
                                :class="selectedDate === n ? 'bg-[#0073D9] text-white font-bold' : 'hover:bg-gray-100 text-gray-800'">
                                <span x-text="n"></span>
                            </div>
                        </template>

                    </div>
                </div>

            </div>

            {{-- JADWAL TREATMENT --}}
            <div class="bg-white p-4 md:p-6 rounded-xl shadow">

                <div class="flex justify-between items-center mb-3">
                    <div>
                        <h3 class="text-sm font-bold">Jadwal Treatment</h3>
                        <p class="text-xs text-gray-500"
                           x-text="selectedDate + ' ' + monthNames[currentMonth] + ' ' + currentYear"></p>
                    </div>

                    <button class="text-[#806B3F] text-lg hover:text-[#A18F5E]">
                        <i class="fa-solid fa-plus"></i>
                    </button>
                </div>

                {{-- Loading State --}}
                <div x-show="loadingJadwal" class="text-center py-4">
                    <div class="inline-block animate-spin rounded-full h-6 w-6 border-b-2 border-[#806B3F]"></div>
                    <p class="text-xs text-gray-500 mt-2">Loading...</p>
                </div>

                {{-- List Jadwal --}}
                <ul class="divide-y divide-black text-xs md:text-sm" x-show="!loadingJadwal">
                    <template x-if="jadwalList.length === 0">
                        <li class="py-4 text-center text-gray-400 text-sm">
                            Tidak ada jadwal treatment
                        </li>
                    </template>

                    <template x-for="jadwal in jadwalList" :key="jadwal.id_reservasi">
                        <li class="flex items-center justify-between py-2">
                            <div class="flex items-center gap-2">
                                <input type="checkbox" 
                                       :checked="jadwal.is_completed == 1"
                                       class="accent-[#8CF69C] w-4 h-4">
                                <span class="text-gray-700" x-text="jadwal.nama_pasien"></span>
                            </div>
                            <span class="text-gray-500" 
                                  x-text="jadwal.jam_formatted + ' - ' + jadwal.jam_selesai_formatted"></span>
                        </li>
                    </template>
                </ul>

            </div>

        </div>

    </div>
</div>

{{-- Calendar Script --}}
<script>
function calendarComponent() {
    return {
        currentMonth: new Date().getMonth(),
        currentYear: new Date().getFullYear(),
        selectedDate: new Date().getDate(),
        daysInMonth: [],
        firstDayOfMonth: 0,
        jadwalList: @json($jadwalTreatment),
        loadingJadwal: false,
        monthNames: [
            'January','February','March','April','May','June',
            'July','August','September','October','November','December'
        ],
        initCalendar() {
            this.updateCalendar();
            this.loadJadwal();
        },
        updateCalendar() {
            const days = new Date(this.currentYear, this.currentMonth + 1, 0).getDate();
            this.daysInMonth = Array.from({ length: days }, (_, i) => i + 1);
            this.firstDayOfMonth = new Date(this.currentYear, this.currentMonth, 1).getDay();
        },
        selectDate(day) {
            this.selectedDate = day;
            this.loadJadwal();
        },
        async loadJadwal() {
            const month = String(this.currentMonth + 1).padStart(2, '0');
            const date = String(this.selectedDate).padStart(2, '0');
            const tanggal = `${this.currentYear}-${month}-${date}`;

            this.loadingJadwal = true;

            try {
                const response = await fetch(`{{ route('admin.dashboard.jadwal') }}?tanggal=${tanggal}`);
                const result = await response.json();

                if (result.success) {
                    this.jadwalList = result.data;
                } else {
                    console.error('Error:', result.message);
                    this.jadwalList = [];
                }
            } catch (error) {
                console.error('Fetch error:', error);
                this.jadwalList = [];
            } finally {
                this.loadingJadwal = false;
            }
        }
    }
}
</script>

@endsection