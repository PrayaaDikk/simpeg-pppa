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
            $table->foreignId('atasan_id')->nullable()->constrained('pegawai')->nullOnDelete();
            $table->enum('jenis_cuti', ['tahunan', 'besar', 'sakit', 'melahirkan', 'alasan penting', 'diluar tanggungan negara']);
            $table->text('alasan_cuti')->nullable();

            $table->date('tanggal_mulai');
            $table->date('tanggal_akhir');
            $table->integer('lama_cuti');

            $table->text('alamat');
            $table->string('no_telp', 15);

            $table->text('riwayat_potongan')->nullable();

            $table->text('catatan_cuti')->nullable();

            $table->enum('status_cuti', ['disetujui', 'ditangguhkan'])->default('disetujui');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cuti');
    }
};
