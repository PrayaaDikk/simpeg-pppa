<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCutiRequest extends FormRequest
{
    /**
     * Tentukan apakah user diizinkan membuat request ini.
     */
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'superadmin']);
    }

    /**
     * Langkah Sanitasi Data sebelum proses Validasi berjalan.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'pegawai_id'    => (int) $this->pegawai_id,
            'atasan_id'     => $this->atasan_id ? (int) $this->atasan_id : null,
            'lama_cuti'     => (int) $this->lama_cuti,
            'alamat'        => strip_tags(trim($this->alamat)), // Sanitasi dari tag HTML xss
            'no_telp'       => filter_var(trim($this->no_telp), FILTER_SANITIZE_NUMBER_INT), // Angka & simbol telp
            'catatan_cuti'  => $this->catatan_cuti ? strip_tags(trim($this->catatan_cuti)) : null,
            'alasan_cuti'   => $this->alasan_cuti ? strip_tags(trim($this->alasan_cuti)) : null,
        ]);
    }

    /**
     * Aturan Validasi data cuti.
     */
    public function rules(): array
    {
        return [
            'pegawai_id'    => 'required|exists:pegawai,id',
            'atasan_id'     => 'required|exists:pegawai,id',
            'jenis_cuti'    => 'required|in:tahunan,besar,sakit,melahirkan,alasan penting,diluar tanggungan negara',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
            'lama_cuti'     => 'required|integer|min:1',
            'alamat'        => 'required|string',
            'no_telp'       => 'required|string|max:15',
            'catatan_cuti'  => 'nullable|string',
            'alasan_cuti'   => 'nullable|string',
        ];
    }

    /**
     * Kustomisasi pesan error jika validasi gagal.
     */
    public function messages(): array
    {
        return [
            'tanggal_akhir.after_or_equal' => 'Tanggal akhir cuti tidak boleh mendahului tanggal mulai cuti.',
            'lama_cuti.min'                => 'Durasi lama cuti minimal adalah 1 hari.',
            'pegawai_id.exists'            => 'Data pegawai yang dipilih tidak valid atau tidak ditemukan.',
        ];
    }
}
