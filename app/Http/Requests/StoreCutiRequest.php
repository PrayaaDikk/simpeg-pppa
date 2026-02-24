<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
            'pegawai_id'      => 'exists:pegawai,id',
            'jenis_cuti'      => 'required|in:Tahunan,Besar,Sakit,Melahirkan,Alasan Penting,Di Luar Tanggungan Negara',
            'alasan_cuti'     => 'required|string|min:5',
            'tanggal_mulai'   => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            // 'lama_cuti'       => 'required|integer|min:1',
            'alamat_cuti'     => 'required|string',
            'no_telp'         => 'required|string|max:20',
            // Catatan cuti opsional saat awal pengajuan
            'catatan_cuti'    => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute harus diisi.',
            'exists' => ':attribute tidak valid.',
            'in' => ':attribute tidak valid.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'after_or_equal' => ':attribute harus setelah tanggal sekarang.',
            'integer' => ':attribute harus berupa angka.',
            'min' => ':attribute minimal :min.',
            'max' => ':attribute maksimal :max.',
            'string' => ':attribute harus berupa teks.',
            'numeric' => ':attribute harus berupa angka.',
            'after' => ':attribute harus setelah :date.',
            'date_format' => ':attribute harus berformat :format.',
            'before_or_equal' => ':attribute harus sebelum atau sama dengan :date.',
            'after_or_equal' => ':attribute harus setelah atau sama dengan :date.',
        ];
    }

    public function attributes(): array
    {
        return [
            'pegawai_id' => 'ID Pegawai',
            'jenis_cuti' => 'Jenis Cuti',
            'alasan_cuti' => 'Alasan Cuti',
            'tanggal_mulai' => 'Tanggal Mulai',
            'tanggal_selesai' => 'Tanggal Selesai',
            'lama_cuti' => 'Lama Hari Cuti',
            'alamat_cuti' => 'Alamat Selama Cuti',
            'no_telp' => 'Nomor Telepon',
        ];
    }
}
