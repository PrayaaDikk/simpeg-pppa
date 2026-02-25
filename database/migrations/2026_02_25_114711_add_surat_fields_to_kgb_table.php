<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('kgb', function (Blueprint $table) {
            $table->date('tanggal_surat')->after('nomor_sk');
            $table->string('pejabat_penetap', 150)->after('golongan_baru');
            $table->string('nip_pejabat', 30)->after('pejabat_penetap');
            $table->string('file_sk')->nullable()->after('nip_pejabat');
        });
    }

    public function down(): void
    {
        Schema::table('kgb', function (Blueprint $table) {
            $table->dropColumn([
                'tanggal_surat',
                'pejabat_penetap',
                'nip_pejabat',
                'file_sk'
            ]);
        });
    }
};
