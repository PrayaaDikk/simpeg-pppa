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
        Schema::create('riwayat_pendidikan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();

            $table->enum('tingkat', ['SMA', 'D3', 'D4', 'S1', 'S2', 'S3']);
            $table->tinyInteger('level_pendidikan')->index();

            $table->string('jurusan', 100);
            $table->string('institusi', 150);
            $table->year('tahun_lulus');

            $table->string('ijazah');
            $table->string('nomor_ijazah', 100);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riwayat_pendidikans');
    }
};
