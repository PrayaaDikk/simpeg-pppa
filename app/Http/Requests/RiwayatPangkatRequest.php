<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RiwayatPangkatRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'superadmin']);
    }

    /**
     * Sanitasi data sebelum masuk proses validasi.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'nomor_sk'   => strip_tags(trim($this->input('nomor_sk'))),
            'pangkat_id' => $this->input('pangkat_id') ? (int) $this->input('pangkat_id') : null,
        ]);
    }

    /**
     * Aturan validasi data riwayat kepangkatan.
     */
    public function rules(): array
    {
        $isUpdate = $this->route('id') !== null;

        return [
            'pangkat_id'  => ['required', 'exists:pangkat,id'],
            'tmt_pangkat' => ['required', 'date', 'date_format:Y-m-d'],
            'nomor_sk'    => ['required', 'string', 'max:255'],
            'file_sk'     => [
                'nullable',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:2048'
            ],
        ];
    }

    /**
     * Kustomisasi pesan error bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'pangkat_id.required'  => 'Pilihan pangkat / golongan wajib dipilih.',
            'pangkat_id.exists'    => 'Pangkat pilihan tidak terdaftar di database master.',
            'tmt_pangkat.required' => 'Terhitung Mulai Tanggal (TMT) pangkat wajib diisi.',
            'tmt_pangkat.date'     => 'Format tanggal TMT pangkat tidak valid.',
            'nomor_sk.required'    => 'Nomor Surat Keputusan (SK) pangkat wajib diisi.',
            'file_sk.required'     => 'Berkas dokumen SK kepangkatan wajib diunggah.',
            'file_sk.mimes'        => 'Format file SK harus berupa PDF, JPG, JPEG, atau PNG.',
            'file_sk.max'          => 'Ukuran file dokumen SK tidak boleh melebihi 2MB.',
        ];
    }
}
