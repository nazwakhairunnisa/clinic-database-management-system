<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PembelianObat extends Model
{
    use HasFactory;

    protected $table = 'pembelian_obat';
    protected $primaryKey = 'id_pembelian_obat';

    protected $fillable = [
        'id_obat',
        'id_supplier',
        'tanggal_beli',
        'jumlah',
        'harga_satuan',
        'status_pembayaran',
        'tanggal_jatuh_tempo'
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'tanggal_beli' => 'date',
        'tanggal_jatuh_tempo' => 'date'
    ];

    // Relationships
    public function obat()
    {
        return $this->belongsTo(StokObat::class, 'id_obat', 'id_obat');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'id_supplier', 'id_supplier');
    }

    public function transaksiKeuangan()
    {
        return $this->hasOne(TransaksiKeuangan::class, 'id_pembelian_obat', 'id_pembelian_obat');
    }

    // Accessor untuk total harga
    public function getTotalHargaAttribute()
    {
        return $this->jumlah * $this->harga_satuan;
    }
}