<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;

class RekamMedis extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'rekam_medis';
    protected $primaryKey = 'id_rekam_medis';
    public $timestamps = true;
    // const CREATED_AT = 'created_at';
    // const UPDATED_AT = null;

    protected $fillable = [
        'id_pasien',
        'keluhan',
        'jenis_kulit',
        'kelembapan',
        'kondisi_pasien',
        'produk_terakhir_dipakai',
        'riwayat_penyakit',
        'riwwayat_pengobatan',
        'riwayat_alergi',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // Relationships
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }

    public function kondisiKulit()
    {
        return $this->hasMany(RekamKondisiKulit::class, 'id_rekam_medis', 'id_rekam_medis');
    }

    // Get kondisi kulit by jenis
    public function getKondisiByJenis($jenis)
    {
        return $this->kondisiKulit()
            ->where('jenis_kondisi', $jenis)
            ->first();
    }
}