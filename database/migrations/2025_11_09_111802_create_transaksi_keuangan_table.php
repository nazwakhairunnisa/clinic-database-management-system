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
        Schema::create('transaksi_keuangan', function (Blueprint $table) {
    $table->id('id_transaksi');
    $table->unsignedBigInteger('id_user')->nullable();
    $table->unsignedBigInteger('id_pembayaran')->nullable();
    $table->unsignedBigInteger('id_pembelian_obat')->nullable();
    $table->string('nama_transaksi');
    $table->date('tanggal_transaksi');
    $table->enum('jenis_transaksi', ['pemasukan', 'pengeluaran']);
    $table->enum('metode_pembayaran', ['cash', 'transfer', 'ewallet']);
    $table->decimal('jumlah', 10, 2);
    $table->text('keterangan')->nullable();
    $table->timestamps();

    $table->foreign('id_user')
        ->references('id_user')
        ->on('users')
        ->onDelete('set null');

    $table->foreign('id_pembayaran')
        ->references('id_pembayaran')
        ->on('pembayaran')
        ->onDelete('set null');

    $table->foreign('id_pembelian_obat')
        ->references('id_pembelian_obat')
        ->on('pembelian_obat')
        ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_keuangan');
    }
};
