<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StokObat extends Model
{
    use HasFactory;

    protected $table = 'stok_obat';
    protected $primaryKey = 'id_obat';

    protected $fillable = [
        'nama_obat',
        'deskripsi',
        'satuan',
        'stok_awal',
        'stok_terkini',
        'tanggal_update'
    ];

    protected $casts = [
        'tanggal_update' => 'date'
    ];

    // Relationship
    public function pembelian()
    {
        return $this->hasMany(PembelianObat::class, 'id_obat', 'id_obat');
    }
}