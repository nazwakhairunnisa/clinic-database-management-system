<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Treatment extends Model
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
}
