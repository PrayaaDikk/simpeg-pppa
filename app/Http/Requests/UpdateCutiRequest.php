<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCutiRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Validasi bisnis: Berkas cuti yang sudah disetujui/ditangguhkan tidak boleh diedit isinya
        $cuti = $this->route('cuti') ? \App\Models\Cuti::find($this->route('cuti')) : null;
        if ($cuti && $cuti->status_cuti !== 'ditangguhkan' && $cuti->status_cuti !== 'disetujui') {
            return false;
        }
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'superadmin']);
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'atasan_id'     => $this->atasan_id ? (int) $this->atasan_id : null,
            'lama_cuti'     => (int) $this->lama_cuti,
            'alamat'        => strip_tags(trim($this->alamat)), // Sanitasi XSS
            'no_telp'       => filter_var(trim($this->no_telp), FILTER_SANITIZE_NUMBER_INT),
            'alasan_cuti'   => $this->alasan_cuti ? strip_tags(trim($this->alasan_cuti)) : null,
            'catatan_cuti'  => $this->catatan_cuti ? strip_tags(trim($this->catatan_cuti)) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'atasan_id'     => 'required|exists:pegawai,id',
            'jenis_cuti'    => 'required|in:tahunan,besar,sakit,melahirkan,alasan penting,diluar tanggungan negara',
            'tanggal_mulai' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_mulai',
            'lama_cuti'     => 'required|integer|min:1',
            'alamat'        => 'required|string',
            'no_telp'       => 'required|string|max:15',
            'alasan_cuti'   => 'nullable|string',
            'catatan_cuti'  => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'tanggal_akhir.after_or_equal' => 'Tanggal akhir cuti tidak boleh mendahului tanggal mulai cuti.',
            'lama_cuti.min'                => 'Durasi lama cuti minimal adalah 1 hari.',
        ];
    }
}
