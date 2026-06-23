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
        Schema::create('kgb', function (Blueprint $table) {
            $table->id();

            // Relasi utama ke tabel pegawai
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();

            // Kolom Parent ID untuk tracking state-machine berkas rujukan sebelumnya
            $table->foreignId('parent_id')
                ->nullable()
                ->constrained('kgb')
                ->cascadeOnDelete();

            // Data Historis Gaji & Golongan Lama
            $table->string('golongan_lama');
            $table->integer('gaji_lama');
            $table->string('masa_kerja_lama');
            $table->date('tmt_gaji_lama');

            // Data Historis Gaji & Golongan Baru
            $table->string('golongan_baru');
            $table->integer('gaji_baru');
            $table->string('masa_kerja_baru');
            $table->date('tmt_gaji_baru');

            // Jadwal Target Kenaikan Berikutnya
            $table->date('kgb_berikutnya');

            // Enum Status KGB terkalibrasi dengan state baru ('telah diproses')
            $table->enum('status_kgb', ['menunggu', 'disetujui', 'tidak disetujui', 'telah diproses'])->default('menunggu');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kgb');
    }
};
