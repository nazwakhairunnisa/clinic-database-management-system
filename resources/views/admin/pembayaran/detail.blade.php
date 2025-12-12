@extends('layouts.admin')

@section('pageTitle', 'Detail Pembayaran')

@section('content')

<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<div class="max-w-5xl mx-auto font-['Roboto',sans-serif] py-6">

    {{-- BACK BUTTON --}}
    <a href="{{ route('admin.pembayaran.riwayat') }}" 
        class="inline-flex items-center text-blue-600 hover:text-blue-800 mb-4">
        <iconify-icon icon="mdi:arrow-left" class="text-xl mr-1"></iconify-icon>
        Kembali ke Riwayat
    </a>

    {{-- MAIN CARD --}}
    <div class="bg-white rounded-2xl shadow-lg overflow-hidden">
        
        {{-- HEADER --}}
        <div class="bg-gradient-to-r from-green-600 to-green-700 text-white px-6 py-4">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold">Detail Pembayaran</h1>
                    <p class="text-sm opacity-90 mt-1">
                        ID Pembayaran: #{{ str_pad($pembayaran->id_pembayaran, 4, '0', STR_PAD_LEFT) }}
                    </p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('admin.pembayaran.struk', $pembayaran->id_pembayaran) }}" 
                        target="_blank"
                        class="bg-white/20 hover:bg-white/30 px-4 py-2 rounded-lg text-sm transition">
                        <iconify-icon icon="mdi:printer" class="text-xl mr-1"></iconify-icon>
                        Print Struk
                    </a>
                </div>
            </div>
        </div>

        {{-- BODY --}}
        <div class="p-6">
            
            {{-- STATUS BADGE --}}
            <div class="flex items-center gap-3 mb-6 pb-6 border-b border-gray-200">
                <div class="flex-1">
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                        {{ $pembayaran->status_pembayaran == 'lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                        <iconify-icon icon="mdi:check-circle" class="text-xl mr-2"></iconify-icon>
                        {{ $pembayaran->status_pembayaran == 'lunas' ? 'LUNAS' : 'BELUM LUNAS' }}
                    </span>
                    <p class="text-xs text-gray-500 mt-2">
                        Dibayar pada: {{ $pembayaran->tanggal_pembayaran->format('d F Y, H:i') }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Total Pembayaran</p>
                    <h2 class="text-3xl font-bold text-green-600">
                        Rp {{ number_format($pembayaran->total_pembayaran, 0, ',', '.') }}
                    </h2>
                </div>
            </div>

            {{-- 2 KOLOM: INFO PASIEN & INFO PEMBAYARAN --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                
                {{-- KOLOM KIRI: INFO PASIEN --}}
                <div class="bg-gray-50 rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <iconify-icon icon="mdi:account" class="text-xl mr-2 text-blue-600"></iconify-icon>
                        Informasi Pasien
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">ID Pasien</span>
                            <span class="font-medium">P-{{ str_pad($pembayaran->reservasi->pasien->id_pasien, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Nama</span>
                            <span class="font-medium">
                                {{ $pembayaran->reservasi->pasien->nama_depan }} 
                                {{ $pembayaran->reservasi->pasien->nama_belakang }}
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">No Telepon</span>
                            <span class="font-medium">{{ $pembayaran->reservasi->pasien->no_telepon }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Jenis Kelamin</span>
                            <span class="font-medium">{{ $pembayaran->reservasi->pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                        </div>
                    </div>
                </div>

                {{-- KOLOM KANAN: INFO PEMBAYARAN --}}
                <div class="bg-gray-50 rounded-xl p-5">
                    <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                        <iconify-icon icon="mdi:cash-register" class="text-xl mr-2 text-green-600"></iconify-icon>
                        Informasi Pembayaran
                    </h3>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-500">ID Reservasi</span>
                            <span class="font-medium">R-{{ str_pad($pembayaran->reservasi->id_reservasi, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Tanggal Treatment</span>
                            <span class="font-medium">{{ $pembayaran->reservasi->tanggal_reservasi->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Metode Pembayaran</span>
                            <span class="font-medium">
                                <span class="px-2 py-1 rounded-full text-xs
                                    {{ $pembayaran->metode_pembayaran == 'cash' ? 'bg-green-100 text-green-700' : '' }}
                                    {{ $pembayaran->metode_pembayaran == 'transfer' ? 'bg-blue-100 text-blue-700' : '' }}
                                    {{ $pembayaran->metode_pembayaran == 'ewallet' ? 'bg-purple-100 text-purple-700' : '' }}">
                                    {{ ucfirst($pembayaran->metode_pembayaran) }}
                                </span>
                            </span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Diproses oleh</span>
                            <span class="font-medium">{{ $pembayaran->user->username ?? '-' }}</span>
                        </div>
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
                                <th class="px-4 py-3 text-left font-medium">Treatment</th>
                                <th class="px-4 py-3 text-center font-medium">Qty</th>
                                <th class="px-4 py-3 text-right font-medium">Harga</th>
                                <th class="px-4 py-3 text-right font-medium">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($pembayaran->reservasi->detailReservasi as $detail)
                            <tr>
                                <td class="px-4 py-3">
                                    <div>
                                        <p class="font-medium">{{ $detail->treatment->nama_treatment }}</p>
                                        @if($detail->treatment->deskripsi)
                                        <p class="text-xs text-gray-500 mt-1">{{ Str::limit($detail->treatment->deskripsi, 50) }}</p>
                                        @endif
                                    </div>
                                </td>
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
                                <td colspan="3" class="px-4 py-3 text-right font-bold text-gray-700">TOTAL PEMBAYARAN</td>
                                <td class="px-4 py-3 text-right font-bold text-green-600 text-lg">
                                    Rp {{ number_format($pembayaran->total_pembayaran, 0, ',', '.') }}
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>

            {{-- BUKTI PEMBAYARAN --}}
            @if($pembayaran->bukti_pembayaran)
            <div class="mb-6">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                    <iconify-icon icon="mdi:image" class="text-lg mr-2"></iconify-icon>
                    Bukti Pembayaran
                </h3>
                <div class="border border-gray-200 rounded-lg p-4">
                    <img src="{{ asset('storage/' . $pembayaran->bukti_pembayaran) }}" 
                         alt="Bukti Pembayaran"
                         class="max-w-md rounded-lg shadow-md cursor-pointer hover:opacity-80 transition"
                         onclick="window.open(this.src, '_blank')">
                    <p class="text-xs text-gray-500 mt-2">Klik gambar untuk memperbesar</p>
                </div>
            </div>
            @endif

            {{-- TRANSAKSI KEUANGAN --}}
            @if($pembayaran->transaksiKeuangan)
            <div class="bg-blue-50 border border-blue-200 rounded-xl p-5">
                <h3 class="text-sm font-semibold text-gray-700 mb-3 flex items-center">
                    <iconify-icon icon="mdi:book-open-variant" class="text-lg mr-2 text-blue-600"></iconify-icon>
                    Catatan Transaksi Keuangan
                </h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">ID Transaksi</span>
                        <span class="font-medium">T-{{ str_pad($pembayaran->transaksiKeuangan->id_transaksi, 4, '0', STR_PAD_LEFT) }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Jenis Transaksi</span>
                        <span class="font-medium text-green-600">Pemasukan</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Tanggal Transaksi</span>
                        <span class="font-medium">{{ $pembayaran->transaksiKeuangan->tanggal_transaksi->format('d M Y') }}</span>
                    </div>
                    @if($pembayaran->transaksiKeuangan->keterangan)
                    <div class="pt-2 border-t border-blue-200">
                        <p class="text-gray-600 text-xs mb-1">Keterangan:</p>
                        <p class="text-gray-700">{{ $pembayaran->transaksiKeuangan->keterangan }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            {{-- TIMELINE --}}
            <div class="mt-6 pt-6 border-t border-gray-200">
                <h3 class="text-sm font-semibold text-gray-700 mb-4 flex items-center">
                    <iconify-icon icon="mdi:timeline-clock" class="text-lg mr-2"></iconify-icon>
                    Timeline Status
                </h3>
                <div class="space-y-3">
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 rounded-full bg-green-500 mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium">Pembayaran Lunas</p>
                            <p class="text-xs text-gray-500">{{ $pembayaran->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 rounded-full bg-blue-500 mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium">Treatment Selesai</p>
                            <p class="text-xs text-gray-500">{{ $pembayaran->reservasi->updated_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 rounded-full bg-gray-400 mt-2"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium">Reservasi Dikonfirmasi</p>
                            <p class="text-xs text-gray-500">{{ $pembayaran->reservasi->created_at->format('d M Y, H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ACTION BUTTONS --}}
            <div class="flex gap-3 mt-6 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.pembayaran.struk', $pembayaran->id_pembayaran) }}" 
                   target="_blank"
                   class="flex-1 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 font-medium text-sm text-center shadow-md hover:shadow-lg transition">
                    <iconify-icon icon="mdi:printer" class="text-xl mr-2"></iconify-icon>
                    Print Struk
                </a>
                <a href="{{ route('admin.pembayaran.riwayat') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg hover:bg-gray-50 font-medium text-sm">
                    Kembali
                </a>
            </div>

        </div>
    </div>

    {{-- INFO CARD --}}
    <div class="mt-6 bg-gradient-to-r from-blue-50 to-green-50 rounded-xl p-5 border border-blue-100">
        <div class="flex items-start gap-3">
            <iconify-icon icon="mdi:information" class="text-2xl text-blue-600 mt-1"></iconify-icon>
            <div class="flex-1 text-sm">
                <p class="font-medium text-gray-800 mb-1">Informasi</p>
                <p class="text-gray-600">
                    Pembayaran ini telah dicatat dalam sistem keuangan klinik. 
                    Untuk melihat laporan lengkap, silakan akses menu Laporan Keuangan.
                </p>
            </div>
        </div>
    </div>

</div>

@endsection