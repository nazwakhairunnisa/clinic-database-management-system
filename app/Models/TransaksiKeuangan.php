<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class TransaksiKeuangan extends BaseModel
{
    use HasFactory;

    protected $table = 'transaksi_keuangan';
    protected $primaryKey = 'id_transaksi';
    
    protected $fillable = [
        'id_user',
        'id_pembayaran',
        'id_pembelian_obat',
        'nama_transaksi',
        'tanggal_transaksi',
        'jenis_transaksi',
        'metode_pembayaran',
        'jumlah',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_transaksi' => 'date',
        'jumlah' => 'decimal:2',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    // Relasi ke Pembayaran
    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class, 'id_pembayaran', 'id_pembayaran');
    }

    // Relasi ke Pembelian Obat
    public function pembelianObat()
    {
        return $this->belongsTo(PembelianObat::class, 'id_pembelian_obat', 'id_pembelian_obat');
    }

    // Scope untuk filter pemasukan
    public function scopePemasukan($query)
    {
        return $query->where('jenis_transaksi', 'pemasukan');
    }

    // Scope untuk filter pengeluaran
    public function scopePengeluaran($query)
    {
        return $query->where('jenis_transaksi', 'pengeluaran');
    }

    // Scope untuk filter berdasarkan periode
    public function scopePeriode($query, $bulan, $tahun)
    {
        return $query->whereMonth('tanggal_transaksi', $bulan)
                     ->whereYear('tanggal_transaksi', $tahun);
    }
}