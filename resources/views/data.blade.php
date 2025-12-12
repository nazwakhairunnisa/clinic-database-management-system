<x-guest-layout>
<div class="min-h-screen bg-gray-50 text-gray-800 overflow-x-hidden flex flex-col">

        {{-- TOP BAR --}}
        <header class="w-full bg-white border-b border-gray-300
               shadow-[0_2px_4px_rgba(0,0,0,0.15)]">
            <div class="w-full flex items-center justify-between pl-0 pr-12 h-[90px]">

                {{-- LOGO --}}
                <div class="flex items-center">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="Logo" 
                         class="h-[70px] w-auto object-contain">
                </div>

                {{-- Title --}}
                <h1 class="flex-1 text-center text-3xl font-serif font-bold text-[#806B3F]">
                    Data Pasien
                </h1>

                {{-- Placeholder kanan --}}
                <div class="w-[70px]"></div>

            </div>
        </header>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 w-full flex justify-center px-4 py-10">

            {{-- CARD --}}
            <div class="bg-white border border-black rounded-md
                        shadow-[6px_6px_8px_rgba(0,0,0,0.35)]
                        w-full max-w-6xl mx-auto
                        px-10 py-14">

                {{-- FORM --}}
                <div class="max-w-4xl mx-auto">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-x-20 gap-y-10">

                        {{-- First Name --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2
                                          focus:outline-none focus:ring-1 focus:ring-[#806B3F]
                                          focus:border-[#806B3F] bg-white">
                        </div>

                        {{-- Last Name --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2
                                          focus:outline-none focus:ring-1 focus:ring-[#806B3F]
                                          focus:border-[#806B3F] bg-white">
                        </div>

                        {{-- Date of Birth --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Date of Birth <span class="text-red-500">*</span>
                            </label>
                            <input type="date" id="dob"
                                   class="w-full border border-gray-300 rounded-md px-3 py-2
                                          focus:outline-none focus:ring-1 focus:ring-[#806B3F]
                                          focus:border-[#806B3F] bg-white">
                        </div>

                        {{-- Gender --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                Gender <span class="text-red-500">*</span>
                            </label>
                            <select
                                class="w-full border border-gray-300 rounded-md px-3 py-2
                                       focus:outline-none focus:ring-1 focus:ring-[#806B3F]
                                       focus:border-[#806B3F] bg-white">
                                <option value="" disabled selected>Select</option>
                                <option value="female">Female</option>
                                <option value="male">Male</option>
                            </select>
                        </div>

                    </div>

                    {{-- BUTTON --}}
                    <div class="mt-14 flex justify-end">
                        <button
                            class="px-8 py-2.5 rounded-md border border-[#806B3F] bg-[#f9efd7]
                                   text-sm font-semibold text-[#806B3F] font-serif
                                   shadow-[3px_3px_4px_rgba(0,0,0,0.25)]
                                   hover:bg-[#e9ddc7]">
                            Confirm
                        </button>
                    </div>

                </div>
            </div>
        </main>

    </div>
    
    <script>
        const today = new Date();
        const year = today.getFullYear() - 15;
        const month = String(today.getMonth() + 1).padStart(2, '0');
        const day = String(today.getDate()).padStart(2, '0');

        const maxDate = `${year}-${month}-${day}`;
        document.getElementById("dob").setAttribute("max", maxDate);
        document.getElementById("dob").setAttribute("min", "1900-01-01");
    </script>

</x-guest-layout>
