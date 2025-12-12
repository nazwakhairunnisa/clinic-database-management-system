@extends('layouts.owner.app')

@section('pageTitle', 'Dashboard')

@section('content')
<div class="p-4 sm:p-6 space-y-8 font-['Roboto']">

    {{-- === Bagian Summary + Calendar & Jadwal Treatment (Sejajar) === --}}
    <div class="flex flex-col lg:flex-row gap-6">

        {{-- === KIRI: Summary + Patient Status === --}}
        <div class="flex-1 flex flex-col space-y-6 min-w-0">

            {{-- === Summary Cards (2x2) === --}}
            <div class="grid grid-cols-2 gap-4 w-full">
                
                {{-- Pendapatan --}}
                <div class="bg-[#EED892] p-4 rounded-xl shadow flex items-center w-full h-28">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-[#FBF7E7]/80 mr-3">
                    <iconify-icon icon="dashicons:money-alt" class="text-3xl text-black"></iconify-icon>
                    </div>
                    <div class="flex flex-col justify-center text-left">
                        <p class="text-sm text-gray-600">Pendapatan</p>
                        <h2 class="text-xl font-bold text-black">
                            <span class="hidden sm:inline">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</span>
                            <span class="inline sm:hidden">Rp {{ number_format($totalPendapatan / 1000000, 1) }} jt</span>
                        </h2>
                    </div>
                </div>

                {{-- Total Pasien --}}
                <div class="bg-[#F5EAD5] p-4 rounded-xl shadow flex items-center w-full h-28">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-[#FBF7E7]/80 mr-3">
                        <iconify-icon icon="mdi:people-outline" class="text-2xl text-black"></iconify-icon>
                    </div>
                    <div class="flex flex-col justify-center text-left">
                        <p class="text-sm text-gray-600">Total Pasien</p>
                        <h2 class="text-xl font-bold text-black">{{ $totalPasien }}</h2>
                    </div>
                </div>

                {{-- Reservasi --}}
                <div class="bg-[#F5EAD5] p-4 rounded-xl shadow flex items-center w-full h-28">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-[#FBF7E7]/80 mr-3">
                        <iconify-icon icon="mdi:calendar-check" class="text-2xl text-black"></iconify-icon>
                    </div>
                    <div class="flex flex-col justify-center text-left">
                        <p class="text-sm text-gray-600">Reservasi</p>
                        <h2 class="text-xl font-bold text-black">{{ $totalReservasi }}</h2>
                        <p class="text-xs text-gray-500">Bulan ini</p>
                    </div>
                </div>

                {{-- Total Treatment --}}
                <div class="bg-[#EED892] p-4 rounded-xl shadow flex items-center w-full h-28">
                    <div class="flex items-center justify-center w-12 h-12 rounded-full bg-[#FBF7E7]/80 mr-3">
                    <iconify-icon icon="ri:health-book-line" class="text-2xl text-black"></iconify-icon>
                    </div>
                    <div class="flex flex-col justify-center text-left">
                        <p class="text-sm text-gray-600">Total Treatment</p>
                        <h2 class="text-xl font-bold text-black">{{ $totalTreatment }}</h2>
                    </div>
                </div>
                
            </div>

            {{-- === PATIENT STATUS === --}}
            <div class="bg-white p-6 rounded-xl shadow overflow-x-auto font-['Roboto']"
                x-data='{
                    patients: @json($patientStatus),
                    
                    treatmentFilter: "",
                    dateFilter: "",
                    statusFilter: "",
                    
                    treatmentOpen: false,
                    dateOpen: false,
                    statusOpen: false,

                    // ✅ FUNGSI HELPER UNTUK CONVERT STATUS (SAMA DENGAN ADMIN)
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
                    
                    // ✅ FUNGSI UNTUK WARNA STATUS (SAMA DENGAN ADMIN)
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
                    <h2 class="text-lg font-bold">Patient Status</h2>

                    <a href="{{ route('owner.jadwal_reservasi.index') }}" 
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

                            {{-- TREATMENT FILTER --}}
                            <th class="relative py-2 px-3 text-left font-light">
                                <div class="flex items-center gap-1 cursor-pointer" @click="treatmentOpen=!treatmentOpen">
                                    Treatment
                                    <div class="flex flex-col leading-[0.5] ml-1 opacity-60">
                                        <iconify-icon icon='tabler:caret-up' class="text-[10px]"></iconify-icon>
                                        <iconify-icon icon='tabler:caret-down' class="text-[10px] -mt-1"></iconify-icon>
                                    </div>
                                </div>

                                <div x-show="treatmentOpen" @click.away="treatmentOpen=false"
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

                            {{-- DATE FILTER --}}
                            <th class="relative py-2 px-3 text-left font-light">
                                <div class="flex items-center gap-1 cursor-pointer" @click="dateOpen=!dateOpen">
                                    Date & Time
                                    <div class="flex flex-col leading-[0.5] ml-1 opacity-60">
                                        <iconify-icon icon='tabler:caret-up' class="text-[10px]"></iconify-icon>
                                        <iconify-icon icon='tabler:caret-down' class="text-[10px] -mt-1"></iconify-icon>
                                    </div>
                                </div>

                                <div x-show="dateOpen" @click.away="dateOpen=false"
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

                            {{-- STATUS FILTER --}}
                            <th class="relative py-2 px-3 text-left font-light rounded-r-lg">
                                <div class="flex items-center gap-1 cursor-pointer" @click="statusOpen=!statusOpen">
                                    Status
                                    <div class="flex flex-col leading-[0.5] ml-1 opacity-60">
                                        <iconify-icon icon='tabler:caret-up' class="text-[10px]"></iconify-icon>
                                        <iconify-icon icon='tabler:caret-down' class="text-[10px] -mt-1"></iconify-icon>
                                    </div>
                                </div>

                                <div x-show="statusOpen" @click.away="statusOpen=false"
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

                    {{-- BODY --}}
                    <tbody class="divide-y">
                        <template x-for="(patient,index) in filteredPatients" :key="patient.id_reservasi">
                            <tr class="bg-gray-50/30">

                                <td class="py-2 px-3" x-text="index+1"></td>
                                <td class="py-2 px-3" x-text="patient.patient_name"></td>
                                <td class="py-2 px-3" x-text="patient.treatment_name"></td>
                                <td class="py-2 px-3" x-text="patient.datetime_full"></td>

                                {{-- ✅ STATUS BADGE (READ-ONLY) --}}
                                <td class="py-2 px-3">
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

       {{-- === KANAN: Calendar + Jadwal Treatment === --}}
        <div class="w-full lg:w-[34%] flex flex-col gap-6"
            x-data="calendarComponent()"
            x-init="initCalendar()">

            {{-- Calendar --}}
            <div class="bg-white p-4 md:p-6 rounded-xl shadow">

                {{-- Header Dropdown Bulan & Tahun --}}
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-3">

                        {{-- Dropdown Bulan --}}
                        <div class="relative w-auto">
                            <select x-model.number="currentMonth"
                                @change="updateCalendar()"
                                class="appearance-none bg-transparent border-none text-base font-bold pr-10 pl-1 cursor-pointer outline-none focus:ring-0">

                                <template x-for="(month, index) in monthNames" :key="index">
                                    <option :value="index" x-text="month"></option>
                                </template>

                            </select>
                        </div>

                        {{-- Dropdown Tahun --}}
                        <div class="relative w-32"
                            x-data="yearDropdown()"
                            x-init="init()"
                            @click.outside="close()">

                            {{-- Button Tahun --}}
                            <button @click="toggle()"
                                class="appearance-none bg-transparent border-none text-base font-bold pr-8 pl-1 w-full flex justify-between items-center cursor-pointer">
                                
                                <span x-text="currentYear"></span>
                                <i class="fa-solid fa-chevron-down text-[10px] text-gray-500"></i>
                            </button>

                            {{-- Dropdown Panel --}}
                            <div x-show="open"
                                class="absolute left-0 mt-1 bg-white shadow-lg border rounded-lg z-50 w-full">

                                {{-- Search Box --}}
                                <div class="p-2 border-b">
                                    <input placeholder="Search year..."
                                        x-model="search"
                                        @input="doSearch()"
                                        class="w-full border rounded px-2 py-1 text-sm focus:outline-none">
                                </div>

                                {{-- Scrollable List --}}
                                <div class="h-48 overflow-y-auto" @scroll="onScroll($event)">
                                    <template x-for="year in filtered" :key="year">
                                        <div @click="selectYear(year)"
                                            class="px-3 py-2 hover:bg-gray-100 cursor-pointer"
                                            :class="year === currentYear ? 'bg-gray-200 font-semibold' : ''"
                                            x-text="year"></div>
                                    </template>
                                </div>

                            </div>
                        </div>

                    </div>
                </div>

                {{-- Hari --}}
                <div class="mt-3 text-xs text-gray-400">
                    
                    <div class="grid grid-cols-7 text-center mb-1">
                        <template x-for="day in ['Sun','Mon','Tue','Wed','Thu','Fri','Sat']">
                            <span x-text="day" class="font-semibold"></span>
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
                                :class="selectedDate === n 
                                    ? 'bg-[#0073D9] text-white font-bold' 
                                    : 'hover:bg-gray-100 text-gray-800'">
                                <span x-text="n"></span>
                            </div>
                        </template>

                    </div>
                </div>

            </div>

            {{-- === Jadwal Treatment === --}}
            <div class="bg-white p-4 md:p-6 rounded-xl shadow">

                <div class="flex justify-between items-center mb-3">
                    <div>
                        <h3 class="text-sm font-bold text-[#000000]">Jadwal Treatment</h3>
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

            {{-- === MOST POPULAR TREATMENTS === --}}
            <div class="bg-white p-5 rounded-xl shadow w-full max-w-[600px]">

                <div class="flex justify-between items-center mb-4">
                    <div>
                        <h2 class="text-lg font-semibold text-black">
                            Most Popular Treatments
                        </h2>
                        <p class="text-xs text-gray-500 mt-1">{{ $currentMonthName }}</p>
                    </div>
                </div>

                @if($popularTreatments->count() > 0)
                <div class="space-y-3">
                    @foreach($popularTreatments as $index => $treatment)
                    <div>
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center space-x-3">
                                <span class="bg-[#EED892]/50 text-[#806B3F] text-sm font-semibold px-3 py-1 rounded-md">
                                    #{{ $index + 1 }}
                                </span>
                                <span class="text-gray-900 font-medium">{{ $treatment->nama_treatment }}</span>
                            </div>
                            <span class="text-sm text-[#808080] pr-2">
                                {{ $treatment->total_reservasi }} {{ Str::plural('reservation', $treatment->total_reservasi) }}
                            </span>
                        </div>
                        
                        @if(!$loop->last)
                        <hr class="border-gray-200">
                        @endif
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-8">
                    <iconify-icon icon="mdi:calendar-remove" class="text-5xl text-gray-300 mb-2"></iconify-icon>
                    <p class="text-gray-400 text-sm">Tidak ada data treatment bulan ini</p>
                </div>
                @endif

            </div>

        </div>

        {{-- ================================ --}}
        {{-- SCRIPT KOMPONEN CALENDAR --}}
        {{-- ================================ --}}
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
                        const response = await fetch(`{{ route('owner.dashboard.jadwal') }}?tanggal=${tanggal}`);
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

        {{-- ========================================= --}}
        {{-- SCRIPT DROPDOWN TAHUN (SEARCH + SCROLL) --}}
        {{-- ========================================= --}}
        <script>
        function yearDropdown() {
            return {
                open: false,
                search: "",

                totalYears: [],
                shownYears: [],
                filtered: [],

                currentYear: new Date().getFullYear(),

                start: new Date().getFullYear() - 50,
                end: new Date().getFullYear() + 50,

                init() {
                    this.generateTotalYears();
                    this.loadShownYears();
                    this.filtered = this.shownYears;
                },

                generateTotalYears() {
                    this.totalYears = [];
                    for (let y = 1900; y <= 3000; y++) {
                        this.totalYears.push(y);
                    }
                },

                loadShownYears() {
                    this.shownYears = this.totalYears.filter(y => y >= this.start && y <= this.end);
                },

                doSearch() {
                    const keyword = this.search.trim();

                    this.filtered = keyword === ""
                        ? this.shownYears
                        : this.totalYears.filter(y => y.toString().includes(keyword));
                },

                onScroll(e) {
                    const box = e.target;

                    if (box.scrollTop + box.clientHeight >= box.scrollHeight - 10) {
                        this.end += 30;
                        this.loadShownYears();
                        this.doSearch();
                    }

                    if (box.scrollTop <= 10) {
                        this.start -= 30;
                        this.loadShownYears();
                        this.doSearch();
                    }
                },

                toggle() {
                    this.open = !this.open;
                },

                close() {
                    this.open = false;
                },

                selectYear(year) {
                    this.currentYear = year;
                    this.open = false;

                    const calendar = document.querySelector('[x-data="calendarComponent()"]')?.__x?.$data;
                    if (calendar) {
                        calendar.currentYear = year;
                        calendar.updateCalendar();
                        calendar.loadJadwal();
                    }
                }
            };
        }
        </script>


    </div>

    
</div>

@endsection