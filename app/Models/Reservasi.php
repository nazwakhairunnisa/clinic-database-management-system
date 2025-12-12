<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Reservasi extends BaseModel
{
    use HasFactory;

    protected $table = 'reservasi';
    protected $primaryKey = 'id_reservasi';
    public $timestamps = true;

    protected $fillable = [
        'id_pasien',
        'id_user',
        'id_jadwal',
        'tanggal_reservasi',
        'jam_reservasi',
        'status',
        'metode_reservasi',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_reservasi' => 'date',
        'jam_reservasi' => 'datetime:H:i',
    ];

    // Relationships
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function jadwalOperasional()
    {
        return $this->belongsTo(JadwalOperasional::class, 'id_jadwal', 'id_jadwal');
    }

    public function detailReservasi()
    {
        return $this->hasMany(DetailReservasi::class, 'id_reservasi', 'id_reservasi');
    }

    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'id_reservasi', 'id_reservasi');
    }

    // Scopes
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeMetode($query, $metode)
    {
        return $query->where('metode_reservasi', $metode);
    }

    public function scopeDateRange($query, $from, $to)
    {
        return $query->whereBetween('tanggal_reservasi', [$from, $to]);
    }

    // ========================================
    // Acessor: Status Badge 
    // ========================================
    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'requested' => [
                'text' => 'Requested', 
                'color' => 'yellow', 
                'bg' => 'bg-yellow-100', 
                'text-color' => 'text-yellow-700',
                'icon' => 'mdi:clock-alert'
            ],
            'confirmed' => [
                'text' => 'Confirmed', 
                'color' => 'blue', 
                'bg' => 'bg-blue-100', 
                'text-color' => 'text-blue-700',
                'icon' => 'mdi:check-circle'
            ],
            'done' => [
                'text' => 'Done', 
                'color' => 'purple', 
                'bg' => 'bg-purple-100', 
                'text-color' => 'text-purple-700',
                'icon' => 'mdi:check-all'
            ],
            'waiting-payment' => [
                'text' => 'Waiting Payment', 
                'color' => 'orange', 
                'bg' => 'bg-orange-100', 
                'text-color' => 'text-orange-700',
                'icon' => 'mdi:cash-clock'
            ],
            'completed' => [
                'text' => 'Completed', 
                'color' => 'green', 
                'bg' => 'bg-green-100', 
                'text-color' => 'text-green-700',
                'icon' => 'mdi:check-circle-outline'
            ],
            'cancelled' => [
                'text' => 'Cancelled', 
                'color' => 'red', 
                'bg' => 'bg-red-100', 
                'text-color' => 'text-red-700',
                'icon' => 'mdi:close-circle'
            ],
            default => [
                'text' => 'Unknown', 
                'color' => 'gray', 
                'bg' => 'bg-gray-100', 
                'text-color' => 'text-gray-700',
                'icon' => 'mdi:help-circle'
            ]
        };
    }

    // ========================================
    // Accessor untuk list treatment
    // ========================================
    public function getTreatmentsListAttribute()
    {
        return $this->detailReservasi->map(function($detail) {
            return $detail->treatment->nama_treatment;
        })->join(', ');
    }

    // ========================================
    // Accessor untuk total biaya
    // ========================================
    public function getTotalBiayaAttribute()
    {
        return $this->detailReservasi->sum(function($detail) {
            return $detail->harga_saat_reservasi * $detail->quantity;
        });
    }

    // ========================================
    // Helper method: Cek apakah bisa diubah statusnya
    // ========================================
    public function canUpdateStatus($newStatus)
    {
        // Status yang tidak bisa diubah
        if (in_array($this->status, ['completed', 'cancelled'])) {
            return false;
        }

        // Validasi flow status
        $validTransitions = [
            'requested' => ['confirmed', 'cancelled'],
            'confirmed' => ['done', 'cancelled'],
            'done' => ['waiting-payment'], // Auto by trigger
            'waiting-payment' => ['completed', 'cancelled'],
        ];

        return in_array($newStatus, $validTransitions[$this->status] ?? []);
    }

    // ========================================
    // Helper method: Get status description
    // ========================================
    public function getStatusDescriptionAttribute()
    {
        return match($this->status) {
            'requested' => 'Menunggu konfirmasi dari admin',
            'confirmed' => 'Jadwal telah dikonfirmasi, siap treatment',
            'done' => 'Treatment selesai, menunggu pembayaran',
            'waiting-payment' => 'Di kasir, sedang diproses pembayaran',
            'completed' => 'Transaksi selesai, pembayaran lunas',
            'cancelled' => 'Reservasi dibatalkan',
            default => 'Status tidak diketahui'
        };
    }

    // ========================================
    // Scope: Reservasi yang perlu perhatian
    // ========================================
    public function scopeNeedsAttention($query)
    {
        return $query->whereIn('status', ['requested', 'waiting-payment'])
                    ->orderBy('tanggal_reservasi', 'asc');
    }

    // ========================================
    // Scope: Reservasi hari ini
    // ========================================
    public function scopeToday($query)
    {
        return $query->whereDate('tanggal_reservasi', today());
    }

    // ========================================
    // Scope: Reservasi yang aktif (belum selesai)
    // ========================================
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['completed', 'cancelled']);
    }
}