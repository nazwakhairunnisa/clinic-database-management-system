<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;
use Carbon\Carbon;

class JadwalOperasional extends BaseModel
{
    use HasFactory;

    protected $table = 'jadwal_operasional';
    protected $primaryKey = 'id_jadwal';
    public $timestamps = true;

    // Perbaiki fillable → kolomnya status_operasional, bukan status
    protected $fillable = [
        'hari_tanggal',
        'jam_mulai',
        'jam_selesai',
        'status_operasional',   // <-- ini yang benar
        'keterangan',
    ];

    protected $casts = [
        'hari_tanggal' => 'date',
        'jam_mulai'    => 'datetime:H:i',
        'jam_selesai'  => 'datetime:H:i',
        'status_operasional' => 'string',
    ];

    // Relationships
    public function reservasi()
    {
        return $this->hasMany(Reservasi::class, 'id_jadwal', 'id_jadwal');
    }

    // =================================================================
    // SCOPES – SEMUA DIPERBAIKI SESUAI KOLOM & VALUE YANG BENAR
    // =================================================================

    /**
     * Jadwal yang buka (bukan tutup)
     */
    public function scopeBuka($query)
    {
        return $query->where('status_operasional', 'buka');
    }

    /**
     * Jadwal yang tutup
     */
    public function scopeTutup($query)
    {
        return $query->where('status_operasional', 'tutup');
    }

    /**
     * Jadwal yang available untuk booking baru
     * = buka + belum ada reservasi yang aktif/belum selesai di jam tersebut
     */
    public function scopeAvailable($query)
    {
        return $query->buka()
                     ->whereDoesntHave('reservasi', function ($q) {
                         $q->whereIn('status', ['requested', 'confirmed', 'done', 'waiting-payment']);
                     });
    }

    /**
     * Jadwal yang sudah ada reservasi aktif (booked)
     */
    public function scopeBooked($query)
    {
        return $query->whereHas('reservasi', function ($q) {
            $q->whereIn('status', ['requested', 'confirmed', 'done', 'waiting-payment']);
        });
    }

    /**
     * Jadwal mulai dari hari ini ke depan
     */
    public function scopeUpcoming($query)
    {
        return $query->where('hari_tanggal', '>=', now()->toDateString());
    }

    /**
     * Jadwal hari ini saja
     */
    public function scopeToday($query)
    {
        return $query->where('hari_tanggal', now()->toDateString());
    }

    /**
     * Jadwal dalam range tanggal (optional helper)
     */
    public function scopeBetweenDates($query, $start, $end = null)
    {
        $end = $end ?? $start;
        return $query->whereBetween('hari_tanggal', [$start, $end]);
    }
}