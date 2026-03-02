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

            // Dasar SK Terakhir (Poin a - c)
            $table->string('nomor_surat');
            $table->date('sk_tanggal');
            $table->string('sk_nomor');

            // Gaji & Masa Kerja Lama (Poin 6, d, e)
            $table->decimal('gaji_pokok_lama', 12, 2);
            $table->date('tgl_mulai_gaji_lama');
            $table->integer('masa_kerja_tahun_lama');
            $table->integer('masa_kerja_bulan_lama');

            // Gaji & Masa Kerja Baru (Poin 7 - 10)
            $table->decimal('gaji_pokok_baru', 12, 2);
            $table->string('gol_ruang_baru'); // Contoh: III/d
            $table->integer('masa_kerja_tahun_baru');
            $table->integer('masa_kerja_bulan_baru');
            $table->date('tgl_mulai_berlaku'); // 01 Januari 2026

            // Estimasi Mendatang (Poin 11)
            $table->date('tgl_kgb_mendatang'); // 01 Januari 2028

            $table->string('status_pegawai');

            $table->string('file_sk')->nullable();

            $table->enum('status_kgb', ['Menunggu', 'Disetujui', 'Ditolak'])->default('Menunggu');

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
