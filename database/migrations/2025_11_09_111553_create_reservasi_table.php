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
        Schema::create('reservasi', function (Blueprint $table) {
            $table->id('id_reservasi');
        $table->unsignedBigInteger('id_pasien');
        $table->unsignedBigInteger('id_user');
        $table->unsignedBigInteger('id_jadwal');
        $table->date('tanggal_reservasi');
        $table->time('jam_reservasi');
        $table->enum('status', ['requested', 'confirmed', 'cancelled', 'done'])->default('requested');
        $table->enum('metode_reservasi', ['manual', 'online'])->default('online');
        $table->text('keterangan')->nullable();
        $table->timestamps();

        // Foreign keys
        $table->foreign('id_pasien')
            ->references('id_pasien')
            ->on('pasien')
            ->onDelete('cascade');

        $table->foreign('id_user')
            ->references('id_user')
            ->on('users')
            ->onDelete('cascade');

        $table->foreign('id_jadwal')
            ->references('id_jadwal')
            ->on('jadwal_operasional')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasi');
    }
};
