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
        Schema::create('rekam_kondisi_kulit', function (Blueprint $table) {
            $table->id('id_kondisi');

            // Relasi ke rekam_medis
            $table->unsignedBigInteger('id_rekam_medis');
            $table->foreign('id_rekam_medis')
                    ->references('id_rekam_medis')
                    ->on('rekam_medis')
                    ->onDelete('cascade');

            // Kolom kondisi kulit
            $table->text ('jenis_kondisi');
            $table->enum('status_kondisi', ['ada', 'tidak ada'])->default('tidak ada');
            $table->string('area');
            $table->enum('derajat', ['ringan', 'sedang', 'berat'])->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekam_kondisi_kulit');
    }
};
