<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\BaseModel;

class Treatment extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'treatment';
    protected $primaryKey = 'id_treatment';

    protected $fillable = [
        'nama_treatment',
        'deskripsi',
        'harga',
        'foto_treatment',
        'durasi',
    ];

    protected $casts = [
        'harga' => 'decimal:2',
        'durasi' => 'integer',
    ];

    // Relasi ke promo
    public function promos()
    {
        return $this->hasMany(Promo::class, 'id_treatment', 'id_treatment');
    }

    // Relasi ke detail reservasi
    public function detailReservasi()
    {
        return $this->hasMany(DetailReservasi::class, 'id_treatment', 'id_treatment');
    }

    // Accessor untuk format harga
    public function getFormattedHargaAttribute()
    {
        return 'Rp ' . number_format($this->harga, 0, ',', '.');
    }

    // Accessor untuk URL foto
    public function getFotoUrlAttribute()
    {
        return $this->foto_treatment 
            ? asset('storage/' . $this->foto_treatment) 
            : asset('images/default-treatment.jpg');
    }

    // Accessor untuk durasi format
    public function getDurasiFormatAttribute()
    {
        $hours = floor($this->durasi / 60);
        $minutes = $this->durasi % 60;
        
        if ($hours > 0 && $minutes > 0) {
            return $hours . ' jam ' . $minutes . ' menit';
        } elseif ($hours > 0) {
            return $hours . ' jam';
        } else {
            return $minutes . ' menit';
        }
    }

    // Scope untuk treatment aktif (tidak dihapus)
    public function scopeActive($query)
    {
        return $query->whereNull('deleted_at');
    }

    /**
     * Method untuk update treatment menggunakan stored procedure
     * Ini akan memanggil SP UpdateTreatment yang sudah ada di database
     */
    public static function updateWithProcedure($id, $data)
    {
        try {
            $idUser = Auth::id();
            
            DB::statement('CALL UpdateTreatment(?, ?, ?, ?, ?, ?, ?)', [
                $id,
                $idUser,
                $data['nama_treatment'],
                $data['deskripsi'] ?? null,
                $data['harga'],
                $data['durasi'],
                $data['foto_treatment'] ?? null
            ]);
            
            return true;
        } catch (\Exception $e) {
            // Log error untuk debugging
            \Log::error('Error calling UpdateTreatment SP: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Cek apakah treatment memiliki promo aktif
     */
    public function hasActivePromo()
    {
        return $this->promos()
            ->where('periode_mulai', '<=', now())
            ->where('periode_selesai', '>=', now())
            ->whereNull('deleted_at')
            ->exists();
    }

    /**
     * Get harga treatment pada tanggal tertentu (dengan promo jika ada)
     * Menggunakan function HargaAkhir dari database
     */
    public static function getHargaAkhir($idTreatment, $tanggal)
    {
        $result = DB::select('SELECT HargaAkhir(?, ?) as harga_akhir', [
            $idTreatment,
            $tanggal
        ]);
        
        return $result[0]->harga_akhir ?? 0;
    }

    /**
     * Terapkan promo untuk treatment
     * Menggunakan stored procedure TerapkanPromo
     */
    public static function terapkanPromo($idTreatment, $tanggalReservasi)
    {
        try {
            $result = DB::select('CALL TerapkanPromo(?, ?, @harga_final, @ada_promo, @nama_promo, @hemat)', [
                $idTreatment,
                $tanggalReservasi
            ]);
            
            // Get output parameters
            $output = DB::select('SELECT @harga_final as harga_final, @ada_promo as ada_promo, 
                                         @nama_promo as nama_promo, @hemat as hemat');
            
            return [
                'harga_final' => $output[0]->harga_final,
                'ada_promo' => $output[0]->ada_promo,
                'nama_promo' => $output[0]->nama_promo,
                'hemat' => $output[0]->hemat
            ];
        } catch (\Exception $e) {
            \Log::error('Error calling TerapkanPromo SP: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Get treatment dengan informasi promo dari view
     */
    public static function getTreatmentWithPromo()
    {
        return DB::table('v_treatment_promo')
            ->orderBy('nama_treatment')
            ->get();
    }

    /**
     * Get single treatment dengan informasi promo
     */
    public static function getTreatmentPromoById($id)
    {
        return DB::table('v_treatment_promo')
            ->where('id_treatment', $id)
            ->first();
    }
}
