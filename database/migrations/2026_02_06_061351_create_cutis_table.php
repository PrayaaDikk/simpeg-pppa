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
                'Di Luar Tanggungan Negara'
            ]);

            $table->text('alasan_cuti');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('lama_cuti');
            $table->text('alamat_cuti');
            $table->string('no_telp', 20);
            $table->text('catatan_cuti');

            $table->enum('status_cuti', [
                'Menunggu',
                'Disetujui',
                'Perubahan',
                'Ditangguhkan',
                'Ditolak'
            ]);

            $table->enum('keputusan_atasan', [
                'Disetujui',
                'Perubahan',
                'Ditangguhkan',
                'Tidak Disetujui'
            ]);

            $table->foreignId('diajukan_oleh')->constrained('users');
            $table->foreignId('disetujui_oleh')->nullable()->constrained('users');

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
