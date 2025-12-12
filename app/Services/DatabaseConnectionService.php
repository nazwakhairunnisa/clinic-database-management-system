<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class DatabaseConnectionService
{
    /**
     * Set database connection berdasarkan role user yang login
     */
    public static function setConnectionByRole()
    {
        $user = Auth::user();
        
        if (!$user) {
            config(['database.default' => 'mysql']);
            return 'mysql';
        }

        $connectionMap = [
            'super admin' => 'mysql_super_admin',
            'dokter' => 'mysql_dokter',
            'admin' => 'mysql_admin',
            'user' => 'mysql_user',
        ];

        $connection = $connectionMap[$user->role] ?? 'mysql';
        
        // Set default connection untuk request ini
        config(['database.default' => $connection]);
        
        return $connection;
    }
    
    /**
     * Get connection name berdasarkan role
     */
    public static function getConnection()
    {
        $user = Auth::user();
        
        if (!$user) {
            return 'mysql';
        }

        $connectionMap = [
            'super admin' => 'mysql_super_admin',
            'dokter' => 'mysql_dokter',
            'admin' => 'mysql_admin',
            'user' => 'mysql_user',
        ];

        return $connectionMap[$user->role] ?? 'mysql';
    }
}