<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;

class ResumePasien extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'resume_pasien';
    protected $primaryKey = 'id_resume';
    public $timestamps = true;

    protected $fillable = [
        'id_pasien',
        'tanggal_kunjungan',
        'anamnesa',
        'riwayat_eksfo',
        'terapi',
        'foto_sebelum_treatment',
        'foto_sesudah_treatment',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function pasien()
    {
        return $this->belongsTo(Pasien::class, 'id_pasien', 'id_pasien');
    }

    // Accessor untuk foto URL
    public function getFotoSebelumUrlAttribute()
    {
        return $this->foto_sebelum_treatment 
            ? asset('storage/' . $this->foto_sebelum_treatment) 
            : null;
    }

    public function getFotoSesudahUrlAttribute()
    {
        return $this->foto_sesudah_treatment 
            ? asset('storage/' . $this->foto_sesudah_treatment) 
            : null;
    }
}