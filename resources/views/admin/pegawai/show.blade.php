@extends('layouts.app')

@section('header')
    Detail Pegawai
@endsection

@section('content')
    <section class="space-y-6">
        {{-- Breadcrumb --}}
        <x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate('admin.pegawai.show', $pegawai)" />

        {{-- Main Content --}}
        <x-ui.detail-section>
            {{-- Informasi Identitas --}}
            <x-admin.pegawai.detail-block title="Informasi Identitas Pegawai">
                <div class="md:col-span-3">
                    <x-ui.photo-display :src="$pegawai->foto" :alt="$pegawai->nama" size="lg" />
                </div>

                <x-ui.detail-item title="Nama Pegawai" :value="$pegawai->nama" />
                <x-ui.detail-item title="NIP" :value="$pegawai->nip" />
                <x-ui.detail-item title="No. Karpeg" :value="$pegawai->karpeg" />

                <x-ui.detail-item title="Jenis Kelamin" :value="$pegawai->jns_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'" />
                <x-ui.detail-item title="Agama" :value="$pegawai->agama" />
                <x-ui.detail-item title="Usia" :value="$pegawai->usia . ' tahun'" />

                <x-ui.detail-item title="Tempat Lahir" :value="$pegawai->tpt_lahir" />
                <x-ui.detail-item title="Tanggal Lahir" :value="\App\Helpers\Helper::formatedDate($pegawai->tgl_lahir)" />
            </x-admin.pegawai.detail-block>

            {{-- Informasi Kontak & Alamat --}}
            <x-admin.pegawai.detail-block title="Informasi Kontak & Alamat">
                <x-ui.detail-item title="No. Telepon" :value="$pegawai->telp" />
                <x-ui.detail-item title="Kode Pos" :value="$pegawai->kode_pos" />
                <div class="md:col-span-2">
                    <x-ui.detail-item title="Alamat" :value="$pegawai->alamat" />
                </div>
            </x-admin.pegawai.detail-block>

            {{-- Informasi Pendidikan --}}
            <x-admin.pegawai.detail-block title="Informasi Pendidikan">
                <x-ui.detail-item title="Pendidikan Terakhir" :value="$pegawai->pendidikan ?? 'Tidak ada riwayat pendidikan'" />
            </x-admin.pegawai.detail-block>

            {{-- Status Pernikahan --}}
            <x-admin.pegawai.detail-block title="Status Pernikahan">
                <x-ui.detail-item title="Status Kawin" :value="$pegawai->status_kawin" />
                <x-ui.detail-item title="Nama Pasangan" :value="$pegawai->suami_istri" />
                <x-ui.detail-item title="Status Kerja Pasangan" :value="$pegawai->sta_kerja_suami_istri" />
                <x-ui.detail-item title="Jumlah Anak" :value="$pegawai->jumlah_anak . ' orang'" />
            </x-admin.pegawai.detail-block>

            {{-- Informasi Kepegawaian & Jabatan --}}
            <x-admin.pegawai.detail-block title="Informasi Kepegawaian & Jabatan">
                <x-ui.detail-item title="Jenis Pegawai" :value="$pegawai->jns_karyawan" />
                <x-ui.detail-item title="Bidang" :value="$pegawai->bidang->nama" />
                <x-ui.detail-item title="Jabatan" :value="$pegawai->jabatan" />
                <x-ui.detail-item title="Golongan/Ruang" :value="$pegawai->gol_ruang" />
                <x-ui.detail-item title="Pangkat" :value="$pegawai->pangkat" />
            </x-admin.pegawai.detail-block>

            {{-- Informasi Masa Kerja --}}
            <x-admin.pegawai.detail-block title="Informasi Masa Kerja">
                <x-ui.detail-item title="TMT Pangkat" :value="\App\Helpers\Helper::formatedDate($pegawai->tmt_pangkat)" />
                <x-ui.detail-item title="Masa Kerja" :value="$pegawai->masa_kerja_thn . ' Tahun ' . $pegawai->masa_kerja_bln . ' Bulan'" />
            </x-admin.pegawai.detail-block>

            {{-- Action Buttons --}}
            <div class="flex items-center flex-wrap justify-end max-xs:justify-start gap-3 pt-6 border-t border-gray-200">
                <a
                    class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-brand rounded-lg hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium transition-colors">
                    <svg class="w-4 h-4" width="64px" height="64px" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                        <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                        <g id="SVGRepo_iconCarrier">
                            <path fill-rule="evenodd" clip-rule="evenodd"
                                d="M20.8477 1.87868C19.6761 0.707109 17.7766 0.707105 16.605 1.87868L2.44744 16.0363C2.02864 16.4551 1.74317 16.9885 1.62702 17.5692L1.03995 20.5046C0.760062 21.904 1.9939 23.1379 3.39334 22.858L6.32868 22.2709C6.90945 22.1548 7.44285 21.8693 7.86165 21.4505L22.0192 7.29289C23.1908 6.12132 23.1908 4.22183 22.0192 3.05025L20.8477 1.87868ZM18.0192 3.29289C18.4098 2.90237 19.0429 2.90237 19.4335 3.29289L20.605 4.46447C20.9956 4.85499 20.9956 5.48815 20.605 5.87868L17.9334 8.55027L15.3477 5.96448L18.0192 3.29289ZM13.9334 7.3787L3.86165 17.4505C3.72205 17.5901 3.6269 17.7679 3.58818 17.9615L3.00111 20.8968L5.93645 20.3097C6.13004 20.271 6.30784 20.1759 6.44744 20.0363L16.5192 9.96448L13.9334 7.3787Z"
                                fill="currentColor"></path>
                        </g>
                    </svg>
                    Edit
                </a>

                <x-ui.delete-button :action="route('admin.pegawai.destroy', $pegawai)" title="Hapus Pegawai"
                    message="Apakah Anda yakin ingin menghapus data pegawai {{ $pegawai->nama }}?" confirmText="Ya, hapus"
                    cancelText="Tidak, batalkan" />
            </div>
        </x-ui.detail-section>
    </section>
@endsection
