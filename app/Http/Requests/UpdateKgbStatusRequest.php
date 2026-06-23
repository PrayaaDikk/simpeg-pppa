<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateKgbStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        // Sesuaikan dengan logic auth sistem Anda, misal: return auth()->user()->hasRole('admin');
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'superadmin']);
    }

    protected function prepareForValidation()
    {
        // Contoh Sanitasi: Memastikan status yang masuk adalah huruf kecil bersih tanpa spasi liar
        if ($this->has('status_kgb')) {
            $this->merge([
                'status_kgb' => strtolower(trim($this->status_kgb)),
            ]);
        }
    }

    public function rules(): array
    {
        return [
            'status_kgb' => ['required', 'in:menunggu,disetujui,tidak disetujui,telah diproses'],
        ];
    }
}
