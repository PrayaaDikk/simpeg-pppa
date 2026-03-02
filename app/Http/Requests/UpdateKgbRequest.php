<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKgbRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'pegawai_id' => 'required|exists:pegawai,id',

            'nomor_surat' => 'required|string|max:255',
            'sk_tanggal' => 'required|date',
            'sk_nomor' => 'required|string|max:255',

            // Gaji & Masa Kerja Lama
            'gaji_pokok_lama' => 'required|numeric',
            'tgl_mulai_gaji_lama' => 'required|date',
            'masa_kerja_tahun_lama' => 'required|integer',
            'masa_kerja_bulan_lama' => 'required|integer',

            // Gaji & Masa Kerja Baru
            'gaji_pokok_baru' => 'required|numeric',
            'gol_ruang_baru' => 'required|string|max:255',
            'masa_kerja_tahun_baru' => 'required|integer',
            'masa_kerja_bulan_baru' => 'required|integer',
            'tgl_mulai_berlaku' => 'required|date',

            // Estimasi Mendatang
            'tgl_kgb_mendatang' => 'required|date',

            'status_pegawai' => 'required|string|max:255',

            'status_kgb' => 'in:Menunggu,Disetujui,Ditolak',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Kolom :attribute wajib diisi',
            'integer' => 'Kolom :attribute wajib angka bulat',
            'numeric' => 'Kolom :attribute wajib angka',
            'exists' => ':attribute tidak valid',
            'in' => ':attribute tidak valid',
        ];
    }

    public function attributes()
    {
        return [
            'pegawai_id' => 'ID Pegawai',

            'nomor_surat' => 'Nomor Surat',
            'sk_tanggal' => 'Tanggal Surat Keputusan Terakhir',
            'sk_nomor' => 'Nomor Surat Keputusan Terakhir',

            // Gaji & Masa Kerja Lama
            'gaji_pokok_lama' => 'Gaji Pokok Lama',
            'tgl_mulai_gaji_lama' => 'Tanggal Mulai Gaji Lama',
            'masa_kerja_tahun_lama' => 'Masa Kerja Tahun Lama',
            'masa_kerja_bulan_lama' => 'Masa Kerja Bulan Lama',

            // Gaji & Masa Kerja Baru
            'gaji_pokok_baru' => 'Gaji Pokok Baru',
            'gol_ruang_baru' => 'Golongan Ruang Baru',
            'masa_kerja_tahun_baru' => 'Masa Kerja Tahun Baru',
            'masa_kerja_bulan_baru' => 'Masa Kerja Bulan Baru',
            'tgl_mulai_berlaku' => 'Tanggal Mulai Berlaku',

            // Estimasi Mendatang
            'tgl_kgb_mendatang' => 'Tanggal KGB Mendatang',

            'status_pegawai' => 'Status Pegawai',

            'status_kgb' => 'Status KGB',

        ];
    }
}
