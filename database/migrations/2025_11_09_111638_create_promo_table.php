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
        Schema::create('promo', function (Blueprint $table) {
        $table->id('promo_id');
        $table->unsignedBigInteger('id_treatment');
        $table->string('nama_promo');
        $table->string('gambar_promo');
        $table->date('periode_mulai');
        $table->date('periode_selesai');
        $table->decimal('harga_promo', 10, 2);
        $table->timestamps();
        $table->softDeletes();

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
        Schema::dropIfExists('promo');
    }
};
