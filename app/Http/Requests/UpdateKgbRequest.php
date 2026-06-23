<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKgbRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'superadmin']);
    }

    public function rules(): array
    {
        return [
            'gaji_lama'       => ['required', 'integer', 'min:0'],
            'gaji_baru'       => ['required', 'integer', 'min:0'],
            'golongan_lama'   => ['required', 'string', 'max:50'],
            'golongan_baru'   => ['required', 'string', 'max:50'],
            'masa_kerja_lama' => ['required', 'string', 'max:50'],
            'masa_kerja_baru' => ['required', 'string', 'max:50'],
            'tmt_gaji_lama'   => ['required', 'date'],
            'tmt_gaji_baru'   => ['required', 'date'],
            'kgb_berikutnya'  => ['required', 'date', 'after:tmt_gaji_baru'],
        ];
    }
}
