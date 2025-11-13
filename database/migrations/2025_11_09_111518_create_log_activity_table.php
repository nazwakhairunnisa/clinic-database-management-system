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
        Schema::create('log_activity', function (Blueprint $table) {
        $table->id('id_log');
        $table->unsignedBigInteger('id_user');
        $table->text('activity');
        $table->timestamp('created_at')->useCurrent(); // pakai default CURRENT_TIMESTAMP

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
        Schema::dropIfExists('log_activity');
    }
};
