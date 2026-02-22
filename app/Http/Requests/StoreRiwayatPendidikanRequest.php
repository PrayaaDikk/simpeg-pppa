<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreRiwayatPendidikanRequest extends FormRequest
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
            'tingkat' => 'required|in:SMA,D3,D4,S1,S2,S3',
            'jurusan' => 'required|string|max:100',
            'institusi' => 'required|string|max:150',
            'tahun_lulus' => 'required|integer|digits:4',
            'nomor_ijazah' => 'required|string|max:255',
            'ijazah' => 'required|file|mimes:pdf|max:2048',
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
            'tingkat' => 'Tingkat Pendidikan',
            'jurusan' => 'Jurusan',
            'institusi' => 'Institusi',
            'tahun_lulus' => 'Tahun Lulus',
            'nomor_ijazah' => 'Nomor Ijazah',
            'ijazah' => 'Ijazah',
        ];
    }
}
