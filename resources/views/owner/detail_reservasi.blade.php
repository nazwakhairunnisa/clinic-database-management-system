@extends('layouts.owner.app')

@section('pageTitle', 'Detail Reservasi')

@section('content')
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
<script src="https://code.iconify.design/iconify-icon/1.0.7/iconify-icon.min.js"></script>

<style>
/* Modal Styling */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}

.modal.show {
    display: flex;
    align-items: center;
    justify-content: center;
}

.modal-content {
    background-color: white;
    padding: 2rem;
    border-radius: 1rem;
    max-width: 500px;
    width: 90%;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}
</style>

<div class="font-['Roboto',sans-serif] bg-[#F8F6F1] min-h-screen flex justify-center items-start pt-10 sm:pt-14">
    <div class="bg-white rounded-2xl shadow-lg w-full max-w-5xl p-6 sm:p-8">

        {{-- HEADER --}}
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Reservation Details</h2>
            <div class="flex gap-2">
                {{-- Close Button --}}
                <a href="{{ route('owner.jadwal_reservasi.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                    <iconify-icon icon="mdi:close" class="text-xl"></iconify-icon>
                </a>
            </div>
        </div>

        {{-- STATUS BADGE BESAR --}}
        <div class="mb-6 flex items-center gap-4">
            <div class="flex-1">
                <div class="flex items-center gap-3">
                    <span class="px-4 py-2 text-sm font-semibold rounded-full {{ $reservasi->status_badge['bg'] }} {{ $reservasi->status_badge['text-color'] }} flex items-center gap-2">
                        <iconify-icon icon="{{ $reservasi->status_badge['icon'] }}" class="text-lg"></iconify-icon>
                        {{ $reservasi->status_badge['text'] }}
                    </span>
                    <span class="text-xs text-gray-500">{{ $reservasi->status_description }}</span>
                </div>
            </div>
            <div class="text-right">
                <div class="text-xs text-gray-500 mb-1">ID Reservasi</div>
                <div class="text-lg font-bold text-gray-800">R-{{ str_pad($reservasi->id_reservasi, 4, '0', STR_PAD_LEFT) }}</div>
            </div>
        </div>

        {{-- SECTION: INFORMASI RESERVASI --}}
        <div class="border border-gray-200 rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-100 px-4 py-2 border-b border-gray-200">
                <h4 class="text-sm font-medium text-gray-600">Informasi Reservasi</h4>
            </div>

            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-6">
                {{-- KOLOM KIRI --}}
                <div class="grid grid-cols-[150px_1fr] text-sm gap-y-3">
                    <div class="text-gray-500">Tanggal:</div>
                    <div class="font-medium text-gray-800 flex items-center gap-2">
                        <iconify-icon icon="mdi:calendar" class="text-blue-600"></iconify-icon>
                        {{ $reservasi->tanggal_reservasi->format('d F Y') }}
                        <span class="text-xs text-gray-500">({{ $reservasi->tanggal_reservasi->format('l') }})</span>
                    </div>

                    <div class="text-gray-500">Jam Mulai:</div>
                    <div class="font-medium text-gray-800 flex items-center gap-2">
                        <iconify-icon icon="mdi:clock-outline" class="text-blue-600"></iconify-icon>
                        {{ $reservasi->jam_reservasi->format('H:i') }} WIB
                    </div>

                    <div class="text-gray-500">Estimasi Selesai:</div>
                    <div class="font-medium text-gray-800 flex items-center gap-2">
                        <iconify-icon icon="mdi:clock-check-outline" class="text-green-600"></iconify-icon>
                        {{ $jamSelesai->format('H:i') }} WIB
                        <span class="text-xs text-gray-500">({{ $totalDurasi }} menit)</span>
                    </div>

                    <div class="text-gray-500">Metode Reservasi:</div>
                    <div class="font-medium text-gray-800">
                        <span class="px-2 py-1 text-xs rounded-full {{ $reservasi->metode_reservasi == 'online' ? 'bg-purple-100 text-purple-700' : 'bg-gray-100 text-gray-700' }}">
                            <iconify-icon icon="{{ $reservasi->metode_reservasi == 'online' ? 'mdi:web' : 'mdi:phone' }}" class="text-sm"></iconify-icon>
                            {{ ucfirst($reservasi->metode_reservasi) }}
                        </span>
                    </div>
                </div>

                {{-- KOLOM KANAN --}}
                <div class="grid grid-cols-[150px_1fr] text-sm gap-y-3">
                    <div class="text-gray-500">Dibuat Oleh:</div>
                    <div class="font-medium text-gray-800">
                        {{ $reservasi->user->username }}
                        <span class="text-xs text-gray-500">({{ ucfirst($reservasi->user->role) }})</span>
                    </div>

                    <div class="text-gray-500">Tanggal Dibuat:</div>
                    <div class="font-medium text-gray-800">{{ $reservasi->created_at->format('d M Y, H:i') }}</div>

                    <div class="text-gray-500">Terakhir Update:</div>
                    <div class="font-medium text-gray-800">{{ $reservasi->updated_at->format('d M Y, H:i') }}</div>

                    @if($reservasi->keterangan)
                    <div class="text-gray-500">Keterangan:</div>
                    <div class="font-medium text-gray-800">{{ $reservasi->keterangan }}</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- SECTION: INFORMASI PASIEN --}}
        <div class="border border-gray-200 rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-100 px-4 py-2 border-b border-gray-200 flex justify-between items-center">
                <h4 class="text-sm font-medium text-gray-600">Informasi Pasien</h4>
                <a href="{{ route('owner.pasien.show', $reservasi->id_pasien) }}" 
                   class="text-xs text-blue-600 hover:text-blue-800 flex items-center gap-1">
                    <iconify-icon icon="mdi:eye" class="text-sm"></iconify-icon>
                    Lihat Detail
                </a>
            </div>

            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-6">
                {{-- KOLOM KIRI --}}
                <div class="grid grid-cols-[150px_1fr] text-sm gap-y-3">
                    <div class="text-gray-500">ID Pasien:</div>
                    <div class="font-medium text-gray-800">P-{{ str_pad($reservasi->pasien->id_pasien, 4, '0', STR_PAD_LEFT) }}</div>

                    <div class="text-gray-500">Nama Lengkap:</div>
                    <div class="font-medium text-gray-800">{{ $reservasi->pasien->nama_depan }} {{ $reservasi->pasien->nama_belakang }}</div>

                    <div class="text-gray-500">No Telepon:</div>
                    <div class="font-medium text-gray-800 flex items-center gap-2">
                        <iconify-icon icon="mdi:phone" class="text-green-600"></iconify-icon>
                        {{ $reservasi->pasien->no_telepon }}
                    </div>
                </div>

                {{-- KOLOM KANAN --}}
                <div class="grid grid-cols-[150px_1fr] text-sm gap-y-3">
                    <div class="text-gray-500">Tanggal Lahir:</div>
                    <div class="font-medium text-gray-800">
                        {{ \Carbon\Carbon::parse($reservasi->pasien->tanggal_lahir)->format('d M Y') }}
                        <span class="text-xs text-gray-500">({{ \Carbon\Carbon::parse($reservasi->pasien->tanggal_lahir)->age }} tahun)</span>
                    </div>

                    <div class="text-gray-500">Jenis Kelamin:</div>
                    <div class="font-medium text-gray-800">{{ $reservasi->pasien->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</div>

                    <div class="text-gray-500">Alamat:</div>
                    <div class="font-medium text-gray-800">{{ $reservasi->pasien->alamat }}</div>
                </div>
            </div>
        </div>

        {{-- SECTION: DETAIL TREATMENT --}}
        <div class="border border-gray-200 rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-100 px-4 py-2 border-b border-gray-200">
                <h4 class="text-sm font-medium text-gray-600">Detail Treatment</h4>
            </div>

            <div class="p-5">
                <div class="space-y-4">
                    @foreach($reservasi->detailReservasi as $detail)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex gap-4">
                            {{-- Foto Treatment - FIXED SIZE --}}
                            @if($detail->treatment->foto_treatment)
                            <div class="flex-shrink-0">
                                <img src="{{ asset('storage/' . $detail->treatment->foto_treatment) }}" 
                                     alt="{{ $detail->treatment->nama_treatment }}"
                                     class="w-20 h-20 sm:w-24 sm:h-24 object-cover rounded-lg border border-gray-300">
                            </div>
                            @endif

                            {{-- Detail Treatment --}}
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-2">
                                    <div>
                                        <h5 class="font-semibold text-gray-800">{{ $detail->treatment->nama_treatment }}</h5>
                                        @if($detail->treatment->deskripsi)
                                        <p class="text-xs text-gray-500 mt-1">{{ Str::limit($detail->treatment->deskripsi, 100) }}</p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <div class="text-sm font-semibold text-gray-800">Rp {{ number_format($detail->harga_saat_reservasi, 0, ',', '.') }}</div>
                                        @if($detail->harga_saat_reservasi < $detail->treatment->harga)
                                        <div class="text-xs text-gray-400 line-through">Rp {{ number_format($detail->treatment->harga, 0, ',', '.') }}</div>
                                        <span class="text-xs bg-red-100 text-red-700 px-2 py-0.5 rounded-full">
                                            Hemat Rp {{ number_format($detail->treatment->harga - $detail->harga_saat_reservasi, 0, ',', '.') }}
                                        </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 text-xs text-gray-600 mt-2">
                                    <div class="flex items-center gap-1">
                                        <iconify-icon icon="mdi:clock-outline"></iconify-icon>
                                        <span>{{ $detail->treatment->durasi }} menit</span>
                                    </div>
                                    <div class="flex items-center gap-1">
                                        <iconify-icon icon="mdi:cart-outline"></iconify-icon>
                                        <span>Qty: {{ $detail->quantity }}</span>
                                    </div>
                                    <div class="flex items-center gap-1 font-semibold text-blue-600">
                                        <iconify-icon icon="mdi:cash"></iconify-icon>
                                        <span>Subtotal: Rp {{ number_format($detail->harga_saat_reservasi * $detail->quantity, 0, ',', '.') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Total Biaya --}}
                <div class="mt-6 pt-4 border-t border-gray-200">
                    <div class="flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            @if($adaPromo)
                            <div class="flex items-center gap-2 text-green-600 mb-1">
                                <iconify-icon icon="mdi:tag" class="text-lg"></iconify-icon>
                                <span class="font-medium">Total Hemat: Rp {{ number_format($totalHemat, 0, ',', '.') }}</span>
                            </div>
                            @endif
                            <span>{{ $reservasi->detailReservasi->count() }} Treatment</span>
                        </div>
                        <div class="text-right">
                            <div class="text-xs text-gray-500 mb-1">Total Biaya</div>
                            <div class="text-2xl font-bold text-gray-800">
                                Rp {{ number_format($reservasi->total_biaya, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- SECTION: INFORMASI PEMBAYARAN --}}
        @if($reservasi->pembayaran)
        <div class="border border-gray-200 rounded-lg mb-6 overflow-hidden">
            <div class="bg-gray-100 px-4 py-2 border-b border-gray-200 flex justify-between items-center">
                <h4 class="text-sm font-medium text-gray-600">Informasi Pembayaran</h4>
                <span class="px-3 py-1 text-xs font-semibold rounded-full {{ $reservasi->pembayaran->status_pembayaran == 'lunas' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ ucfirst($reservasi->pembayaran->status_pembayaran) }}
                </span>
            </div>

            <div class="p-5 grid grid-cols-1 sm:grid-cols-2 gap-x-10 gap-y-6">
                <div class="grid grid-cols-[150px_1fr] text-sm gap-y-3">
                    <div class="text-gray-500">Total Pembayaran:</div>
                    <div class="font-bold text-lg text-gray-800">Rp {{ number_format($reservasi->pembayaran->total_pembayaran, 0, ',', '.') }}</div>

                    <div class="text-gray-500">Metode Pembayaran:</div>
                    <div class="font-medium text-gray-800">
                        <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-700">
                            {{ ucfirst($reservasi->pembayaran->metode_pembayaran) }}
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-[150px_1fr] text-sm gap-y-3">
                    <div class="text-gray-500">Tanggal Pembayaran:</div>
                    <div class="font-medium text-gray-800">
                        {{ $reservasi->pembayaran->tanggal_pembayaran ? \Carbon\Carbon::parse($reservasi->pembayaran->tanggal_pembayaran)->format('d M Y') : 'Belum dibayar' }}
                    </div>

                    @if($reservasi->pembayaran->status_pembayaran == 'lunas' && $reservasi->pembayaran->bukti_pembayaran)
                    <div class="text-gray-500">Bukti Pembayaran:</div>
                    <div class="font-medium text-gray-800">
                        <a href="{{ asset('storage/' . $reservasi->pembayaran->bukti_pembayaran) }}" 
                           target="_blank"
                           class="text-blue-600 hover:text-blue-800 flex items-center gap-1">
                            <iconify-icon icon="mdi:file-document"></iconify-icon>
                            Lihat Bukti
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif

        {{-- ACTION BUTTONS --}}
        <div class="flex gap-3 justify-end pt-4 border-t border-gray-200">
            
            {{-- Update Status Button - Dinamis sesuai flow --}}
            @if($reservasi->status == 'requested')
                <button onclick="updateStatusModal('confirmed')" 
                        class="flex items-center gap-2 bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition shadow-sm">
                    <iconify-icon icon="mdi:check-circle" class="text-lg"></iconify-icon>
                    Konfirmasi Reservasi
                </button>
            @endif

            @if($reservasi->status == 'confirmed')
                <button onclick="updateStatusModal('done')" 
                        class="flex items-center gap-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition shadow-sm">
                    <iconify-icon icon="mdi:check-all" class="text-lg"></iconify-icon>
                    Treatment Selesai
                </button>
            @endif

            @if($reservasi->status == 'waiting-payment')
                <a href="{{ route('admin.pembayaran.show', $reservasi->pembayaran->id_pembayaran) }}"
                   class="flex items-center gap-2 bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition shadow-sm">
                    <iconify-icon icon="mdi:cash-register" class="text-lg"></iconify-icon>
                    Proses Pembayaran
                </a>
            @endif

            {{-- Batalkan hanya untuk status tertentu --}}
            @if(in_array($reservasi->status, ['requested', 'confirmed']))
                <button onclick="cancelModal()" 
                        class="flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition shadow-sm">
                    <iconify-icon icon="mdi:close-circle" class="text-lg"></iconify-icon>
                    Batalkan
                </button>
            @endif

            <a href="{{ route('owner.jadwal_reservasi.index') }}" 
               class="flex items-center gap-2 bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition shadow-sm">
                <iconify-icon icon="mdi:arrow-left" class="text-lg"></iconify-icon>
                Kembali
            </a>
        </div>

    </div>
</div>

{{-- MODAL UPDATE STATUS --}}
<div id="updateStatusModal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800" id="modalTitle">Update Status</h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                <iconify-icon icon="mdi:close" class="text-xl"></iconify-icon>
            </button>
        </div>
        
        <div class="mb-4">
            <p class="text-sm text-gray-600 mb-3" id="modalMessage">Apakah Anda yakin ingin mengupdate status reservasi?</p>
            
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 mb-3">
                <div class="flex items-center gap-2 text-sm text-blue-800">
                    <iconify-icon icon="mdi:information" class="text-lg"></iconify-icon>
                    <span id="statusInfo"></span>
                </div>
            </div>

            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan (opsional)</label>
            <textarea id="keterangan" 
                      rows="3" 
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      placeholder="Tambahkan catatan jika diperlukan..."></textarea>
        </div>

        <div class="flex gap-3 justify-end">
            <button onclick="closeModal()" 
                    class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Batal
            </button>
            <button onclick="confirmUpdateStatus()" 
                    id="confirmBtn"
                    class="px-4 py-2 text-sm bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                Ya, Update
            </button>
        </div>
    </div>
</div>

{{-- MODAL CANCEL --}}
<div id="cancelModal" class="modal">
    <div class="modal-content">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-800">Batalkan Reservasi</h3>
            <button onclick="closeCancelModal()" class="text-gray-400 hover:text-gray-600">
                <iconify-icon icon="mdi:close" class="text-xl"></iconify-icon>
            </button>
        </div>
        
        <div class="mb-4">
            <p class="text-sm text-gray-600 mb-3">Silakan masukkan alasan pembatalan:</p>
            
            <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Pembatalan <span class="text-red-500">*</span></label>
            <textarea id="alasanBatal" 
                      rows="3" 
                      class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-red-500 focus:border-transparent"
                      placeholder="Contoh: Pasien berhalangan hadir, Perubahan jadwal, dll"
                      required></textarea>
        </div>

        <div class="flex gap-3 justify-end">
            <button onclick="closeCancelModal()" 
                    class="px-4 py-2 text-sm bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition">
                Batal
            </button>
            <button onclick="confirmCancel()" 
                    class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 transition">
                Ya, Batalkan
            </button>
        </div>
    </div>
</div>

<script>
let targetStatus = '';

function updateStatusModal(status) {
    targetStatus = status;
    const modal = document.getElementById('updateStatusModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalMessage = document.getElementById('modalMessage');
    const statusInfo = document.getElementById('statusInfo');
    const confirmBtn = document.getElementById('confirmBtn');
    
    // Customize modal berdasarkan status
    if (status === 'confirmed') {
        modalTitle.textContent = 'Konfirmasi Reservasi';
        modalMessage.textContent = 'Jadwal akan dikonfirmasi dan pasien akan diberi notifikasi.';
        statusInfo.textContent = 'Status akan berubah dari "Requested" → "Confirmed"';
        confirmBtn.classList.remove('bg-blue-600', 'hover:bg-blue-700');
        confirmBtn.classList.add('bg-green-600', 'hover:bg-green-700');
    } else if (status === 'done') {
        modalTitle.textContent = 'Treatment Selesai';
        modalMessage.textContent = 'Treatment telah selesai dilakukan. Status akan otomatis berubah ke "Waiting Payment".';
        statusInfo.textContent = 'Status akan berubah dari "Confirmed" → "Done" → "Waiting Payment" (otomatis)';
        confirmBtn.classList.remove('bg-green-600', 'hover:bg-green-700');
        confirmBtn.classList.add('bg-blue-600', 'hover:bg-blue-700');
    }
    
    modal.classList.add('show');
}

function cancelModal() {
    document.getElementById('cancelModal').classList.add('show');
}

function closeModal() {
    document.getElementById('updateStatusModal').classList.remove('show');
    document.getElementById('keterangan').value = '';
}

function closeCancelModal() {
    document.getElementById('cancelModal').classList.remove('show');
    document.getElementById('alasanBatal').value = '';
}

function confirmUpdateStatus() {
    const keterangan = document.getElementById('keterangan').value;
    updateStatus({{ $reservasi->id_reservasi }}, targetStatus, keterangan);
}

function confirmCancel() {
    const alasan = document.getElementById('alasanBatal').value.trim();
    
    if (!alasan) {
        alert('Alasan pembatalan harus diisi!');
        return;
    }
    
    updateStatus({{ $reservasi->id_reservasi }}, 'cancelled', alasan);
}

function updateStatus(id, status, keterangan) {
    fetch(`/owner/jadwal-reservasi/${id}/status`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({
            status: status,
            keterangan: keterangan
        })
    })
    .then(r => {
        if (!r.ok) throw new Error('HTTP ' + r.status);
        return r.json();
    })
    .then(res => {
        alert(res.message);
        location.reload();
    })
    .catch(err => {
        alert('Gagal: ' + err.message);
        console.error(err);
    });
}

// Close modal when clicking outside
window.onclick = function(event) {
    const updateModal = document.getElementById('updateStatusModal');
    const cancelModal = document.getElementById('cancelModal');
    
    if (event.target == updateModal) {
        closeModal();
    }
    if (event.target == cancelModal) {
        closeCancelModal();
    }
}
</script>

@endsection