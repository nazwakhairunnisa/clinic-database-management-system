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
        Schema::create('rekam_medis', function (Blueprint $table) {
            $table->id('id_rekam_medis');

            // Foreign key ke pasien
            $table->unsignedBigInteger('id_pasien');
            $table->foreign('id_pasien')
                    ->references('id_pasien')
                    ->on('pasien')
                    ->onDelete('cascade');

            $table->text('keluhan');
            $table->enum('jenis_kulit', ['normal', 'dry', 'oily', 'sensitive', 'kombinasi']);
            $table->enum('kelembapan', ['baik', 'cukup', 'kurang']);
            $table->enum('kondisi_pasien', ['normal', 'hamil', 'menyusui', 'kontrasepsi']);
            $table->text('produk_terakhir_dipakai');
            $table->text('riwayat_penyakit');
            $table->text('riwwayat_pengobatan');
            $table->text('riwayat_alergi');
            $table->timestamp('created_at')->useCurrent(); // default CURRENT_TIMESTAMP

            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekam_medis');
    }
};
