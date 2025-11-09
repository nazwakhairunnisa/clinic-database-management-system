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
        Schema::create('resume_pasien', function (Blueprint $table) {
            $table->id('id_resume');

            // Foreign key ke pasien
            $table->unsignedBigInteger('id_pasien');
            $table->foreign('id_pasien')
                    ->references('id_pasien')
                    ->on('pasien')
                    ->onDelete('cascade');

            $table->date('tanggal_kunjungan');

            // Tambahan kolom sesuai data medis
            $table->text('anamnesa'); 
            $table->text('riwayat_eksfo'); 
            $table->text('terapi');

            // File foto (bisa simpan path gambar)
            $table->string('foto_sebelum_treatment');
            $table->string('foto_sesudah_treatment');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resume_pasien');
    }
};
