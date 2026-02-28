<?php

namespace App\Http\Requests;

use App\Models\Jabatan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePegawaiRequest extends FormRequest
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
     */
    public function rules(): array
    {
        // 1. Cek apakah jabatan_id yang diinput adalah singleton
        $inputJabatanId = $this->input('jabatan_id');
        $isSingleton = false;

        if ($inputJabatanId) {
            $isSingleton = Jabatan::where('id', $inputJabatanId)
                ->where('is_singleton', true)
                ->exists();
        }

        return [
            'bidang_id' => 'nullable|exists:bidang,id',

            // 2. Terapkan rule unique jika jabatan adalah singleton
            'jabatan_id' => [
                'required',
                'exists:jabatan,id',
                $isSingleton ? 'unique:pegawai,jabatan_id' : '',
            ],

            'atasan_id' => 'nullable|exists:pegawai,id',
            'nip' => 'required|string|max:20|unique:pegawai,nip',
            'nama' => 'required|string|max:45',
            'karpeg' => 'nullable|string|max:10|unique:pegawai,karpeg',
            'jns_kelamin' => 'required|in:L,P',
            'agama' => 'required|string|max:10',
            'tgl_lahir' => 'required|date|before:today',
            'tpt_lahir' => 'required|string|max:20',
            'telp' => 'required|string|max:15',
            'kode_pos' => 'required|string|max:5',
            'alamat' => 'required|string|max:100',
            'status_kawin' => 'required|string|max:15',
            'suami_istri' => 'nullable|string|max:45',
            'sta_kerja_suami_istri' => 'nullable|string|max:15',
            'jumlah_anak' => 'required|integer|min:0',
            'jns_karyawan' => 'required|in:PNS,CPNS,PPPK',
            'gol_ruang' => 'required|string|max:5',
            'tmt_pegawai' => 'required|date|before_or_equal:today',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'max' => 'Kolom :attribute maksimal :max karakter.',
            'unique' => 'Kolom :attribute sudah digunakan.',
            'exists' => 'Kolom :attribute tidak valid.',
            'in' => 'Kolom :attribute tidak valid.',
            'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
            'before' => 'Kolom :attribute harus sebelum hari ini.',
            'before_or_equal' => 'Kolom :attribute tidak boleh melebihi tanggal hari ini.',
            'integer' => 'Kolom :attribute harus berupa angka bulat.',
            'min' => 'Kolom :attribute minimal :min.',
            'image' => 'Kolom :attribute harus berupa gambar.',
            'mimes' => 'Kolom :attribute harus berformat: :values.',
            'jabatan_id.unique' => 'Jabatan ini sudah terisi oleh pegawai lain.',
        ];
    }

    public function attributes(): array
    {
        return [
            'bidang_id' => 'ID Bidang',
            'nip' => 'NIP',
            'nama' => 'Nama',
            'karpeg' => 'Kartu Pegawai',
            'jns_kelamin' => 'Jenis Kelamin',
            'agama' => 'Agama',
            'tgl_lahir' => 'Tanggal Lahir',
            'tpt_lahir' => 'Tempat Lahir',
            'telp' => 'No. Telepon',
            'kode_pos' => 'Kode Pos',
            'alamat' => 'Alamat',
            'status_kawin' => 'Status Kawin',
            'suami_istri' => 'Nama Pasangan',
            'sta_kerja_suami_istri' => 'Status Kerja Pasangan',
            'jumlah_anak' => 'Jumlah Anak',
            'jns_karyawan' => 'Jenis Karyawan',
            'jabatan_id' => 'Jabatan',
            'atasan_id' => 'Atasan',
            'gol_ruang' => 'Golongan/Ruang',
            'tmt_pegawai' => 'TMT Pegawai',
            'foto' => 'Foto',
        ];
    }
}
