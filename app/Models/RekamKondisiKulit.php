<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;

class RekamKondisiKulit extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'rekam_kondisi_kulit';
    protected $primaryKey = 'id_kondisi';
    public $timestamps = true;

    protected $fillable = [
        'id_rekam_medis',
        'jenis_kondisi',
        'status_kondisi',
        'area',
        'derajat',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function rekamMedis()
    {
        return $this->belongsTo(RekamMedis::class, 'id_rekam_medis', 'id_rekam_medis');
    }
}
