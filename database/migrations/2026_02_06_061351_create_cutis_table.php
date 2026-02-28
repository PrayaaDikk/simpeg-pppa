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
        Schema::create('cuti', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pegawai_id')->constrained('pegawai')->cascadeOnDelete();

            $table->enum('jenis_cuti', [
                'Tahunan',
                'Besar',
                'Sakit',
                'Melahirkan',
                'Alasan Penting',
                'Diluar Tanggungan Negara'
            ]);

            $table->text('alasan_cuti')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('lama_cuti');


            $table->text('alamat_cuti');
            $table->string('no_telp', 20);
            $table->text('catatan_cuti')->nullable();

            $table->enum('status_cuti', [
                'Menunggu',
                'Disetujui',
                'Perubahan',
                'Ditangguhkan',
                'Tidak disetujui'
            ])->default('Menunggu');

            $table->enum('keputusan_atasan', [
                'Menunggu',
                'Disetujui',
                'Perubahan',
                'Ditangguhkan',
                'Tidak disetujui'
            ])->default('Menunggu');

            $table->foreignId('diajukan_oleh')->constrained('pegawai');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cutis');
    }
};
