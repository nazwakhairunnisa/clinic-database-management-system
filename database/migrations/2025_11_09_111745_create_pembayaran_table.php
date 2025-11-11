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
        Schema::create('pembayaran', function (Blueprint $table) {
    $table->id('id_pembayaran');
    $table->unsignedBigInteger('id_reservasi');
    $table->unsignedBigInteger('id_user');
    $table->date('tanggal_pembayaran');
    $table->decimal('total_pembayaran', 10, 2);
    $table->enum('metode_pembayaran', ['cash', 'transfer', 'ewallet']);
    $table->enum('status_pembayaran', ['belum', 'lunas'])->default('belum');
    $table->string('bukti_pembayaran')->nullable();
    $table->timestamps();

    $table->foreign('id_reservasi')
        ->references('id_reservasi')
        ->on('reservasi')
        ->onDelete('cascade');

    $table->foreign('id_user')
        ->references('id_user')
        ->on('users')
        ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};
