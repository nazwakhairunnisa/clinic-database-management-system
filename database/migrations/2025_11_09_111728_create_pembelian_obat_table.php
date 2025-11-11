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
        Schema::create('pembelian_obat', function (Blueprint $table) {
    $table->id('id_pembelian_obat');
    $table->unsignedBigInteger('id_obat');
    $table->unsignedBigInteger('id_supplier');
    $table->date('tanggal_beli');
    $table->integer('jumlah');
    $table->decimal('harga_satuan', 10, 2);
    $table->enum('status_pembayaran', ['belum', 'lunas'])->default('belum');
    $table->date('tanggal_jatuh_tempo');
    $table->timestamps();

    $table->foreign('id_obat')
        ->references('id_obat')
        ->on('stok_obat')
        ->onDelete('cascade');

    $table->foreign('id_supplier')
        ->references('id_supplier')
        ->on('supplier')
        ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian_obat');
    }
};
