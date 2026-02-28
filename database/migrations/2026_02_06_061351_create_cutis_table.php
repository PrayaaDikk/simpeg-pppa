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
                'tahunan',
                'besar',
                'sakit',
                'melahirkan',
                'alasan penting',
                'di luar tanggungan negara'
            ]);

            $table->text('alasan_cuti');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->integer('lama_cuti');


            $table->text('alamat_cuti');
            $table->string('no_telp', 20);
            $table->text('catatan_cuti')->nullable();

            $table->enum('status_cuti', [
                'menunggu',
                'disetujui',
                'perubahan',
                'ditangguhkan',
                'ditolak'
            ])->default('menunggu');

            $table->enum('keputusan_atasan', [
                'disetujui',
                'perubahan',
                'ditangguhkan',
                'tidak disetujui'
            ])->default('ditangguhkan');

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
        Schema::dropIfExists('cutis');
    }
};
