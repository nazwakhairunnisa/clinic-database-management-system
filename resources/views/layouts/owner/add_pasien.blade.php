@extends('layouts.owner.app')

@section('pageTitle', 'Add Patient')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<div class="font-['Roboto'] bg-[#F8F6F1] min-h-screen flex justify-center items-start pt-8 sm:pt-12">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-4xl p-6 sm:p-8 transition-all duration-300">
        
        {{-- Header --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-black">Add Patient & Reservation</h2>
            <a href="{{ route('owner.pasien') }}" class="text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-xl"></i>
            </a>
        </div>

        {{-- ERROR MESSAGES --}}
        @if(session('error'))
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-red-700">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6 rounded-r-lg">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fa-solid fa-circle-exclamation text-red-500 text-xl"></i>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terdapat error pada form:</h3>
                        <ul class="mt-2 text-sm text-red-700 list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <form action="{{ route('owner.pasien.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- SECTION 1: DATA PASIEN --}}
            <div class="border border-gray-200 rounded-lg p-5">
                <h3 class="text-md font-semibold text-gray-700 mb-4">Data Pasien</h3>
                
                {{-- Row 1 --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Nama Depan <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_depan" value="{{ old('nama_depan') }}" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm" required>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Nama Belakang <span class="text-red-500">*</span></label>
                        <input type="text" name="nama_belakang" value="{{ old('nama_belakang') }}" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm" required>
                    </div>
                </div>

                {{-- Row 2 --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">No. Telepon <span class="text-red-500">*</span></label>
                        <input type="text" name="no_telepon" value="{{ old('no_telepon') }}" 
                            placeholder="08xxxxxxxxx" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm" required>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Tanggal Lahir <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm" required>
                    </div>
                </div>

                {{-- Row 3 --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Jenis Kelamin <span class="text-red-500">*</span></label>
                        <select name="jenis_kelamin" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm" required>
                            <option value="">- Pilih -</option>
                            <option value="L" {{ old('jenis_kelamin') == 'L' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="P" {{ old('jenis_kelamin') == 'P' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                </div>

                {{-- Alamat --}}
                <div>
                    <label class="text-sm font-medium text-gray-600">Alamat <span class="text-red-500">*</span></label>
                    <textarea name="alamat" rows="2" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm" required>{{ old('alamat') }}</textarea>
                </div>
            </div>

            {{-- SECTION 2: DATA RESERVASI --}}
            <div class="border border-gray-200 rounded-lg p-5">
                <h3 class="text-md font-semibold text-gray-700 mb-4">Data Reservasi</h3>

                {{-- Date & Time --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Tanggal Reservasi <span class="text-red-500">*</span></label>
                        <input type="date" name="tanggal_reservasi" value="{{ old('tanggal_reservasi', date('Y-m-d')) }}" 
                            min="{{ date('Y-m-d') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm" required>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Jam Reservasi <span class="text-red-500">*</span></label>
                        <input type="time" name="jam_reservasi" value="{{ old('jam_reservasi') }}" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm" required>
                    </div>
                </div>

                {{-- Jadwal Operasional --}}
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-600">Jadwal Operasional <span class="text-red-500">*</span></label>
                    <select name="id_jadwal" 
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm" required>
                        <option value="">- Pilih Jadwal -</option>
                        @foreach($jadwal as $j)
                        <option value="{{ $j->id_jadwal }}" 
                            {{ old('id_jadwal') == $j->id_jadwal ? 'selected' : ($jadwalHariIni && $jadwalHariIni->id_jadwal == $j->id_jadwal && !old('id_jadwal') ? 'selected' : '') }}>
                            {{ $j->hari_tanggal->format('d M Y') }} - {{ $j->jam_mulai->format('H:i') }} s/d {{ $j->jam_selesai->format('H:i') }}
                            ({{ $j->status == 'available' ? 'Tersedia' : 'Penuh' }})
                        </option>
                        @endforeach
                    </select>
                    <small class="text-gray-500">Pilih slot waktu yang tersedia</small>
                </div>

                {{-- Treatments --}}
                <div class="mb-4">
                    <label class="text-sm font-medium text-gray-600 mb-2 block">Treatment <span class="text-red-500">*</span></label>
                    <div id="treatment-container" class="space-y-3">
                        <div class="treatment-item border border-gray-300 rounded-lg p-3">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                <div class="sm:col-span-2">
                                    <select name="treatments[0][id_treatment]" 
                                        class="treatment-select w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm" required>
                                        <option value="">- Pilih Treatment -</option>
                                        @foreach($treatments as $t)
                                        <option value="{{ $t->id_treatment }}" data-harga="{{ $t->harga }}">
                                            {{ $t->nama_treatment }} - {{ $t->formatted_harga }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex gap-2">
                                    <input type="number" name="treatments[0][quantity]" value="1" min="1" 
                                        class="quantity-input w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm" 
                                        placeholder="Qty" required>
                                    <button type="button" onclick="removeTreatment(this)" 
                                        class="remove-treatment hidden bg-red-500 text-white px-3 rounded-lg hover:bg-red-600">
                                        <iconify-icon icon="mdi:close"></iconify-icon>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" onclick="addTreatment()" 
                        class="mt-3 text-sm text-blue-600 hover:text-blue-800 font-medium">
                        <iconify-icon icon="mdi:plus-circle" class="text-lg"></iconify-icon> Tambah Treatment
                    </button>
                </div>

                {{-- Total Biaya (Auto Calculate) --}}
                <div class="bg-gray-50 border border-gray-300 rounded-lg p-4">
                    <div class="flex justify-between items-center">
                        <span class="text-sm font-medium text-gray-700">Total Biaya:</span>
                        <span id="total-biaya" class="text-lg font-bold text-gray-900">Rp 0</span>
                    </div>
                </div>
            </div>

            {{-- SECTION 3: DATA PEMBAYARAN --}}
            <div class="border border-gray-200 rounded-lg p-5">
                <h3 class="text-md font-semibold text-gray-700 mb-4">Data Pembayaran</h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="text-sm font-medium text-gray-600">Metode Pembayaran <span class="text-red-500">*</span></label>
                        <select name="metode_pembayaran" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm" required>
                            <option value="">- Pilih Metode -</option>
                            <option value="cash" {{ old('metode_pembayaran') == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="ewallet" {{ old('metode_pembayaran') == 'ewallet' ? 'selected' : '' }}>E-Wallet</option>
                        </select>
                    </div>
                    <div>
                        <label class="text-sm font-medium text-gray-600">Status Pembayaran <span class="text-red-500">*</span></label>
                        <select name="status_pembayaran" 
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm" required>
                            <option value="belum" {{ old('status_pembayaran') == 'belum' ? 'selected' : '' }}>Belum Dibayar</option>
                            <option value="lunas" {{ old('status_pembayaran') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                        </select>
                        <small class="text-gray-500">Jika lunas, akan otomatis tercatat di pendapatan</small>
                    </div>
                </div>
            </div>

            {{-- Submit Button --}}
            <div class="pt-2 flex gap-3">
                <button type="submit" 
                    class="bg-[#2F9CCA] text-white px-6 py-2 rounded-lg hover:bg-[#1788b4] transition-all duration-200">
                    <iconify-icon icon="mdi:content-save" class="text-lg mr-1"></iconify-icon>
                    Simpan Data
                </button>
                <a href="{{ route('owner.pasien') }}" 
                    class="bg-gray-500 text-white px-6 py-2 rounded-lg hover:bg-gray-600 transition-all duration-200">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

<script>
let treatmentCount = 1;

function addTreatment() {
    const container = document.getElementById('treatment-container');
    const newItem = document.createElement('div');
    newItem.className = 'treatment-item border border-gray-300 rounded-lg p-3';
    newItem.innerHTML = `
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="sm:col-span-2">
                <select name="treatments[${treatmentCount}][id_treatment]" 
                    class="treatment-select w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm" required>
                    <option value="">- Pilih Treatment -</option>
                    @foreach($treatments as $t)
                    <option value="{{ $t->id_treatment }}" data-harga="{{ $t->harga }}">
                        {{ $t->nama_treatment }} - {{ $t->formatted_harga }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <input type="number" name="treatments[${treatmentCount}][quantity]" value="1" min="1" 
                    class="quantity-input w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-[#EED892] outline-none text-sm" 
                    placeholder="Qty" required>
                <button type="button" onclick="removeTreatment(this)" 
                    class="remove-treatment bg-red-500 text-white px-3 rounded-lg hover:bg-red-600">
                    <iconify-icon icon="mdi:close"></iconify-icon>
                </button>
            </div>
        </div>
    `;
    container.appendChild(newItem);
    treatmentCount++;
    
    // Show remove buttons if more than 1 item
    updateRemoveButtons();
    
    // Add event listener for new selects
    attachCalculateListeners();
}

function removeTreatment(button) {
    button.closest('.treatment-item').remove();
    updateRemoveButtons();
    calculateTotal();
}

function updateRemoveButtons() {
    const items = document.querySelectorAll('.treatment-item');
    const removeButtons = document.querySelectorAll('.remove-treatment');
    
    if (items.length > 1) {
        removeButtons.forEach(btn => btn.classList.remove('hidden'));
    } else {
        removeButtons.forEach(btn => btn.classList.add('hidden'));
    }
}

function calculateTotal() {
    let total = 0;
    const treatmentItems = document.querySelectorAll('.treatment-item');
    
    treatmentItems.forEach(item => {
        const select = item.querySelector('.treatment-select');
        const quantity = item.querySelector('.quantity-input').value;
        
        if (select.value) {
            const harga = parseFloat(select.options[select.selectedIndex].dataset.harga);
            total += harga * parseInt(quantity);
        }
    });
    
    document.getElementById('total-biaya').textContent = 
        'Rp ' + total.toLocaleString('id-ID');
}

function attachCalculateListeners() {
    document.querySelectorAll('.treatment-select, .quantity-input').forEach(element => {
        element.removeEventListener('change', calculateTotal);
        element.removeEventListener('input', calculateTotal);
        element.addEventListener('change', calculateTotal);
        element.addEventListener('input', calculateTotal);
    });
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    attachCalculateListeners();
    updateRemoveButtons();
});
</script>

@endsection