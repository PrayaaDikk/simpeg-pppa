<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RiwayatPendidikanRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        // Berdasarkan web.php, rute ini sudah dilindungi middleware 'role:admin'
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'superadmin']);
    }

    /**
     * Sanitasi data sebelum masuk proses validasi.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'institusi' => strip_tags(trim($this->input('institusi'))),
            'jurusan'   => strip_tags(trim($this->input('jurusan'))),
            'tingkat'   => strtoupper(trim($this->input('tingkat'))),
        ]);
    }

    /**
     * Aturan validasi data.
     */
    public function rules(): array
    {
        $isUpdate = $this->route('id') !== null;

        $rules = [
            'tingkat'     => ['required', 'in:SMA,D1,D2,D3,D4,S1,S2,S3'],
            'institusi'   => ['required', 'string', 'max:255'],
            'jurusan'     => ['required', 'string', 'max:255'],
            'tahun_lulus' => ['required', 'integer', 'digits:4'],
        ];

        if (!$isUpdate) {
            $rules['ijazah'] = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'];
        } else {
            // Kondisi Pembaruan Data (Update)
            // Jika request membawa file ijazah baru yang valid, terapkan pengecekan berkas
            if ($this->hasFile('ijazah')) {
                $rules['ijazah'] = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:2048'];
            } else {
                // Jika tidak mengunggah file baru (null/kosong), izinkan lolos tanpa dicek oleh aturan 'file'
                $rules['ijazah'] = ['nullable'];
            }
        }

        return $rules;
    }

    /**
     * Kustomisasi pesan error bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'tingkat.required'     => 'Tingkat jenjang pendidikan wajib dipilih.',
            'tingkat.in'           => 'Pilihan jenjang pendidikan tidak valid.',
            'institusi.required'   => 'Nama sekolah atau universitas wajib diisi.',
            'jurusan.required'     => 'Nama jurusan atau program studi wajib diisi.',
            'tahun_lulus.required' => 'Tahun lulus wajib diisi.',
            'tahun_lulus.integer'  => 'Tahun lulus harus berupa angka murni.',
            'tahun_lulus.digits'   => 'Tahun lulus harus berjumlah 4 digit angka.',
            'ijazah.required'      => 'Berkas scan ijazah wajib diunggah.',
            'ijazah.mimes'         => 'Format file ijazah harus berupa PDF, JPG, JPEG, atau PNG.',
            'ijazah.max'           => 'Ukuran file ijazah tidak boleh melebihi 2MB.',
        ];
    }
}
