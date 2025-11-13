<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stok_obat', function (Blueprint $table) {
            $table->string('nama_obat')->after('id_obat');
            $table->text('deskripsi')->nullable()->after('nama_obat');
            $table->string('satuan')->default('pcs')->after('deskripsi'); // misal: box, botol, strip, dll
        });
    }

    public function down(): void
    {
        Schema::table('stok_obat', function (Blueprint $table) {
            $table->dropColumn(['nama_obat', 'deskripsi', 'satuan']);
        });
    }
};