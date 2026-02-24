<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCutiRequest extends FormRequest
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
            'pegawai_id' => [
                'required',
                'exists:pegawai,id',
                // Validasi agar tidak ada duplikat jatah cuti di tahun yang sama
                Rule::unique('jatah_cuti')->where(
                    fn($query) =>
                    $query->where('pegawai_id', $this->pegawai_id)
                        ->where('tahun', $this->tahun)
                ),
            ],
            'tahun' => 'required|digits:4|integer|min:2020|max:' . (now()->year + 1),
            'jatah_tahunan' => 'required|integer|min:0|max:50',
            'sisa_tahun_lalu' => 'nullable|integer|min:0|max:12',
        ];
    }

    /**
     * Pesan error kustom (Messages) dalam Bahasa Indonesia.
     */
    public function messages(): array
    {
        return [
            'pegawai_id.required' => ':attribute harus dipilih.',
            'pegawai_id.exists' => ':attribute tidak terdaftar di sistem.',
            'pegawai_id.unique' => 'Pegawai ini sudah memiliki jatah cuti di tahun tersebut.',
            'tahun.required' => ':attribute tidak boleh kosong.',
            'tahun.digits' => ':attribute harus berupa 4 angka tahun.',
            'jatah_tahunan.required' => 'Jumlah :attribute harus diisi.',
            'integer' => ':attribute harus berupa angka bulat.',
            'min' => ':attribute minimal adalah :min.',
            'max' => ':attribute maksimal adalah :max.',
        ];
    }

    /**
     * Nama alias untuk field (Attributes) agar pesan error lebih enak dibaca.
     */
    public function attributes(): array
    {
        return [
            'pegawai_id' => 'Nama Pegawai',
            'tahun' => 'Tahun Cuti',
            'jatah_tahunan' => 'Jatah Cuti Tahunan',
            'sisa_tahun_lalu' => 'Sisa Cuti Tahun Lalu',
        ];
    }
}
