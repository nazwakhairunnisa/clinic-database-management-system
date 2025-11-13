<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model
{
    use SoftDeletes;

    protected $table = 'promo';
    protected $primaryKey = 'promo_id';
    protected $guarded = [];

    public function treatment()
    {
        return $this->belongsTo(Treatment::class, 'id_treatment', 'id_treatment');
    }
}
