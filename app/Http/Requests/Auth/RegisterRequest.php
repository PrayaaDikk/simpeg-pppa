<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class RegisterRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Password::defaults()],
            'nip' => ['required', 'exists:pegawai,nip'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi',
            'stirng' => 'Kolom :attribute harus berupa teks',
            'max' => 'Kolom :attribute maksimal :max karakter',
            'email' => 'Kolom :attribute harus berupa email',
            'unique' => ':attribute sudah digunakan',
            'confirmed' => 'Kolom :attribute harus sama dengan kolom password',
            'exists' => ':attribute tidak terdaftar',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'password' => 'Kata Sandi',
            'nip' => 'NIP',
        ];
    }
}
