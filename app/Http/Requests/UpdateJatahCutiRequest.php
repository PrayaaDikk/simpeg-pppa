<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCutiRequest extends FormRequest
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
        // Ambil ID jatah_cuti dari parameter Route (misal: /admin/jatah-cuti/{id})
        $id = $this->route('id');

        return [
            'pegawai_id' => [
                'required',
                'exists:pegawai,id',
                // Cek unik, tapi abaikan record yang sedang di-edit ini
                Rule::unique('jatah_cuti')->where(
                    fn($query) =>
                    $query->where('pegawai_id', $this->pegawai_id)
                        ->where('tahun', $this->tahun)
                )->ignore($id),
            ],
            'tahun' => 'required|digits:4|integer|min:2020|max:' . (now()->year + 1),
            'jatah_tahunan' => 'required|integer|min:0|max:50',
            'sisa_tahun_lalu' => 'nullable|integer|min:0|max:12',
            'terpakai' => 'required|integer|min:0', // Admin mungkin perlu koreksi jumlah terpakai
        ];
    }

    public function messages(): array
    {
        return [
            'pegawai_id.unique' => 'Ups! Pegawai ini sudah punya jatah di tahun tersebut. Gak boleh dobel ya! 🙅‍♂️',
            'terpakai.min' => 'Masa :attribute minus? Emangnya mau ngutang cuti? Hehe. Minimal :min ya.',
            'required' => ':attribute jangan dikosongin ya, nanti dia sedih.',
            'integer' => ':attribute harus angka bulat, bukan potongan kue! 🍰',
        ];
    }

    public function attributes(): array
    {
        return [
            'pegawai_id' => 'Nama Pegawai',
            'tahun' => 'Tahun Cuti',
            'jatah_tahunan' => 'Jatah Cuti Tahunan',
            'sisa_tahun_lalu' => 'Sisa Cuti Tahun Lalu',
            'terpakai' => 'Cuti Terpakai',
        ];
    }
}
