<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class StokObat extends BaseModel
{
    use HasFactory;

    protected $table = 'stok_obat';
    protected $primaryKey = 'id_obat';
    public $timestamps = true;

    protected $fillable = [
        'nama_obat',
        'deskripsi',
        'satuan',
        'stok_awal',
        'stok_terkini',
        'tanggal_update'
    ];

    protected $casts = [
        'tanggal_update' => 'date',
        'stok_awal' => 'integer',
        'stok_terkini' => 'integer'
    ];

    // Relationships
    public function pembelian()
    {
        return $this->hasMany(PembelianObat::class, 'id_obat', 'id_obat');
    }

    public function pembelianTerakhir()
    {
        return $this->hasOne(PembelianObat::class, 'id_obat', 'id_obat')
                    ->latestOfMany('tanggal_beli');
    }

    // Accessors
    public function getStatusStokAttribute()
    {
        if ($this->stok_terkini == 0) {
            return 'Habis';
        } elseif ($this->stok_terkini <= 10) {
            return 'Menipis';
        } else {
            return 'Aman';
        }
    }

    public function getStatusColorAttribute()
    {
        if ($this->stok_terkini == 0) {
            return 'red';
        } elseif ($this->stok_terkini <= 10) {
            return 'red';
        } elseif ($this->stok_terkini <= 20) {
            return 'yellow';
        } else {
            return 'green';
        }
    }
}