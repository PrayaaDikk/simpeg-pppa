<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRiwayatKepangkatanRequest extends FormRequest
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
            // 'pegawai_id' => 'required|exists:pegawai,id',
            'pangkat_id' => 'required|exists:pangkat,id',
            'tmt_pangkat' => 'required|date',
            'nomor_sk' => 'required|string|max:45',
            'file_sk' => 'required|file|mimes:pdf|max:2048',
            'tanggal_input' => 'required|date',
        ];
    }

    public function messages()
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'max' => 'Kolom :attribute maksimal :max karakter.',
            'exists' => 'Kolom :attribute tidak valid.',
            'in' => 'Kolom :attribute tidak valid.',
            'integer' => 'Kolom :attribute harus berupa angka bulat.',
            'min' => 'Kolom :attribute minimal :min.',
            'file' => 'Kolom :attribute harus berupa file.',
            'mimes' => 'Kolom :attribute harus berformat: :values.',
        ];
    }

    public function attributes()
    {
        return [
            'pegawai_id' => 'ID Pegawai',
            'pangkat_id' => 'Pangkat',
            'tmt_pangkat' => 'TMT Pangkat',
            'pangkat' => 'Pangkat',
            'nomor_sk' => 'Nomor SK',
            'tanggal_input' => 'Tanggal Input',
            'file_sk' => 'File SK',
        ];
    }
}
