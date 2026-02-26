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
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();

            $table->string('nomor_surat', 100);

            $table->decimal('gaji_lama', 12, 2);
            $table->decimal('gaji_baru', 12, 2);
            $table->date('tmt_gaji_lama');
            $table->date('tmt_kgb');
            $table->date('kgb_berikutnya');

            $table->string('masa_kerja_lama', 25);
            $table->string('masa_kerja_baru', 25);
            $table->string('golongan_lama', 25);
            $table->string('golongan_baru', 25);

            $table->enum('status_kgb', ['disetujui']);

            $table->foreignId('diajukan_oleh')->constrained('pegawai');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('pegawai');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kgbs');
    }
};
