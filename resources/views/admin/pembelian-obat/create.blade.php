@extends('layouts.admin')

@section('pageTitle', 'Tambah Pembelian Obat')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<div class="font-['Roboto',sans-serif] bg-[#F8F6F1] min-h-screen flex justify-center items-start pt-10 sm:pt-14">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-3xl p-6 sm:p-8 transition-all duration-300">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-black">Tambah Pembelian Obat</h2>
            <a href="{{ route('admin.pembelian_obat.index') }}" class="text-gray-400 hover:text-gray-600 transition">
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

        {{-- FORM --}}
        <form action="{{ route('admin.pembelian_obat.store') }}" method="POST" class="space-y-5" id="formPembelian">
            @csrf

            {{-- ROW 1: Nama Obat + Tanggal Pembelian --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Nama Obat --}}
                <div>
                    <label class="text-sm font-medium text-gray-600">Nama Obat <span class="text-red-500">*</span></label>
                    <select name="id_obat" id="id_obat" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                        <option value="">- Pilih Obat -</option>
                        @foreach($obatList as $obat)
                        <option value="{{ $obat->id_obat }}" {{ old('id_obat') == $obat->id_obat ? 'selected' : '' }}>
                            {{ $obat->nama_obat }} ({{ $obat->satuan }})
                        </option>
                        @endforeach
                    </select>
                    @error('id_obat')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Tanggal Pembelian --}}
                <div>
                    <label class="text-sm font-medium text-gray-600">Tanggal Pembelian <span class="text-red-500">*</span></label>
                    <input type="date" name="tanggal_beli" value="{{ old('tanggal_beli', date('Y-m-d')) }}" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                    @error('tanggal_beli')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ROW 2: Supplier + Jumlah --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Supplier --}}
                <div>
                    <label class="text-sm font-medium text-gray-600">Supplier <span class="text-red-500">*</span></label>
                    <select name="id_supplier" required
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                        <option value="">- Pilih Supplier -</option>
                        @foreach($suppliers as $supplier)
                        <option value="{{ $supplier->id_supplier }}" {{ old('id_supplier') == $supplier->id_supplier ? 'selected' : '' }}>
                            {{ $supplier->nama_supplier }}
                        </option>
                        @endforeach
                    </select>
                    @error('id_supplier')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Jumlah --}}
                <div>
                    <label class="text-sm font-medium text-gray-600">Jumlah <span class="text-red-500">*</span></label>
                    <input type="number" name="jumlah" value="{{ old('jumlah', 1) }}" min="1" required
                        id="jumlah" oninput="hitungTotal()"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                    @error('jumlah')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ROW 3: Harga Satuan + Status --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Harga Satuan --}}
                <div>
                    <label class="text-sm font-medium text-gray-600">Harga Satuan <span class="text-red-500">*</span></label>
                    <input type="number" name="harga_satuan" value="{{ old('harga_satuan', 0) }}" min="0" required
                        id="harga_satuan" oninput="hitungTotal()"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none"
                        placeholder="0">
                    @error('harga_satuan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Status Pembayaran --}}
                <div>
                    <label class="text-sm font-medium text-gray-600">Status Pembayaran <span class="text-red-500">*</span></label>
                    <select name="status_pembayaran" id="status_pembayaran" required onchange="toggleFields()"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                        <option value="">- Pilih Status -</option>
                        <option value="lunas" {{ old('status_pembayaran') == 'lunas' ? 'selected' : '' }}>Sudah Dibayar</option>
                        <option value="belum" {{ old('status_pembayaran') == 'belum' ? 'selected' : '' }}>Belum Dibayar</option>
                    </select>
                    @error('status_pembayaran')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ROW 4: Total Harga + CONDITIONAL (Metode Pembayaran ATAU Tanggal Jatuh Tempo) --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Total Harga (Display Only) --}}
                <div>
                    <label class="text-sm font-medium text-gray-600">Total Harga</label>
                    <div class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm bg-gray-100 text-gray-700 font-semibold" id="totalHarga">
                        Rp 0
                    </div>
                </div>

                {{-- CONDITIONAL FIELD - Saling Gantian di Posisi yang Sama --}}
                <div>
                    {{-- Metode Pembayaran (Muncul jika Lunas) --}}
                    <div id="metode-pembayaran-container" style="display: none;">
                        <label class="text-sm font-medium text-gray-600">
                            Metode Pembayaran <span class="text-red-500">*</span>
                        </label>
                        <select name="metode_pembayaran" id="metode_pembayaran"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                            <option value="">- Pilih Metode -</option>
                            <option value="cash">Cash</option>
                            <option value="transfer">Transfer Bank</option>
                            <option value="ewallet">E-Wallet</option>
                        </select>
                        @error('metode_pembayaran')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tanggal Jatuh Tempo (Muncul jika Belum Dibayar) --}}
                    <div id="jatuhtempo-container" style="display: none;">
                        <label class="text-sm font-medium text-gray-600">
                            Tanggal Jatuh Tempo <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="tanggal_jatuh_tempo" value="{{ old('tanggal_jatuh_tempo') }}" id="tanggal_jatuh_tempo"
                            min="{{ date('Y-m-d') }}"
                            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#EED892] outline-none">
                        @error('tanggal_jatuh_tempo')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Submit --}}
            <div class="pt-2 flex space-x-3">
                <button type="submit" 
                    class="bg-[#2F9CCA] text-white px-6 py-2 rounded-lg hover:bg-[#1788b4] transition-all duration-200">
                    <i class="fa-solid fa-save mr-2"></i>
                    Simpan
                </button>
                <a href="{{ route('admin.pembelian_obat.index') }}"
                    class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400 transition-all duration-200">
                    <i class="fa-solid fa-times mr-2"></i>
                    Batal
                </a>
            </div>

        </form>

    </div>
</div>

<script>
// Toggle fields based on status pembayaran
function toggleFields() {
    const status = document.getElementById('status_pembayaran').value;
    const jatuhTempoContainer = document.getElementById('jatuhtempo-container');
    const jatuhTempoInput = document.getElementById('tanggal_jatuh_tempo');
    const metodePembayaranContainer = document.getElementById('metode-pembayaran-container');
    const metodePembayaranInput = document.getElementById('metode_pembayaran');
    
    if (status === 'belum') {
        // Belum Dibayar: Tampilkan Jatuh Tempo, Sembunyikan Metode Pembayaran
        jatuhTempoContainer.style.display = 'block';
        jatuhTempoInput.required = true;
        
        metodePembayaranContainer.style.display = 'none';
        metodePembayaranInput.required = false;
        metodePembayaranInput.value = '';
        
    } else if (status === 'lunas') {
    jatuhTempoContainer.style.display = 'none';
    jatuhTempoInput.required = false;
    jatuhTempoInput.value = '';

    metodePembayaranContainer.style.display = 'block';
    metodePembayaranInput.required = true;  // Wajib hanya saat lunas
    
    } else {
        // Tidak ada yang dipilih
        jatuhTempoContainer.style.display = 'none';
        jatuhTempoInput.required = false;
        metodePembayaranContainer.style.display = 'none';
        metodePembayaranInput.required = false;
    }
}

// Hitung total harga
function hitungTotal() {
    const jumlah = parseFloat(document.getElementById('jumlah').value) || 0;
    const harga = parseFloat(document.getElementById('harga_satuan').value) || 0;
    const total = jumlah * harga;
    
    document.getElementById('totalHarga').textContent = 'Rp ' + total.toLocaleString('id-ID');
}

// Run on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleFields();
    hitungTotal();
});
</script>
@endsection