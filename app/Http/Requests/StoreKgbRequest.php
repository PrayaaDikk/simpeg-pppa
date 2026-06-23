<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreKgbRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'superadmin']);
    }

    protected function prepareForValidation()
    {
        // Sanitasi dasar: Mengubah string kosong atau spasi menjadi null pada parent_id
        $this->merge([
            'parent_id' => filled($this->parent_id) ? $this->parent_id : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'pegawai_id'      => ['required', 'exists:pegawai,id'],
            'gaji_lama'       => ['required', 'integer', 'min:0'],
            'gaji_baru'       => ['required', 'integer', 'min:0'],
            'golongan_lama'   => ['required', 'string', 'max:50'],
            'golongan_baru'   => ['required', 'string', 'max:50'],
            'masa_kerja_lama' => ['required', 'string', 'max:50'],
            'masa_kerja_baru' => ['required', 'string', 'max:50'],
            'tmt_gaji_lama'   => ['required', 'date'],
            'tmt_gaji_baru'   => ['required', 'date'],
            'kgb_berikutnya'  => ['required', 'date', 'after:tmt_gaji_baru'],
            'parent_id'       => ['nullable', 'exists:kgb,id']
        ];
    }
}
