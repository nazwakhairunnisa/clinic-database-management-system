<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class PembelianObat extends BaseModel
{
    use HasFactory;

    protected $table = 'pembelian_obat';
    protected $primaryKey = 'id_pembelian_obat';
    public $timestamps = true;

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
        'tanggal_beli' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'jumlah' => 'integer',
        'harga_satuan' => 'decimal:2'
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

    // Accessor untuk total harga
    public function getTotalHargaAttribute()
    {
        return $this->jumlah * $this->harga_satuan;
    }

    // Relasi ke Transaksi Keuangan
    public function transaksiKeuangan()
    {
        return $this->hasOne(TransaksiKeuangan::class, 'id_pembelian_obat', 'id_pembelian_obat');
    }

    // Accessor untuk status badge color
    public function getStatusColorAttribute()
    {
        return match($this->status_pembayaran) {
            'lunas' => 'green',
            'belum' => 'red',
            default => 'gray'
        };
    }

    public function getStatusTextAttribute()
    {
        return match($this->status_pembayaran) {
            'lunas' => 'Sudah Dibayar',
            'belum' => 'Belum Dibayar',
            default => '-'
        };
    }
}