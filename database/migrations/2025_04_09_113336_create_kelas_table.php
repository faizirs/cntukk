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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id('id_kelas');
            $table->string('nama_kelas', 255);
            $table->unsignedBigInteger('kompetensi_keahlian_id');
            $table->timestamps();

            $table->foreign('kompetensi_keahlian_id')->references('id_kompetensi_keahlian')->on('kompetensi_keahlian')->onDelete('cascade');

            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
