<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Services\DatabaseConnectionService;

class BaseModel extends Model
{
    /**
     * Constructor untuk set connection berdasarkan role
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        
        // Set connection based on authenticated user's role
        $this->setConnection(DatabaseConnectionService::getConnection());
    }
}
