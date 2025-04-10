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
            $table->unsignedBigInteger('id_user');
            $table->string('nisn', 10);
            $table->date('tgl_bayar');
            $table->string('bulan_bayar', 10);
            $table->string('tahun_bayar', 4);
            $table->unsignedBigInteger('id_spp');
            $table->integer('jumlah_bayar');
            $table->timestamps();

            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('nisn')->references('nisn')->on('siswa')->onDelete('cascade');
            $table->foreign('id_spp')->references('id_spp')->on('spp')->onDelete('cascade');
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
