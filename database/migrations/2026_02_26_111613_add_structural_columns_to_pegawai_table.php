<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            // Kita selipkan setelah bidang_id agar urutannya rapi di database
            $table->foreignId('jabatan_id')->after('bidang_id')->nullable()->constrained('jabatan')->nullOnDelete();

            // Atasan merujuk ke tabel yang sama (pegawai)
            $table->foreignId('atasan_id')->after('jabatan_id')->nullable()->constrained('pegawai')->nullOnDelete();

            // Kuota cuti dasar diletakkan setelah masa kerja
            $table->integer('kuota_cuti')->after('foto')->default(12);
        });
    }

    public function down(): void
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->dropForeign(['jabatan_id']);
            $table->dropForeign(['atasan_id']);
            $table->dropColumn(['jabatan_id', 'atasan_id', 'kuota_cuti']);
        });
    }
};
