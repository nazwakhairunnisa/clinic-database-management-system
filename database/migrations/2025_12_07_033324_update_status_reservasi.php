<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update enum status reservasi
        DB::statement("ALTER TABLE reservasi MODIFY COLUMN status ENUM('requested', 'confirmed', 'done', 'waiting-payment', 'completed', 'cancelled') NOT NULL DEFAULT 'requested'");
        
        // Update status pembayaran jika perlu lebih deskriptif
        // DB::statement("ALTER TABLE pembayaran MODIFY COLUMN status_pembayaran ENUM('pending', 'lunas') NOT NULL DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan ke enum lama
        DB::statement("ALTER TABLE reservasi MODIFY COLUMN status ENUM('requested', 'confirmed', 'cancelled', 'done') NOT NULL DEFAULT 'requested'");
    }
};