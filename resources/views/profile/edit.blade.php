@extends('layouts.admin-app')

@section('content')
    {{-- Header --}}
    <x-ui.header :back="route('admin.dashboard')">Profil Pegawai</x-ui.header>

    {{-- BreadCrumb --}}
    {{-- <x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate('admin.riwayat-jabatan.index', $pegawaiId)" />/ --}}

    {{-- Main Content --}}
    <section>
        {{-- <div class="bg-white shadow-sm rounded-bl-lg rounded-br-lg "> --}}

        @include('layouts.navigation')

        <div class="py-12">


            <div class="space-y-6">
                <div class="bg-white">
                    <x-ui.detail-block title="Informasi Identitas Pegawai">
                        <div class="md:col-span-3">
                            <x-ui.photo-display :src="$user->pegawai->foto" :alt="$user->pegawai->nama" size="lg" />
                        </div>

                        <x-ui.detail-item title="Nama Pegawai" :value="$user->pegawai->nama" />
                        <x-ui.detail-item title="NIP" :value="$user->pegawai->nip" />
                        <x-ui.detail-item title="No. Karpeg" :value="$user->pegawai->karpeg" />

                        <x-ui.detail-item title="Jenis Kelamin" :value="$user->pegawai->jns_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'" />
                        <x-ui.detail-item title="Agama" :value="$user->pegawai->agama" />
                        <x-ui.detail-item title="Usia" :value="$user->pegawai->calculateAge() . ' tahun'" />

                        <x-ui.detail-item title="Tempat Lahir" :value="$user->pegawai->tpt_lahir" />
                        <x-ui.detail-item title="Tanggal Lahir" :value="$user->pegawai->tgl_lahir->format('d M Y')" />

                        <x-ui.detail-item-link title="Detail Profil" :link="route('admin.pegawai.show', $user->pegawai)" value="Lihat detail" />
                    </x-ui.detail-block>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <div class="max-w-xl">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
