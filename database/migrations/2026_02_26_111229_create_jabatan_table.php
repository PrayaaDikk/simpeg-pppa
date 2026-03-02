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
        // database/migrations/xxxx_create_jabatan_table.php
        Schema::create('jabatan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_jabatan'); // Kadin, Kabid, Staff, dll
            $table->foreignId('bidang_id')->nullable()->constrained('bidang')->nullOnDelete();

            // Logika Pintar:
            $table->boolean('is_singleton')->default(false); // True jika cuma boleh 1 orang
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jabatan');
    }
};
