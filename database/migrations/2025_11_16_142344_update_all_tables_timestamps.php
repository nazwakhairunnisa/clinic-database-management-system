<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
         $tables = [
            'detail_reservasi',
            'jadwal_operasional',
            'pasien',
            'pembayaran',
            'pembelian_obat',
            'promo',
            'rekam_kondisi_kulit',
            'reservasi',
            'resume_pasien',
            'stok_obat',
            'supplier',
            'transaksi_keuangan',
            'treatment',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'))->change();
                $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'))->change();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'detail_reservasi',
            'jadwal_operasional',
            'pasien',
            'pembayaran',
            'pembelian_obat',
            'promo',
            'rekam_kondisi_kulit',
            'reservasi',
            'resume_pasien',
            'stok_obat',
            'supplier',
            'transaksi_keuangan',
            'treatment',
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->timestamp('created_at')->nullable()->default(null)->change();
                $table->timestamp('updated_at')->nullable()->default(null)->change();
            });
        }
    }
};
