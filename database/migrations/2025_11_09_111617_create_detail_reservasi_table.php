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
        Schema::create('detail_reservasi', function (Blueprint $table) {
        $table->id('id_detail');
        $table->unsignedBigInteger('id_reservasi');
        $table->unsignedBigInteger('id_treatment');
        $table->decimal('harga_saat_reservasi', 10, 2); // tanpa check()
        $table->integer('quantity'); // tanpa check()
        $table->timestamps();
        $table->softDeletes();

        $table->foreign('id_reservasi')
            ->references('id_reservasi')
            ->on('reservasi')
            ->onDelete('cascade');

        $table->foreign('id_treatment')
            ->references('id_treatment')
            ->on('treatment')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_reservasi');
    }
};
