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
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bidang_id')->constrained('bidang');

            $table->string('nip', 20)->unique();
            $table->string('nama', 45);
            $table->string('karpeg', 10)->nullable();
            $table->enum('jns_kelamin', ['L', 'P']);
            $table->string('agama', 10);
            $table->date('tgl_lahir');
            $table->integer('usia');
            $table->string('tpt_lahir', 20);
            $table->string('telp', 15);
            $table->string('kode_pos', 5);
            $table->string('alamat', 50);
            $table->string('status_kawin', 15);
            $table->string('suami_istri', 45)->nullable();
            $table->string('sta_kerja_suami_istri', 15)->nullable();
            $table->integer('jumlah_anak');

            $table->enum('jns_karyawan', ['PNS', 'CPNS', 'PPPK']);
            $table->string('jabatan', 45);
            $table->string('gol_ruang', 5);
            $table->string('pangkat', 25);
            $table->date('tmt_pangkat');
            $table->integer('masa_kerja_thn');
            $table->integer('masa_kerja_bln');
            $table->string('foto')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
