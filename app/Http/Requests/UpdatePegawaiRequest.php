<?php

namespace App\Http\Requests;

use App\Models\Jabatan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePegawaiRequest extends FormRequest
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
        // 1. AMBIL ID DARI ROUTE {id}
        // Karena route kamu: /{id}, maka panggil 'id'
        $pegawaiId = $this->route('id');

        // 2. Ambil data singleton
        $inputJabatanId = $this->input('jabatan_id');
        $isSingleton = false;

        if ($inputJabatanId) {
            $isSingleton = Jabatan::where('id', $inputJabatanId)
                ->where('is_singleton', true)
                ->exists();
        }

        return [
            'bidang_id' => ['nullable', 'exists:bidang,id'],
            'jabatan_id' => [
                'required',
                'exists:jabatan,id',
                // Jika jabatan ini hanya untuk 1 orang, cek apakah sudah dipakai ORANG LAIN
                $isSingleton ? Rule::unique('pegawai', 'jabatan_id')->ignore($pegawaiId, 'id') : '',
            ],
            'nip' => [
                'required',
                'string',
                'max:50',
                // Sekarang $pegawaiId tidak null, ignore akan bekerja
                Rule::unique('pegawai', 'nip')->ignore($pegawaiId),
            ],
            'nama' => ['required', 'string', 'max:45'],
            'karpeg' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('pegawai', 'karpeg')->ignore($pegawaiId),
            ],
            'jns_kelamin' => ['required', 'in:L,P'],
            'agama' => ['required', 'string', 'max:10'],
            'tgl_lahir' => ['required', 'date', 'before:today'],
            'tpt_lahir' => ['required', 'string', 'max:20'],
            'telp' => ['required', 'string', 'max:15'],
            'kode_pos' => ['required', 'string', 'max:5'],
            'alamat' => ['required', 'string', 'max:100'],
            'status_kawin' => ['required', 'string', 'max:15'],
            'suami_istri' => ['nullable', 'string', 'max:255'],
            'sta_kerja_suami_istri' => ['nullable', 'string', 'max:20'],
            'jumlah_anak' => ['required', 'integer', 'min:0'],
            'jns_karyawan' => ['required', 'in:PNS,CPNS,PPPK'],
            'gol_ruang' => ['required', 'string', 'max:5'],
            'tmt_pegawai' => ['required', 'date', 'before_or_equal:today'],
            'foto' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'kuota_cuti' => ['required', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
            'keterangan' => ['nullable', 'string', 'max:150'],
        ];
    }



    public function messages()
    {
        return [
            'required' => 'Kolom :attribute wajib diisi.',
            'max' => 'Kolom :attribute maksimal :max karakter.',
            'unique' => 'Kolom :attribute sudah digunakan.',
            'exists' => 'Kolom :attribute tidak valid.',
            'in' => 'Kolom :attribute tidak valid.',
            'date' => 'Kolom :attribute harus berupa tanggal yang valid.',
            'integer' => 'Kolom :attribute harus berupa angka bulat.',
            'min' => 'Kolom :attribute minimal :min.',
            'image' => 'Kolom :attribute harus berupa gambar.',
            'mimes' => 'Kolom :attribute harus berformat: :values.',
            'jabatan_id.unique' => 'Jabatan ini hanya dapat memiliki satu pegawai.',
        ];
    }

    public function attributes()
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
            'gol_ruang' => 'Golongan/Ruang',
            'tmt_pangkat' => 'TMT Pangkat',
            'foto' => 'Foto',
        ];
    }
}
