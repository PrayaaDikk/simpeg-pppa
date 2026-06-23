<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RiwayatJabatanRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'superadmin']);
    }

    /**
     * Jalankan sanitasi input sebelum masuk ke aturan validasi.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nama_jabatan' => strip_tags(trim($this->input('nama_jabatan'))),
            'nomor_sk'     => strip_tags(trim($this->input('nomor_sk'))),
        ]);
    }

    /**
     * Aturan validasi data riwayat jabatan.
     */
    public function rules(): array
    {
        return [
            'nama_jabatan' => ['required', 'string', 'max:255'],
            'tmt_jabatan'  => ['required', 'date', 'date_format:Y-m-d'],
            'nomor_sk'     => ['required', 'string', 'max:255'],
            'tanggal_sk'   => ['required', 'date', 'date_format:Y-m-d'],
        ];
    }

    /**
     * Kustomisasi pesan error bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'nama_jabatan.required' => 'Nama jabatan kedudukan wajib diisi.',
            'nama_jabatan.string'   => 'Nama jabatan harus berupa teks valid.',
            'tmt_jabatan.required'  => 'Terhitung Mulai Tanggal (TMT) jabatan wajib diisi.',
            'tmt_jabatan.date'      => 'Format tanggal TMT jabatan tidak valid.',
            'nomor_sk.required'     => 'Nomor Surat Keputusan (SK) jabatan wajib diisi.',
            'tanggal_sk.required'   => 'Tanggal penerbitan SK jabatan wajib diisi.',
            'tanggal_sk.date'       => 'Format tanggal penerbitan SK tidak valid.',
        ];
    }
}
