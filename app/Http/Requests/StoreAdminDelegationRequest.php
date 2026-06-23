<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminDelegationRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Proteksi Ketat: Hanya akun non-personil dengan role superadmin yang diizinkan
        return auth()->check() && auth()->user()->hasRole('superadmin');
    }

    protected function prepareForValidation()
    {
        // Sanitasi input: Bersihkan spasi kosong (whitespace) pada password konfirmasi
        if ($this->has('password_konfirmasi')) {
            $this->merge([
                'password_konfirmasi' => trim($this->password_konfirmasi),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'pegawai_id'          => ['required', 'exists:pegawais,id'],
            'password_konfirmasi' => ['required', 'string'],
            'expires_at'          => ['nullable', 'date', 'after:today'],
            'catatan'             => ['nullable', 'string', 'max:500'],
        ];
    }

    public function messages(): array
    {
        return [
            'pegawai_id.required'          => 'Pegawai yang akan diutus wajib dipilih.',
            'pegawai_id.exists'            => 'Data profil pegawai tidak ditemukan dalam sistem.',
            'password_konfirmasi.required' => 'Password konfirmasi Superadmin wajib diisi demi keamanan.',
            'expires_at.after'             => 'Tanggal kedaluwarsa delegasi harus di masa mendatang.',
            'catatan.max'                  => 'Catatan alasan delegasi maksimal 500 karakter.',
        ];
    }
}
