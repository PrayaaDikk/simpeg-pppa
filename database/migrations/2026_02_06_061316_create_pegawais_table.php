<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidang_id')->nullable()->constrained('bidang')->nullOnDelete();

            $table->string('nip', 20)->unique();
            $table->string('nama', 100);
            $table->string('karpeg', 20)->nullable();
            $table->enum('jns_kelamin', ['L', 'P']);
            $table->string('agama', 20);
            $table->date('tgl_lahir');
            $table->string('tpt_lahir', 50);

            $table->string('telp', 20);
            $table->string('kode_pos', 10);
            $table->text('alamat');

            $table->string('status_kawin', 20);
            $table->string('suami_istri', 100)->nullable();
            $table->string('sta_kerja_suami_istri', 50)->nullable();
            $table->integer('jumlah_anak')->default(0);

            $table->enum('jns_karyawan', ['PNS', 'CPNS', 'PPPK']);
            $table->string('gol_ruang', 10);
            $table->string('pangkat', 50);
            $table->date('tmt_pegawai');
            $table->string('foto')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
