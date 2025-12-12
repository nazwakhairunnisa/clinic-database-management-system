<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\BaseModel;

class DetailReservasi extends BaseModel
{
    use HasFactory;

    protected $table = 'detail_reservasi';
    protected $primaryKey = 'id_detail';
    public $timestamps = true;

    protected $fillable = [
        'id_reservasi',
        'id_treatment',
        'harga_saat_reservasi',
        'quantity',
    ];

    protected $casts = [
        'harga_saat_reservasi' => 'decimal:2',
        'quantity' => 'integer',
    ];

    // Relationships
    public function reservasi()
    {
        return $this->belongsTo(Reservasi::class, 'id_reservasi', 'id_reservasi');
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class, 'id_treatment', 'id_treatment');
    }

    // Accessor
    public function getSubtotalAttribute()
    {
        return $this->harga_saat_reservasi * $this->quantity;
    }
}