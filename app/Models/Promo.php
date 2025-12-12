<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;

class Promo extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'promo';
    protected $primaryKey = 'promo_id';

    protected $fillable = [
        'id_treatment',
        'nama_promo',
        'gambar_promo',
        'periode_mulai',
        'periode_selesai',
        'harga_promo',
    ];

    protected $casts = [
        'harga_promo' => 'decimal:2',
        'periode_mulai' => 'date',
        'periode_selesai' => 'date',
    ];

    // Relasi ke treatment
    public function treatment()
    {
        return $this->belongsTo(Treatment::class, 'id_treatment', 'id_treatment');
    }

    // Accessor untuk format harga
    public function getFormattedHargaPromoAttribute()
    {
        return 'Rp ' . number_format($this->harga_promo, 0, ',', '.');
    }

    // Accessor untuk URL gambar
    public function getGambarUrlAttribute()
    {
        return $this->gambar_promo 
            ? asset('storage/' . $this->gambar_promo) 
            : asset('images/default-promo.jpg');
    }

    // Scope untuk promo aktif
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at')
            ->where('periode_selesai', '>=', now());
    }

    // Scope untuk promo yang sedang berjalan
    public function scopeBerjalan($query)
    {
        return $query->whereNull('deleted_at')
            ->where('periode_mulai', '<=', now())
            ->where('periode_selesai', '>=', now());
    }

    // Check apakah promo sedang aktif
    public function isActive()
    {
        return now()->between($this->periode_mulai, $this->periode_selesai);
    }

    // Hitung sisa hari promo
    public function getSisaHariAttribute()
    {
        if (!$this->isActive()) {
            return 0;
        }
        // Hitung sisa hari (dibulatkan ke atas agar tidak 0 di hari terakhir)
        $now = now()->startOfDay();
        $end = $this->periode_selesai->startOfDay();
        
        $sisaHari = $now->diffInDays($end);
        
        // Tambah 1 jika masih di hari yang sama agar tidak 0
        if ($sisaHari == 0 && now()->lte($this->periode_selesai)) {
            return 1;
        }
        
        return (int) $sisaHari;
    }

    // Hitung hemat dari harga normal
    public function getHematAttribute()
    {
        if (!$this->treatment) {
            return 0;
        }
        return $this->treatment->harga - $this->harga_promo;
    }

    // Hitung persentase diskon
    public function getPersenDiskonAttribute()
    {
        if (!$this->treatment || $this->treatment->harga == 0) {
            return 0;
        }
        return round((($this->treatment->harga - $this->harga_promo) / $this->treatment->harga) * 100);
    }
}