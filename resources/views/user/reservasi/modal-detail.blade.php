<!-- Modal Detail Reservasi -->
<div id="reservationModal"
     class="fixed inset-0 bg-black/50 flex justify-center items-center hidden z-[9999]">

    <div class="bg-white rounded-[10px] shadow-lg p-8 w-[90%] max-w-[400px] 
                border border-black relative">

        <!-- Close Button -->
        <button id="closeModal" 
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 text-2xl">
            &times;
        </button>

        <h2 class="text-center text-2xl font-serif font-bold mb-6 text-[#6B5E4A]">
            Reservation Detail
        </h2>

        <!-- Content -->
        <div class="space-y-4 text-[16px]">

            <div class="flex">
                <span class="font-bold w-32">Date</span>
                <span class="mr-2">:</span>
                <span id="modalDate" class="flex-1"></span>
            </div>

            <div class="flex">
                <span class="font-bold w-32">Time</span>
                <span class="mr-2">:</span>
                <span id="modalTime" class="flex-1"></span>
            </div>

            <div class="flex">
                <span class="font-bold w-32">Status</span>
                <span class="mr-2">:</span>
                <span id="modalStatus" class="flex-1 font-semibold"></span>
            </div>

            <div class="flex">
                <span class="font-bold w-32">Total</span>
                <span class="mr-2">:</span>
                <span id="modalTotal" class="flex-1 font-semibold text-green-600"></span>
            </div>

            <div class="flex">
                <span class="font-bold w-32">Payment</span>
                <span class="mr-2">:</span>
                <span id="modalPaymentStatus" class="flex-1"></span>
            </div>

        </div>

        <!-- Footer Note -->
        <div class="mt-6 pt-4 border-t border-gray-200">
            <p class="text-red-600 text-sm leading-tight">
                *Note: Untuk pembatalan reservasi, silahkan hubungi admin klinik.
            </p>
        </div>

    </div>

</div>