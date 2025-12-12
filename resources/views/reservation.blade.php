@extends('layouts.app_public')

@section('title', 'Reservation - Clay Skinthetic Clinic')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="min-h-screen w-full flex justify-center px-4 py-12 bg-gray-50 font-['Roboto',sans-serif]">

    <!-- CARD -->
    <div class="w-full max-w-6xl bg-white border border-black rounded-xl 
                shadow-[6px_6px_6px_rgba(0,0,0,0.28)] px-8 lg:px-12 py-10 
                flex flex-col my-8 h-auto max-h-[90vh]">

        <!-- FORM -->
        <form id="reservationForm" method="POST" action="{{ route('user.reservasi.store') }}" 
              class="w-full flex flex-col h-full">
            @csrf

            {{-- Alert Error --}}
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <p>{{ session('error') }}</p>
                    @if(session('profile_link'))
                        <div class="mt-3">
                            <a href="{{ session('profile_link') }}" 
                               class="inline-block bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                                → Lengkapi Profil Sekarang
                            </a>
                        </div>
                    @endif
                </div>
            @endif

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            <!-- SCROLLABLE CONTENT -->
            <div class="flex-1 overflow-y-auto pr-2 -mr-2">

                <!-- GRID -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-20 gap-y-10">

                    <!-- DATE -->
                    <div>
                        <label class="block text-base font-medium text-gray-700 mb-2">
                            Date <span class="text-red-500">*</span>
                        </label>

                        <input type="date" 
                               name="tanggal_reservasi"
                               id="dateInput"
                               min="{{ date('Y-m-d') }}"
                               value="{{ old('tanggal_reservasi') }}"
                               onclick="this.showPicker()"
                               required
                               class="w-full border border-gray-300 rounded-md px-4 py-3 
                                      focus:outline-none focus:ring-1 focus:ring-[#806B3F] 
                                      focus:border-[#806B3F] bg-white">
                        @error('tanggal_reservasi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                        
                        <p id="jadwalInfo" class="text-sm text-gray-600 mt-2 hidden"></p>
                    </div>

                    <!-- TREATMENT -->
                    <div>
                        <label class="block text-base font-medium text-gray-700 mb-2">
                            Choose Treatment <span class="text-red-500">*</span>
                        </label>

                        <!-- SELECT BOX -->
                        <div id="treatmentToggle" tabindex="0"
                             class="w-full border border-gray-300 rounded-md px-4 py-3 bg-white cursor-pointer 
                                    flex justify-between items-center transition-all duration-200 
                                    focus:outline-none focus:ring-1 focus:ring-[#806B3F] focus:border-[#806B3F]">

                            <div id="selectedTags" class="flex flex-wrap gap-2">
                                <span class="text-gray-400">Pilih treatment...</span>
                            </div>

                            <svg id="toggleIcon" class="w-5 h-5 text-[#806B3F] transition-transform duration-300" 
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>

                        <!-- TREATMENT OPTIONS -->
                        <div id="treatmentBox"
                             class="border border-black rounded-md bg-white max-h-64
                                    overflow-y-auto p-4 space-y-4 mt-2 hidden shrink-0">

                            @forelse($treatments as $treatment)
                                <label class="flex items-center gap-4 border border-gray-300 rounded-lg 
                                              p-4 cursor-pointer hover:bg-gray-50">

                                    @if($treatment->foto_treatment)
                                        <img src="{{ asset('storage/' . $treatment->foto_treatment) }}"
                                             class="w-20 h-20 object-cover rounded-md shadow">
                                    @else
                                        <div class="w-20 h-20 bg-gray-200 rounded-md flex items-center justify-center">
                                            <span class="text-xs text-gray-400">No Image</span>
                                        </div>
                                    @endif

                                    <div class="flex-1">
                                        <p class="font-semibold">{{ $treatment->nama_treatment }}</p>
                                        <p class="text-sm text-gray-600">Durasi: {{ $treatment->durasi }} menit</p>
                                        <p class="text-gray-900 font-bold">
                                            Rp {{ number_format($treatment->harga, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <input type="checkbox"
                                           name="treatments[{{ $loop->index }}][id_treatment]"
                                           value="{{ $treatment->id_treatment }}"
                                           class="treatmentCheckbox w-5 h-5 accent-[#806B3F]"
                                           data-name="{{ $treatment->nama_treatment }}"
                                           data-price="{{ $treatment->harga }}"
                                           data-durasi="{{ $treatment->durasi }}"
                                           data-id="{{ $treatment->id_treatment }}">
                                    
                                    <input type="hidden" 
                                           name="treatments[{{ $loop->index }}][quantity]" 
                                           value="1"
                                           class="treatmentQuantity">
                                </label>
                            @empty
                                <p class="text-gray-500 text-center py-4">Belum ada treatment tersedia</p>
                            @endforelse

                        </div>
                        @error('treatments')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- TIME SLOTS -->
                    <div class="md:col-span-2">
                        <label class="block text-base font-medium text-gray-700 mb-2">
                            Available Time Slots <span class="text-red-500">*</span>
                        </label>

                        <div id="timeSlotContainer" class="text-gray-400 text-sm">
                            Pilih tanggal dan treatment terlebih dahulu untuk melihat slot waktu yang tersedia.
                        </div>

                        <input type="hidden" name="jam_reservasi" id="jamReservasiInput" required>

                        @error('jam_reservasi')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- KETERANGAN (Optional) -->
                    <div class="md:col-span-2">
                        <label class="block text-base font-medium text-gray-700 mb-2">
                            Catatan (Opsional)
                        </label>
                        <textarea name="keterangan" 
                                  rows="3"
                                  placeholder="Tambahkan catatan untuk reservasi Anda..."
                                  class="w-full border border-gray-300 rounded-md px-4 py-3 
                                         focus:outline-none focus:ring-1 focus:ring-[#806B3F] 
                                         focus:border-[#806B3F]">{{ old('keterangan') }}</textarea>
                    </div>

                    <!-- SUMMARY BELOW -->
                    <div id="summaryBox" class="md:col-span-2 mt-6 hidden">
                        <p class="font-semibold mb-2">Treatment Summary:</p>
                        <div id="summaryList" class="text-gray-700 w-full"></div>

                        <div class="grid grid-cols-[180px_1fr] font-bold mt-3 text-gray-900">
                            <span>TOTAL</span>
                            <span id="totalPrice" class="text-right">Rp 0</span>
                        </div>
                    </div>

                </div> <!-- END GRID -->

            </div>

            <!-- BUTTON FIXED AT BOTTOM OF CARD -->
            <div class="mt-8 flex justify-end flex-shrink-0">
                <button id="submitBtn" type="button" disabled
                        class="px-10 py-3 rounded-md border border-[#806B3F] bg-[#f9efd7]
                               text-xl font-semibold text-[#806B3F] font-serif 
                               shadow-[3px_3px_4px_rgba(0,0,0,0.25)] 
                               hover:bg-[#e9ddc7] transition disabled:opacity-50 disabled:cursor-not-allowed">
                    Confirm
                </button>
            </div>

        </form>

    </div>
</div>


<!-- ======================= CONFIRMATION MODAL (OUTSIDE MAIN CONTAINER) ======================= -->
<div id="confirmModal"
     class="fixed inset-0 bg-black/50 flex justify-center items-center hidden"
     style="z-index: 10000;">

    <div class="bg-white rounded-[10px] shadow-lg p-8 w-[360px] md:w-[400px] 
                min-h-[520px] border border-black relative overflow-hidden">

        <h2 class="text-center text-2xl font-serif font-bold mb-8">
            Your Reservation
        </h2>

        <div class="space-y-5 text-[16px]">
            <div class="flex">
                <span class="font-bold w-32">Date</span>
                <span class="mr-1">:</span>
                <span id="modalDate"></span>
            </div>

            <div class="flex">
                <span class="font-bold w-32">Time</span>
                <span class="mr-1">:</span>
                <span id="modalTime"></span>
            </div>

            <div class="flex">
                <span class="font-bold w-32">Treatment</span>
                <span class="mr-1">:</span>
            </div>

            <div id="modalTreatments" class="w-full mt-2 text-[16px]"></div>

            <div class="flex justify-between font-bold border-t pt-4 mt-4 text-[17px]">
                <span>TOTAL</span>
                <span id="modalTotal"></span>
            </div>
        </div>

        <div class="absolute bottom-6 left-0 w-full px-10">
            <div class="flex justify-end gap-3">
                <button type="button" id="cancelBtn"
                        class="px-6 py-2 bg-gray-200 border border-gray-400 
                               rounded-md font-serif text-lg font-semibold 
                               text-gray-700 shadow hover:bg-gray-300">
                    Cancel
                </button>
                <button type="submit" id="finalConfirmBtn"
                        form="reservationForm"
                        class="px-8 py-2 bg-[#f9efd7] border border-[#806B3F] 
                               rounded-md font-serif text-lg font-semibold 
                               text-[#806B3F] shadow hover:bg-[#e8dcc4]">
                    Confirm
                </button>
            </div>

            <p class="text-red-600 text-sm mt-4 text-left leading-tight">
                *Note : Jika ingin membatalkan reservasi, silahkan hubungi admin.
            </p>
        </div>
    </div>
</div>

<script>
    const dateInput = document.getElementById('dateInput');
    const treatmentCheckboxes = document.querySelectorAll('.treatmentCheckbox');
    const submitBtn = document.getElementById('submitBtn');
    const timeSlotContainer = document.getElementById('timeSlotContainer');
    const jamReservasiInput = document.getElementById('jamReservasiInput');
    const jadwalInfo = document.getElementById('jadwalInfo');

    let selectedTimeSlot = null;

    dateInput.addEventListener("change", () => {
        dateInput.blur();
        checkJadwalOperasional();
    });

    const toggleBtn = document.getElementById('treatmentToggle');
    const treatmentBox = document.getElementById('treatmentBox');
    const toggleIcon = document.getElementById('toggleIcon');
    const summaryBox = document.getElementById('summaryBox');

    toggleBtn.addEventListener("click", (e) => {
        treatmentBox.classList.toggle("hidden");
        const isHidden = treatmentBox.classList.contains("hidden");

        toggleIcon.style.transform = isHidden ? 'rotate(0deg)' : 'rotate(180deg)';
        
        updateSelections();
    });

    const selectedTags = document.getElementById('selectedTags');
    const summaryList = document.getElementById('summaryList');
    const totalPriceEl = document.getElementById('totalPrice');

    function updateSelections() {
        selectedTags.innerHTML = "";
        summaryList.innerHTML = "";

        let total = 0;
        let hasSelection = false;

        treatmentCheckboxes.forEach(cb => {
            if (cb.checked) {
                hasSelection = true;
                const name = cb.dataset.name;
                const price = Number(cb.dataset.price);

                const tag = document.createElement("span");
                tag.className = "bg-[#806B3F] text-white px-3 py-1 rounded-full text-sm flex items-center gap-2";
                tag.innerHTML = `${name} <span class='remove-x cursor-pointer font-bold'>×</span>`;

                tag.querySelector(".remove-x").addEventListener("click", (ev) => {
                    ev.stopPropagation();        
                    cb.checked = false;          
                    updateSelections();
                    loadTimeSlots();
                });

                selectedTags.appendChild(tag);

                const row = document.createElement("div");
                row.className = "grid grid-cols-[180px_1fr] w-full";
                row.innerHTML = `
                    <span>${name}</span>
                    <span class="text-right">Rp ${price.toLocaleString("id-ID")}</span>
                `;
                summaryList.appendChild(row);

                total += price;
            }
        });

        if (!hasSelection) {
            selectedTags.innerHTML = '<span class="text-gray-400">Pilih treatment...</span>';
        }

        totalPriceEl.textContent = "Rp " + total.toLocaleString("id-ID");

        if (hasSelection && treatmentBox.classList.contains("hidden")) {
            summaryBox.classList.remove("hidden");
        } else {
            summaryBox.classList.add("hidden");
        }

        checkInputs();
    }

    treatmentCheckboxes.forEach(cb => {
        cb.addEventListener("change", (e) => {
            updateSelections();
            setTimeout(() => {
                if (cb.checked) {
                    treatmentBox.classList.add("hidden");
                    toggleIcon.style.transform = 'rotate(0deg)';
                    updateSelections();
                    loadTimeSlots();
                }
            }, 0);
        });
    });

    function checkJadwalOperasional() {
        const tanggal = dateInput.value;
        if (!tanggal) {
            jadwalInfo.classList.add('hidden');
            return;
        }

        jadwalInfo.classList.remove('hidden');
        jadwalInfo.textContent = 'Memeriksa jadwal operasional...';
        jadwalInfo.className = 'text-sm text-gray-600 mt-2';

        fetch(`{{ route('user.reservasi.get-jadwal') }}?tanggal=${tanggal}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Ambil hanya bagian jam (HH:MM) dari string datetime
                    const mulai = data.jadwal.jam_mulai.substring(11, 16); // "09:00"
                    const selesai = data.jadwal.jam_selesai.substring(11, 16); // "17:00"

                    // Ubah jadi format dengan titik (09.00 - 17.00)
                    const formatJam = (jam) => jam.replace(':', '.');

                    jadwalInfo.textContent = `Jam operasional klinik: ${formatJam(mulai)} - ${formatJam(selesai)}`;
                    jadwalInfo.className = 'text-sm text-green-600 mt-2';
                    loadTimeSlots();
                } else {
                    jadwalInfo.textContent = data.message;
                    jadwalInfo.className = 'text-sm text-red-600 mt-2';
                    timeSlotContainer.innerHTML = `<p class="text-red-500 text-sm">${data.message}</p>`;
                    jamReservasiInput.value = '';
                    selectedTimeSlot = null;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                jadwalInfo.textContent = 'Gagal memeriksa jadwal operasional.';
                jadwalInfo.className = 'text-sm text-red-600 mt-2';
            });
    }

    function loadTimeSlots() {
        const tanggal = dateInput.value;
        if (!tanggal) {
            timeSlotContainer.innerHTML = '<p class="text-gray-400 text-sm">Pilih tanggal terlebih dahulu.</p>';
            return;
        }

        let maxDurasi = 0;
        treatmentCheckboxes.forEach(cb => {
            if (cb.checked) {
                maxDurasi = Math.max(maxDurasi, Number(cb.dataset.durasi));
            }
        });

        if (maxDurasi === 0) {
            timeSlotContainer.innerHTML = '<p class="text-gray-400 text-sm">Pilih treatment terlebih dahulu.</p>';
            return;
        }

        timeSlotContainer.innerHTML = '<p class="text-gray-500 text-sm">Loading available slots...</p>';

        fetch(`{{ route('user.reservasi.get-time-slots') }}?tanggal=${tanggal}&durasi=${maxDurasi}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.slots && data.slots.length > 0) {
                    renderTimeSlots(data.slots);
                } else {
                    timeSlotContainer.innerHTML = '<p class="text-red-500 text-sm">Tidak ada slot waktu tersedia untuk tanggal ini.</p>';
                }
            })
            .catch(error => {
                console.error('Error fetching time slots:', error);
                timeSlotContainer.innerHTML = '<p class="text-red-500 text-sm">Gagal memuat slot waktu. Silakan coba lagi.</p>';
            });
    }

    function renderTimeSlots(slots) {
        timeSlotContainer.innerHTML = '';
        const grid = document.createElement('div');
        grid.className = 'grid grid-cols-4 md:grid-cols-6 gap-3';

        slots.forEach(slot => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'px-4 py-2 border border-gray-300 rounded-md text-sm font-medium hover:bg-[#806B3F] hover:text-white transition-colors';
            btn.textContent = slot.jam;
            btn.dataset.time = slot.jam;

            btn.addEventListener('click', function() {
                grid.querySelectorAll('button').forEach(b => {
                    b.classList.remove('bg-[#806B3F]', 'text-white', 'border-[#806B3F]');
                    b.classList.add('border-gray-300');
                });

                this.classList.add('bg-[#806B3F]', 'text-white', 'border-[#806B3F]');
                this.classList.remove('border-gray-300');

                selectedTimeSlot = this.dataset.time;
                jamReservasiInput.value = selectedTimeSlot;
                checkInputs();
            });

            grid.appendChild(btn);
        });

        timeSlotContainer.appendChild(grid);
    }

    function checkInputs() {
        const filled = dateInput.value !== "" && jamReservasiInput.value !== "";
        const selected = [...treatmentCheckboxes].some(cb => cb.checked);
        
        submitBtn.disabled = !(filled && selected);
        submitBtn.textContent = (filled && selected) ? "Reservation" : "Confirm";
    }

    // MODAL
    const confirmModal = document.getElementById('confirmModal');
    const modalDate = document.getElementById('modalDate');
    const modalTime = document.getElementById('modalTime');
    const modalTreatments = document.getElementById('modalTreatments');
    const modalTotal = document.getElementById('modalTotal');
    const cancelBtn = document.getElementById('cancelBtn');
    const finalConfirmBtn = document.getElementById('finalConfirmBtn');
    const reservationForm = document.getElementById('reservationForm');

    submitBtn.addEventListener("click", () => {
        console.log('Submit button clicked');
        console.log('Date:', dateInput.value);
        console.log('Time:', jamReservasiInput.value);
        console.log('Treatments:', [...treatmentCheckboxes].filter(cb => cb.checked).map(cb => cb.value));
        
        if (!dateInput.value || !jamReservasiInput.value || ![...treatmentCheckboxes].some(cb => cb.checked)) {
            alert("Harap isi semua field terlebih dahulu.");
            return;
        }

        const selectedDate = new Date(dateInput.value);
        const dateOptions = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        modalDate.textContent = selectedDate.toLocaleDateString('id-ID', dateOptions);
        
        modalTime.textContent = jamReservasiInput.value;
        modalTreatments.innerHTML = "";

        let total = 0;

        treatmentCheckboxes.forEach(cb => {
            if (cb.checked) {
                const row = document.createElement("div");
                row.className = "flex justify-between";
                row.innerHTML = `
                    <span>${cb.dataset.name}</span>
                    <span>Rp ${Number(cb.dataset.price).toLocaleString("id-ID")}</span>
                `;
                modalTreatments.appendChild(row);
                total += Number(cb.dataset.price);
            }
        });

        modalTotal.textContent = "Rp " + total.toLocaleString("id-ID");
        
        console.log('Showing modal');
        confirmModal.classList.remove("hidden");
    });

    // Handle final confirmation
    finalConfirmBtn.addEventListener("click", (e) => {
        e.preventDefault();
        console.log('Final confirm clicked');
        console.log('Form data before submit:');
        console.log('- Date:', dateInput.value);
        console.log('- Time:', jamReservasiInput.value);
        
        // PENTING: Disable semua checkbox yang TIDAK tercentang
        // supaya tidak ikut terkirim ke server
        treatmentCheckboxes.forEach((cb, index) => {
            const quantityInput = cb.closest('label').querySelector('.treatmentQuantity');
            
            if (!cb.checked) {
                // Disable checkbox dan quantity input yang tidak tercentang
                cb.disabled = true;
                if (quantityInput) quantityInput.disabled = true;
            } else {
                console.log(`Treatment ${index}: ${cb.dataset.name} - ID: ${cb.value}`);
            }
        });
        
        // Log form data untuk debugging
        const formData = new FormData(reservationForm);
        console.log('Form data yang akan dikirim:');
        for (let [key, value] of formData.entries()) {
            console.log(`${key}: ${value}`);
        }
        
        // Submit form
        console.log('Submitting form...');
        reservationForm.submit();
    });

    cancelBtn.addEventListener("click", () => {
        console.log('Cancel clicked');
        confirmModal.classList.add("hidden");
    });

    confirmModal.addEventListener("click", (e) => {
        if (e.target === confirmModal) {
            console.log('Backdrop clicked');
            confirmModal.classList.add("hidden");
        }
    });

    checkInputs();
</script>

@endsection