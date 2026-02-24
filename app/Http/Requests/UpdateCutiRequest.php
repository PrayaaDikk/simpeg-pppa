<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            // User mungkin mengedit detail waktu jika ada 'Perubahan'
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'lama_cuti'       => 'required|integer|min:1',

            // Kolom Persetujuan (Hanya untuk Admin/Atasan)
            'status_cuti'     => 'required|in:Menunggu,Disetujui,Perubahan,Ditangguhkan,Ditolak',
            'keputusan_atasan' => 'required|in:Disetujui,Perubahan,Ditangguhkan,Tidak Disetujui',
            'catatan_cuti'    => 'nullable|string|max:500',
            'disetujui_oleh'  => 'nullable|exists:users,id',
        ];
    }

    public function messages(): array
    {
        return [
            'status_cuti.in' => 'Statusnya jangan aneh-aneh ya, pilih yang sudah disediakan. 🍼',
            'catatan_cuti.max' => 'Waduh, catatannya kepanjangan! Maksimal 500 huruf aja ya.',
        ];
    }

    public function attributes(): array
    {
        return [
            'status_cuti' => 'Status Pengajuan',
            'keputusan_atasan' => 'Keputusan Atasan',
            'catatan_cuti' => 'Catatan/Komentar',
        ];
    }
}
