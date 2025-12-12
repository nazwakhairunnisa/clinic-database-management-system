<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class Pembayaran extends BaseModel
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';
    
    protected $fillable = [
        'id_reservasi',
        'id_user',
        'tanggal_pembayaran',
        'total_pembayaran',
        'metode_pembayaran',
        'status_pembayaran',
        'bukti_pembayaran',
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'date',
        'total_pembayaran' => 'decimal:2',
    ];

    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi', 'id_reservasi');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function transaksiKeuangan()
    {
        return $this->hasOne(TransaksiKeuangan::class, 'id_pembayaran', 'id_pembayaran');
    }
}