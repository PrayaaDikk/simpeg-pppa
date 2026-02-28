@extends('layouts.admin-app')

@section('content')
    {{-- Header --}}
    <x-ui.header :back="route('admin.cuti.index')">Detail Cuti</x-ui.header>

    {{-- Breadcrumb --}}
    <x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate('admin.cuti.show', $cuti)" />

    <section class="space-y-6">
        <x-ui.detail-section>
            {{-- Informasi Identitas --}}
            <x-ui.detail-block title="Informasi Identitas Pegawai">
                <div class="md:col-span-3">
                    <x-ui.photo-display :src="$cuti->pegawai->foto" :alt="$cuti->pegawai->nama" size="lg" />
                </div>

                <x-ui.detail-item title="Nama Pegawai" :value="$cuti->pegawai->nama" />
                <x-ui.detail-item title="NIP" :value="$cuti->pegawai->nip" />
                <x-ui.detail-item title="No. Karpeg" :value="$cuti->pegawai->karpeg" />

                <x-ui.detail-item title="Jenis Kelamin" :value="$cuti->pegawai->jns_kelamin === 'L' ? 'Laki-laki' : 'Perempuan'" />
                <x-ui.detail-item title="Agama" :value="$cuti->pegawai->agama" />
                <x-ui.detail-item title="Usia" :value="$cuti->pegawai->calculateAge() . ' tahun'" />

                <x-ui.detail-item title="Tempat Lahir" :value="$cuti->pegawai->tpt_lahir" />
                <x-ui.detail-item title="Tanggal Lahir" :value="$cuti->pegawai->tgl_lahir->format('d M Y')" />
            </x-ui.detail-block>

            {{-- Informasi Cuti --}}
            <x-ui.detail-block title="Informasi Cuti">
                <x-ui.detail-item title="Jenis Cuti" :value="$cuti->jenis_cuti" />
                <x-ui.detail-item title="Alamat Selama Menjalankan Cuti" :value="$cuti->alamat_cuti" />
                <x-ui.detail-item title="No. Telpon" :value="$cuti->no_telp" />

                <div class="md:col-span-3">
                    <x-ui.detail-item title="Alasan Cuti" :value="$cuti->alasan_cuti" />
                </div>
            </x-ui.detail-block>

            {{-- Informasi Lama Cuti --}}
            <x-ui.detail-block title="Informasi Lama Cuti">
                <x-ui.detail-item title="Tanggal Mulai Cuti" :value="$cuti->tanggal_mulai->format('d M Y')" />
                <x-ui.detail-item title="Tanggal Akhir Cuti" :value="$cuti->tanggal_selesai->format('d M Y')" />
                <x-ui.detail-item title="Lama Cuti" :value="$cuti->lama_cuti . ' hari'" />
            </x-ui.detail-block>

            {{-- Action Buttons --}}
            <div class="flex items-center flex-wrap justify-end max-xs:justify-start gap-3 pt-6 border-t border-gray-200">
                <a href="{{ route('admin.cuti.edit', $cuti->id) }}"
                    class=" inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-brand rounded-lg hover:bg-brand-strong focus:ring-4 focus:ring-brand-medium transition-colors">
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

                <x-ui.delete-button :action="route('admin.cuti.destroy', $cuti)" title="Hapus Riwayat"
                    message="Apakah Anda yakin ingin menghapus data riwayat pangkat?" confirmText="Ya, hapus"
                    cancelText="Tidak, batalkan" />
            </div>
        </x-ui.detail-section>
    </section>
@endsection
