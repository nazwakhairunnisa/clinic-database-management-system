@extends('layouts.app_public')

@section('content')

@php
    $treatments = [
        [ 'nama' => 'Peeling Whitening',   'harga' => 200000, 'gambar' => 'images/promo4.jpg' ],
        [ 'nama' => 'Facial Whitening',    'harga' => 80000,  'gambar' => 'images/promo4.jpg' ],
        [ 'nama' => 'Skin Booster Acne',   'harga' => 350000, 'gambar' => 'images/promo4.jpg' ],
        [ 'nama' => 'Facial Detox',        'harga' => 350000, 'gambar' => 'images/promo4.jpg' ],
        [ 'nama' => 'Facial Hydra',        'harga' => 350000, 'gambar' => 'images/promo4.jpg' ],
    ];
@endphp

<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="min-h-screen w-full flex justify-center px-4 py-12 bg-gray-50 font-['Roboto',sans-serif]">

    <!-- CARD -->
    <div class="w-full max-w-6xl bg-white border border-black rounded-xl 
                shadow-[6px_6px_6px_rgba(0,0,0,0.28)] px-12 py-12 h-[95vh] flex flex-col mt-28">

        <!-- FORM -->
        <form id="reservationForm" method="POST" class="w-full flex-grow flex flex-col">
            @csrf

            <!-- SCROLLABLE CONTENT -->
            <div class="flex-grow overflow-y-auto pr-2">

                <!-- GRID -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-20 gap-y-10">

                    <!-- DATE -->
                    <div>
                        <label class="block text-base font-medium text-gray-700 mb-2">
                            Date <span class="text-red-500">*</span>
                        </label>

                        <input type="date" 
                               id="dateInput"
                               onclick="this.showPicker()"
                               class="w-full border border-gray-300 rounded-md px-4 py-3 
                                      focus:outline-none focus:ring-1 focus:ring-[#806B3F] 
                                      focus:border-[#806B3F] bg-white">
                    </div>

                    <!-- TIME -->
                    <div>
                        <label class="block text-base font-medium text-gray-700 mb-2">
                            Time <span class="text-red-500">*</span>
                        </label>

                        <input type="time" 
                               id="timeInput"
                               onclick="this.showPicker()"
                               class="w-full border border-gray-300 rounded-md px-4 py-3 
                                      focus:outline-none focus:ring-1 focus:ring-[#806B3F] 
                                      focus:border-[#806B3F] bg-white">
                    </div>

                    <!-- TREATMENT -->
                    <div class="md:col-span-2">

                        <label class="block text-base font-medium text-gray-700 mb-2">
                            Choose Treatment <span class="text-red-500">*</span>
                        </label>

                        <!-- SELECT BOX -->
                        <div id="treatmentToggle"
                             tabindex="0"
                             class="w-full border border-gray-300 rounded-md px-4 py-3 bg-white 
                                    cursor-pointer flex justify-between items-center 
                                    transition-all duration-150 focus:outline-none 
                                    focus:ring-1 focus:ring-[#806B3F] focus:border-[#806B3F]">

                            <div id="selectedTags" class="flex flex-wrap gap-2"></div>
                            <span id="toggleIcon" class="text-gray-600 text-lg">▼</span>

                        </div>

                        <!-- TREATMENT OPTIONS -->
                        <div id="treatmentBox"
                             class="border border-black rounded-md bg-white max-h-48 
                                    overflow-y-auto p-4 space-y-4 mt-2 hidden shrink-0">

                            @foreach($treatments as $t)
                                <label class="flex items-center gap-4 border border-gray-300 rounded-lg 
                                              p-4 cursor-pointer hover:bg-gray-50">

                                    <img src="{{ asset($t['gambar']) }}"
                                         class="w-20 h-20 object-cover rounded-md shadow">

                                    <div class="flex-1">
                                        <p class="font-semibold">{{ $t['nama'] }}</p>
                                        <p class="text-gray-900 font-bold">
                                            RP {{ number_format($t['harga'], 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <input type="checkbox"
                                           class="treatmentCheckbox w-5 h-5 accent-[#806B3F]"
                                           data-name="{{ $t['nama'] }}"
                                           data-price="{{ $t['harga'] }}">
                                </label>
                            @endforeach

                        </div>
                    </div>

                    <!-- SUMMARY BELOW -->
                    <div id="summaryBox" class="md:col-span-2 mt-6 hidden">
                        <p class="font-semibold mb-2">Treatment :</p>
                        <div id="summaryList" class="text-gray-700 w-full"></div>

                        <div class="grid grid-cols-[180px_1fr] font-bold mt-3 text-gray-900">
                            <span>TOTAL</span>
                            <span id="totalPrice" class="text-right">RP 0</span>
                        </div>
                    </div>

                </div> <!-- END GRID -->

            </div>

            <!-- BUTTON FIXED AT BOTTOM -->
            <div class="mt-6 flex justify-end flex-shrink-0">
                <button id="submitBtn" type="button"
                        class="px-10 py-3 rounded-md border border-[#806B3F] bg-[#f9efd7]
                               text-xl font-semibold text-[#806B3F] font-serif 
                               shadow-[3px_3px_4px_rgba(0,0,0,0.25)] 
                               hover:bg-[#e9ddc7] transition">
                    Confirm
                </button>
            </div>

        </form>

    </div>
</div>


<!-- ======================= CONFIRMATION MODAL ======================= -->
<div id="confirmModal"
     class="fixed inset-0 bg-black/50 flex justify-center items-start pt-20 hidden z-[9999]">

    <div class="bg-white rounded-[10px] shadow-lg p-8 w-[360px] md:w-[400px] 
                min-h-[520px] border border-black relative overflow-hidden">

        <h2 class="text-center text-2xl font-serif font-bold mb-8">
            Your Reservation
        </h2>

        <!-- CONTENT -->
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

        <!-- BUTTON + NOTE -->
        <div class="absolute bottom-6 left-0 w-full px-10">

            <div class="flex justify-end">
                <button id="finalConfirmBtn"
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
const timeInput = document.getElementById('timeInput');
const treatmentCheckboxes = document.querySelectorAll('.treatmentCheckbox');
const submitBtn = document.getElementById('submitBtn');

dateInput.addEventListener("change", () => dateInput.blur());
timeInput.addEventListener("change", () => timeInput.blur());
timeInput.addEventListener("focus", () => timeInput.showPicker());

function checkInputs() {
    const filled = dateInput.value !== "" && timeInput.value !== "";
    const selected = [...treatmentCheckboxes].some(cb => cb.checked);
    submitBtn.textContent = (filled && selected) ? "Reservation" : "Confirm";
}


//    TREATMENT COLLAPSE
const toggleBtn = document.getElementById('treatmentToggle');
const treatmentBox = document.getElementById('treatmentBox');
const toggleIcon = document.getElementById('toggleIcon');
const summaryBox = document.getElementById('summaryBox');

toggleBtn.addEventListener("click", (e) => {
    // Toggle dropdown
    treatmentBox.classList.toggle("hidden");
    const hidden = treatmentBox.classList.contains("hidden");
    toggleIcon.textContent = hidden ? "▼" : "▲";

    updateSelections();

    // focus/blur effect
    toggleBtn.focus();
    if (hidden) toggleBtn.blur();
});


//    SUMMARY + TAG
const selectedTags = document.getElementById('selectedTags');
const summaryList = document.getElementById('summaryList');
const totalPriceEl = document.getElementById('totalPrice');

function updateSelections() {
    selectedTags.innerHTML = "";
    summaryList.innerHTML = "";

    let total = 0;

    treatmentCheckboxes.forEach(cb => {
        if (cb.checked) {
            const name = cb.dataset.name;
            const price = Number(cb.dataset.price);

            // TAG
            const tag = document.createElement("span");
            tag.className =
                "bg-[#806B3F] text-white px-3 py-1 rounded-full text-sm flex items-center gap-2";
            tag.innerHTML = `${name} <span class='remove-x cursor-pointer font-bold'>×</span>`;

            // stopPropagation
            tag.querySelector(".remove-x").addEventListener("click", (ev) => {
                ev.stopPropagation();        
                cb.checked = false;          
                updateSelections();          
                checkInputs();
            });

            selectedTags.appendChild(tag);

            // SUMMARY ROW
            const row = document.createElement("div");
            row.className = "grid grid-cols-[180px_1fr] w-full";
            row.innerHTML = `
                <span>${name}</span>
                <span class="text-right">RP ${price.toLocaleString("id-ID")}</span>
            `;
            summaryList.appendChild(row);

            total += price;
        }
    });

    totalPriceEl.textContent = "RP " + total.toLocaleString("id-ID");

    const hasSelected = [...treatmentCheckboxes].some(cb => cb.checked);
    if (hasSelected && treatmentBox.classList.contains("hidden")) {
        summaryBox.classList.remove("hidden");
    } else {
        summaryBox.classList.add("hidden");
    }

    checkInputs();
}


//    REGISTER CHECKBOX LISTENERS
treatmentCheckboxes.forEach(cb => {
    cb.addEventListener("change", (e) => {

        updateSelections();

        setTimeout(() => {
            if (cb.checked) {
                // close dropdown
                treatmentBox.classList.add("hidden");
                toggleIcon.textContent = "▼";

                updateSelections();
            }
        }, 0);
    });
});


// MODAL LOGIC 
const confirmModal = document.getElementById('confirmModal');
const modalDate = document.getElementById('modalDate');
const modalTime = document.getElementById('modalTime');
const modalTreatments = document.getElementById('modalTreatments');
const modalTotal = document.getElementById('modalTotal');
const finalConfirmBtn = document.getElementById('finalConfirmBtn');

submitBtn.addEventListener("click", () => {

    if (!dateInput.value || !timeInput.value || ![...treatmentCheckboxes].some(cb => cb.checked)) {
        alert("Harap isi semua field terlebih dahulu.");
        return;
    }

    modalDate.textContent = dateInput.value;
    modalTime.textContent = timeInput.value;
    modalTreatments.innerHTML = "";

    let total = 0;

    treatmentCheckboxes.forEach(cb => {
        if (cb.checked) {
            const row = document.createElement("div");
            row.className = "flex justify-between";
            row.innerHTML = `
                <span>${cb.dataset.name}</span>
                <span>RP ${Number(cb.dataset.price).toLocaleString("id-ID")}</span>
            `;
            modalTreatments.appendChild(row);

            total += Number(cb.dataset.price);
        }
    });

    modalTotal.textContent = "RP " + total.toLocaleString("id-ID");

    confirmModal.classList.remove("hidden");
});

confirmModal.addEventListener("click", (e) => {
    if (e.target === confirmModal)
        confirmModal.classList.add("hidden");
});

finalConfirmBtn.addEventListener("click", () => {
    alert("Reservation confirmed!");
    confirmModal.classList.add("hidden");
});
</script>
