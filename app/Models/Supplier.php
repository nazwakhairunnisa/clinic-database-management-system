<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $table = 'supplier';
    protected $primaryKey = 'id_supplier';

    protected $fillable = [
        'nama_supplier',
        'nomor_supplier'
    ];

    public function pembelianObat()
    {
        return $this->hasMany(PembelianObat::class, 'id_supplier', 'id_supplier');
    }
}