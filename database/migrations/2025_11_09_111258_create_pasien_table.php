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
        Schema::create('pasien', function (Blueprint $table) {
            $table->id('id_pasien');
            
            // Foreign key ke tabel users
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')
                    ->references('id_user')
                    ->on('users')
                    ->onDelete('cascade');
            
            
            $table->string('nama_depan');
            $table->string('nama_belakang');
            $table->date('tanggal_lahir');
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->string('no_telepon', 15)->unique();
            $table->string('alamat');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pasien');
    }
};
