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
        Schema::create('treatment', function (Blueprint $table) {
        $table->id('id_treatment');
        $table->string('nama_treatment')->unique();
        $table->text('deskripsi')->nullable();
        $table->decimal('harga', 10, 2); // tanpa check()
        $table->string('foto_treatment')->nullable();
        $table->integer('durasi'); // tanpa check()
        $table->timestamps();
        $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('treatment');
    }
};
