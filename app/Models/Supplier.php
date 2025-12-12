<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Supplier extends BaseModel
{
    use HasFactory;

    protected $table = 'supplier';
    protected $primaryKey = 'id_supplier';
    public $timestamps = true;

    protected $fillable = [
        'nama_supplier',
        'nomor_supplier'
    ];

    // Relationships
    public function pembelianObat()
    {
        return $this->hasMany(PembelianObat::class, 'id_supplier', 'id_supplier');
    }
}