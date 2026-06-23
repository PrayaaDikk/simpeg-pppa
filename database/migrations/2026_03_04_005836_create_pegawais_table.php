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

            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();

            $table->foreignId('bidang_id')->nullable()->constrained('bidang')->nullOnDelete();
            $table->foreignId('jabatan_id')->nullable()->constrained('jabatan')->nullOnDelete();

            $table->string('nip')->unique();
            $table->string('nama');
            $table->string('karpeg', 20)->nullable();

            $table->enum('jenis_kelamin', ['l', 'p']);
            $table->enum('agama', ['islam', 'kristen', 'katolik', 'hindu', 'buddha', 'konghucu'])->default('islam');

            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');

            $table->string('no_telp');
            $table->string('kode_pos');
            $table->text('alamat');

            $table->enum('status_kawin', ['belum kawin', 'kawin', 'cerai hidup', 'cerai mati'])->default('belum kawin');
            $table->string('nama_pasangan')->nullable();
            $table->string('status_kerja_pasangan')->nullable();
            $table->integer('jumlah_anak')->nullable();

            $table->enum('jenis_pegawai', ['pns', 'cpns', 'pppk'])->default('pns');
            $table->foreignId('pangkat_id')->constrained('pangkat');

            $table->integer('jatah_cuti_dua_tahun_lalu')->default(0)->max(6);
            $table->integer('jatah_cuti_satu_tahun_lalu')->default(0)->max(6);
            $table->integer('jatah_cuti_tahun_ini')->default(12);

            $table->date('tmt_pegawai');
            $table->boolean('is_active')->default(true);

            $table->string('foto')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
