@extends('layouts.admin')

@section('pageTitle', 'Proses Pembayaran')

@section('content')

<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<div class="max-w-4xl mx-auto font-['Roboto',sans-serif] py-6">

    {{-- BACK BUTTON --}}
    <a href="{{ route('admin.pembayaran.index') }}" 
        class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4">
        <iconify-icon icon="mdi:arrow-left" class="text-xl mr-1"></iconify-icon>
        Kembali ke List
    </a>

    {{-- ERROR MESSAGE --}}
    @if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4" role="alert">
        <span class="block sm:inline">{{ session('error') }}</span>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- MAIN CARD --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        
        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Proses Pembayaran</h1>
                    <p class="text-sm opacity-90 mt-1">
                        ID Reservasi: R-{{ str_pad($pembayaran->reservasi->id_reservasi, 4, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
                <iconify-icon icon="mdi:cash-register" class="text-5xl opacity-30"></iconify-icon>
            </div>
        </div>

        {{-- BODY --}}
        <div class="p-6">
            
            {{-- INFO PASIEN --}}
            <div class="bg-gray-50 rounded-xl p-4 mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                    <iconify-icon icon="mdi:account" class="text-lg mr-2"></iconify-icon>
                    Informasi Pasien
                </h3>
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <p class="text-gray-500">Nama</p>
                        <p class="font-medium">
                            {{ $pembayaran->reservasi->pasien->nama_depan }} 
                            {{ $pembayaran->reservasi->pasien->nama_belakang }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500">No Telepon</p>
                        <p class="font-medium">{{ $pembayaran->reservasi->pasien->no_telepon }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500">Tanggal Treatment</p>
                        <p class="font-medium">
                            {{ $pembayaran->reservasi->tanggal_reservasi->format('d M Y') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500">Jam</p>
                        <p class="font-medium">
                            {{ $pembayaran->reservasi->jam_reservasi->format('H:i') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- DETAIL TREATMENT --}}
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                    <iconify-icon icon="mdi:spa" class="text-lg mr-2"></iconify-icon>
                    Detail Treatment
                </h3>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2 text-left font-medium">Treatment</th>
                                <th class="px-4 py-2 text-center font-medium">Qty</th>
                                <th class="px-4 py-2 text-right font-medium">Harga</th>
                                <th class="px-4 py-2 text-right font-medium">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($pembayaran->reservasi->detailReservasi as $detail)
                            <tr>
                                <td class="px-4 py-3">{{ $detail->treatment->nama_treatment }}</td>
                                <td class="px-4 py-3 text-center">{{ $detail->quantity }}</td>
                                <td class="px-4 py-3 text-right">
                                    Rp {{ number_format($detail->harga_saat_reservasi, 0, ',', '.') }}
                                </td>
                                <td class="px-4 py-3 text-right font-medium">
                                    Rp {{ number_format($detail->harga_saat_reservasi * $detail->quantity, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="3" class="px-4 py-3 text-right font-bold">TOTAL</td>
                                <td class="px-4 py-3 text-right font-bold text-blue-600 text-lg">
                                    Rp {{ number_format($pembayaran->total_pembayaran, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- FORM PEMBAYARAN --}}
            <form method="POST" 
                  action="{{ route('admin.pembayaran.proses', $pembayaran->id_pembayaran) }}"
                  enctype="multipart/form-data"
                  id="paymentForm">
                @csrf
                @method('PUT')

                <div class="bg-blue-50 border border-blue-200 rounded-xl p-5 mb-6">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <iconify-icon icon="mdi:cash" class="text-lg mr-2 text-blue-600"></iconify-icon>
                        Proses Pembayaran
                    </h3>

                    <div class="space-y-4">
                        {{-- Metode Pembayaran --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Metode Pembayaran <span class="text-red-500">*</span>
                            </label>
                            <div class="grid grid-cols-3 gap-3">
                                <label class="relative flex items-center justify-center p-3 border-2 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                    <input type="radio" name="metode_pembayaran" value="cash" class="sr-only peer" required>
                                    <div class="text-center peer-checked:text-blue-600">
                                        <iconify-icon icon="mdi:cash" class="text-3xl"></iconify-icon>
                                        <p class="text-xs font-medium mt-1">Cash</p>
                                    </div>
                                    <div class="absolute inset-0 border-2 border-blue-600 rounded-lg hidden peer-checked:block"></div>
                                </label>

                                <label class="relative flex items-center justify-center p-3 border-2 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                    <input type="radio" name="metode_pembayaran" value="transfer" class="sr-only peer">
                                    <div class="text-center peer-checked:text-blue-600">
                                        <iconify-icon icon="mdi:bank-transfer" class="text-3xl"></iconify-icon>
                                        <p class="text-xs font-medium mt-1">Transfer</p>
                                    </div>
                                    <div class="absolute inset-0 border-2 border-blue-600 rounded-lg hidden peer-checked:block"></div>
                                </label>

                                <label class="relative flex items-center justify-center p-3 border-2 rounded-lg cursor-pointer hover:border-blue-500 transition">
                                    <input type="radio" name="metode_pembayaran" value="ewallet" class="sr-only peer">
                                    <div class="text-center peer-checked:text-blue-600">
                                        <iconify-icon icon="mdi:wallet" class="text-3xl"></iconify-icon>
                                        <p class="text-xs font-medium mt-1">E-Wallet</p>
                                    </div>
                                    <div class="absolute inset-0 border-2 border-blue-600 rounded-lg hidden peer-checked:block"></div>
                                </label>
                            </div>
                        </div>

                        {{-- Jumlah Bayar --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Jumlah Dibayar <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                                <input type="number" 
                                       name="jumlah_bayar" 
                                       id="jumlahBayar"
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500"
                                       placeholder="0"
                                       min="{{ $pembayaran->total_pembayaran }}"
                                       required>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Minimal: Rp {{ number_format($pembayaran->total_pembayaran, 0, ',', '.') }}</p>
                        </div>

                        {{-- Kembalian (Auto Calculate) --}}
                        <div class="bg-white rounded-lg p-4 border-2 border-dashed border-gray-300">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-gray-700">Kembalian</span>
                                <span id="kembalian" class="text-xl font-bold text-green-600">Rp 0</span>
                            </div>
                        </div>

                        {{-- Bukti Pembayaran (Optional) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Bukti Pembayaran (Optional)
                            </label>
                            <input type="file" 
                                   name="bukti_pembayaran" 
                                   accept="image/*"
                                   class="w-full text-sm text-gray-500
                                          file:mr-4 file:py-2 file:px-4
                                          file:rounded-lg file:border-0
                                          file:text-sm file:font-medium
                                          file:bg-blue-50 file:text-blue-700
                                          hover:file:bg-blue-100">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG (Max 2MB)</p>
                        </div>
                    </div>
                </div>

                {{-- BUTTONS --}}
                <div class="flex gap-3">
                    <button type="submit" 
                            class="flex-1 bg-green-600 text-white py-3 rounded-lg hover:bg-green-700 font-medium text-sm shadow-lg hover:shadow-xl transition">
                        <iconify-icon icon="mdi:check-circle" class="text-xl mr-1"></iconify-icon>
                        Proses Pembayaran
                    </button>
                    <a href="{{ route('admin.pembayaran.index') }}" 
                       class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium text-sm">
                        Batal
                    </a>
                </div>
            </form>

        </div>
    </div>
</div>

{{-- AUTO CALCULATE KEMBALIAN --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const jumlahBayar = document.getElementById('jumlahBayar');
    const kembalian = document.getElementById('kembalian');
    const totalTagihan = {{ $pembayaran->total_pembayaran }};

    jumlahBayar.addEventListener('input', function() {
        const bayar = parseFloat(this.value) || 0;
        const kembali = bayar - totalTagihan;
        
        if (kembali >= 0) {
            kembalian.textContent = 'Rp ' + kembali.toLocaleString('id-ID');
            kembalian.classList.remove('text-red-600');
            kembalian.classList.add('text-green-600');
        } else {
            kembalian.textContent = 'Kurang Rp ' + Math.abs(kembali).toLocaleString('id-ID');
            kembalian.classList.remove('text-green-600');
            kembalian.classList.add('text-red-600');
        }
    });
});
</script>

@endsection