<?php

namespace App\Console\Commands;

use App\Models\JatahCuti;
use App\Models\Pegawai;
use Illuminate\Console\Command;

class ResetJatahCuti extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:reset-jatah-cuti';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tahunLalu = now()->subYear()->year;
        $tahunBaru = now()->year;
        $pegawais = Pegawai::all();

        foreach ($pegawais as $pegawai) {
            // 1. Ambil sisa jatah tahun lalu
            $dataLalu = $pegawai->jatahCuti()->where('tahun', $tahunLalu)->first();
            $sisaBawaan = $dataLalu ? $dataLalu->sisa : 0;

            // 2. Batasi jatah yang boleh dibawa (misal maks 6 hari)
            $carryOver = min($sisaBawaan, 6);

            // 3. Buat record baru untuk tahun ini
            JatahCuti::updateOrCreate(
                ['pegawai_id' => $pegawai->id, 'tahun' => $tahunBaru],
                [
                    'jatah_tahunan' => 12,
                    'sisa_tahun_lalu' => $carryOver,
                    'terpakai' => 0,
                    'sisa' => 12 + $carryOver
                ]
            );
        }
        $this->info("Jatah cuti tahun $tahunBaru berhasil di-generate!");
    }
}
