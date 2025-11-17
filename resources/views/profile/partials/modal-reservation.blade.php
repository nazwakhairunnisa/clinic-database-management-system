<div id="historyModal"
     class="fixed inset-0 bg-black/50 flex justify-center items-start pt-20 hidden z-[9999]">

    <div class="bg-white rounded-[10px] shadow-lg p-8 w-[360px] md:w-[400px] 
                min-h-[400px] border border-black relative overflow-hidden">

        <h2 class="text-center text-2xl mb-8">
             Your Reservation
        </h2>

        <!-- CONTENT -->
        <div class="space-y-5 text-[16px]">

            <div class="flex">
                <span class="w-32">Date</span>
                <span class="mr-1">:</span>
                <span id="hDate"></span>
            </div>

            <div class="flex">
                <span class="w-32">Time</span>
                <span class="mr-1">:</span>
                <span id="hTime"></span>
            </div>

            <div class="flex">
                <span class="w-32">Treatment</span>
                <span class="mr-1">:</span>
            </div>

            <div id="hTreatments" class="w-full mt-2 text-[16px]"></div>

            <div class="flex justify-between border-t pt-4 mt-4 text-[17px]">
                <span>TOTAL</span>
                <span id="hTotal"></span>
            </div>

        </div>

    </div>
</div>
