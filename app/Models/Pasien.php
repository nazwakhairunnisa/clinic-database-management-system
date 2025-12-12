<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\BaseModel;

class Pasien extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'pasien';
    protected $primaryKey = 'id_pasien';
    public $timestamps = true;

    protected $fillable = [
        'id_user',
        'nama_depan',
        'nama_belakang',
        'tanggal_lahir',
        'jenis_kelamin',
        'no_telepon',
        'alamat',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user', 'id_user');
    }

    public function reservasi()
    {
        return $this->hasMany(Reservasi::class, 'id_pasien', 'id_pasien');
    }

    public function rekamMedis()
    {
        return $this->hasMany(RekamMedis::class, 'id_pasien', 'id_pasien');
    }

    public function resumePasien()
    {
        return $this->hasMany(ResumePasien::class, 'id_pasien', 'id_pasien');
    }

    // Accessors
    public function getNamaLengkapAttribute()
    {
        return $this->nama_depan . ' ' . $this->nama_belakang;
    }

    public function getUsiaAttribute()
    {
        return $this->tanggal_lahir->age;
    }
}