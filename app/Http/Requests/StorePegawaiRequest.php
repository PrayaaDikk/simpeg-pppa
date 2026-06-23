<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePegawaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->hasAnyRole(['admin', 'superadmin']);
    }

    /**
     * Lapisan Sanitasi Data sebelum proses validasi berjalan.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'nip' => $this->nip ? trim($this->nip) : null,
            'nama' => $this->nama ? trim($this->nama) : null,
            'email' => $this->email ? strtolower(trim($this->email)) : null,
            'karpeg' => $this->karpeg ? strtoupper(trim($this->karpeg)) : null,
            'no_telp' => $this->no_telp ? trim($this->no_telp) : null,
            'kode_pos' => $this->kode_pos ? trim($this->kode_pos) : null,
        ]);
    }

    public function rules(): array
    {
        return [
            'nip' => 'required|string|unique:pegawai,nip',
            'nama' => 'required|string|max:255',
            'karpeg' => 'nullable|string|max:20',
            'jenis_kelamin' => 'required|in:l,p',
            'agama' => 'required|in:islam,kristen,katolik,hindu,buddha,konghucu',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'no_telp' => 'required|string|max:20',
            'kode_pos' => 'required|string|max:10',
            'alamat' => 'required|string',
            'status_kawin' => 'required|in:belum kawin,kawin,cerai hidup,cerai mati',
            'nama_pasangan' => 'nullable|string|max:255',
            'status_kerja_pasangan' => 'nullable|string|max:255',
            'jumlah_anak' => 'nullable|integer|min:0',
            'jenis_pegawai' => 'required|in:pns,cpns,pppk',
            'pangkat_id' => 'required|exists:pangkat,id',
            'bidang_id' => 'nullable|exists:bidang,id',
            'jabatan_id' => 'nullable|exists:jabatan,id',
            'tmt_pegawai' => 'required|date',
            'is_active' => 'required|boolean',

            'email' => 'nullable|email|unique:users,email',
            'password' => 'nullable|string|min:6',

            'jatah_cuti_dua_tahun_lalu' => 'required|integer|min:0',
            'jatah_cuti_satu_tahun_lalu' => 'required|integer|min:0',
            'jatah_cuti_tahun_ini' => 'required|integer|min:0',
        ];
    }
}
