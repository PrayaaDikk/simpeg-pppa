@php
    use App\Helpers\Helper;
@endphp

@extends('layouts.app')

@section('header')
    Detail Pegawai
@endsection

@section('content')
    {{-- Main Content --}}
    <section>

        {{-- BreadCrumb --}}
        <x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate('admin.pegawai.show', $pegawai)" />

        <div class="bg-white p-6 shadow-sm rounded-lg space-y-6">

            <x-admin.pegawai.detail-block title="Informasi Identitas Pegawai">
                <div class="h-24 min-w-16 border-2 bg-white border-muted rounded-lg"></div>

                <div class="space-y-6">
                    <x-ui.detail-item title="Nama Pegawai" :value="$pegawai->nama" />
                    <x-ui.detail-item title="NIP" :value="$pegawai->nip" />
                </div>

                <div class="space-y-6">
                    <x-ui.detail-item title="Jenis Kelamin" :value="$pegawai->jns_kelamin" />
                    <x-ui.detail-item title="Agama" :value="$pegawai->agama" />
                </div>

                <div class="space-y-6">
                    <x-ui.detail-item title="Tempat Lahir" :value="$pegawai->tpt_lahir" />
                    <x-ui.detail-item title="Tanggal Lahir" :value="Helper::formatedDate($pegawai->tgl_lahir)" />
                </div>
            </x-admin.pegawai.detail-block>

            <x-admin.pegawai.detail-block title="Informasi Kontak & Alamat">
                <div class="space-y-6">
                    <x-ui.detail-item title="No. Telepon" :value="$pegawai->telp" />
                    <x-ui.detail-item title="Alamat" :value="$pegawai->alamat" />
                </div>

                <div class="space-y-6">
                    <x-ui.detail-item title="Kode Pos" :value="$pegawai->kode_pos" />
                </div>
            </x-admin.pegawai.detail-block>

            <x-admin.pegawai.detail-block title="Informasi Pendidikan">
                <x-ui.detail-item title="Pendidikan Terakhir" :value="$pendidikanTerakhir ?? 'Tidak ada riwayat pendidikan'" />
            </x-admin.pegawai.detail-block>

            <x-admin.pegawai.detail-block title="Status Pernikahan">
                <div class="space-y-6">
                    <x-ui.detail-item title="Status Kawin" :value="$pegawai->status_kawin" />
                    <x-ui.detail-item title="Status Kerja Pasangan" :value="$pegawai->sta_kerja_suami_istri ?? '-'" />
                </div>

                <div class="space-y-6">
                    <x-ui.detail-item title="Nama Pasangan" :value="$pegawai->suami_istri ?? '-'" />
                    <x-ui.detail-item title="Jumlah Anak" :value="$pegawai->jumlah_anak" />
                </div>
            </x-admin.pegawai.detail-block>

            <x-admin.pegawai.detail-block title="Informasi Kepegawaian & Jabatan">
                <div class="space-y-6">
                    <x-ui.detail-item title="Jenis Pegawai" :value="$pegawai->jns_karyawan" />
                    <x-ui.detail-item title="Golongan/Ruang" :value="$pegawai->gol_ruang" />
                </div>

                <div class="space-y-6">
                    <x-ui.detail-item title="Jabatan" :value="$pegawai->jabatan" />
                    <x-ui.detail-item title="Pangkat" :value="$pegawai->pangkat" />
                </div>
            </x-admin.pegawai.detail-block>

            <x-admin.pegawai.detail-block title="Informasi Pangkat">
                <div class="space-y-6">
                    <x-ui.detail-item title="TMT Pangkat" :value="Helper::formatedDate($pegawai->tmt_pangkat)" />
                    <x-ui.detail-item title="Masa Kerja (Tahun)" :value="$pegawai->masa_kerja_thn" />
                </div>

                <div class="space-y-6">
                    <x-ui.detail-item title="Masa Kerja (Bulan)" :value="$pegawai->masa_kerja_bln" />
                </div>
            </x-admin.pegawai.detail-block>
        </div>

    </section>
@endsection
