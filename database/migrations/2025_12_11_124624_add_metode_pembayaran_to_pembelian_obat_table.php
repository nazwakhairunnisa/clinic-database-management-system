<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pembelian_obat', function (Blueprint $table) {
            $table->enum('metode_pembayaran', ['cash', 'transfer', 'ewallet'])
                  ->nullable()
                  ->after('status_pembayaran');
        });
    }

    public function down()
    {
        Schema::table('pembelian_obat', function (Blueprint $table) {
            $table->dropColumn('metode_pembayaran');
        });
    }
};