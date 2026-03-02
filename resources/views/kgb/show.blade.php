@extends('layouts.app')

@section('content')
    {{-- Header --}}
    <x-ui.header :back="route(auth()->user()->routePrefix() . 'kgb.index')">Detail Kenaikan Gaji Berkala</x-ui.header>

    {{-- Breadcrumb --}}
    <x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate(auth()->user()->routePrefix() . 'kgb.show', $kgb)" />

    <section class="space-y-6">
        <x-ui.detail-section>
            {{-- Informasi Identitas --}}
            <x-ui.detail-block title="Informasi Identitas Pegawai">
                <div class="md:col-span-3">
                    <x-ui.photo-display :src="$kgb->pegawai->foto" :alt="$kgb->pegawai->nama" size="lg" />
                </div>

                <x-ui.detail-item title="Nama Pegawai" :value="$kgb->pegawai->nama" />
                <x-ui.detail-item title="NIP" :value="$kgb->pegawai->nip" />
                <x-ui.detail-item title="Pangkat/Gol. Ruang" :value="$kgb->pegawai->pangkat . ', ' . $kgb->pegawai->gol_ruang" />

                <x-ui.detail-item title="Jabatan" :value="$kgb->pegawai->jabatan->nama_jabatan ?? '-'" />

                <x-ui.detail-item title="Unit Kerja"
                    value="Dinas Pemberdayaan Perempuan dan Perlindungan Anak Kota Kendari" />
            </x-ui.detail-block>

            <x-ui.detail-block title="Dasar Penetapan berdasarkan Surat Keputusan Terakhir">
                <x-ui.detail-item title="Oleh Pejabat Penetap" value="Kepala Dinas PP PA" />
                <x-ui.detail-item title="Tanggal" :value="$kgb->sk_tanggal->format('d M Y')" />
                <x-ui.detail-item title="Nomor" :value="$kgb->sk_nomor" />
                <x-ui.detail-item title="Tanggal berlakunya gaji" :value="$kgb->tgl_mulai_gaji_lama->format('d M Y')" />
                <x-ui.detail-item title="Masa Kerja Golongan" :value="$kgb->masa_kerja_tahun_lama . ' Tahun ' . $kgb->masa_kerja_bulan_lama . ' Bulan'" />
            </x-ui.detail-block>

            <x-ui.detail-block title="Kenaikan Gaji Berkala yang Diperoleh">
                <x-ui.detail-item title="Gaji Pokok Baru" :value="'Rp. ' . number_format($kgb->gaji_pokok_baru, 0, ',', '.')" />
                <x-ui.detail-item title="Berdasarkan Masa Kerja" :value="$kgb->masa_kerja_tahun_baru . ' Tahun ' . $kgb->masa_kerja_bulan_baru . ' Bulan'" />
                <x-ui.detail-item title="Nomor" :value="$kgb->sk_nomor" />
                <x-ui.detail-item title="Tanggal berlakunya gaji" :value="$kgb->tgl_mulai_berlaku->format('d M Y')" />
                <x-ui.detail-item title="KGB Yang Akan Datang" :value="$kgb->tgl_kgb_mendatang->format('d M Y')" />
                <x-ui.detail-item title="Status Pegawai ybs" :value="$kgb->status_pegawai" />
            </x-ui.detail-block>

            <x-ui.detail-block title="Informasi Status KGB">
                <div>
                    <dt class="text-sm font-medium text-gray-500 mb-1">Status KGB</dt>
                    <span
                        class="{{ \App\Constants\Cuti::STATUS_COLOR[$kgb->status_kgb] . ' inline-flex items-center px-2 py-1 ring-1 ring-inset text-sm font-medium rounded w-fit' }}">{{ $kgb->status_kgb }}</span>
                </div>
            </x-ui.detail-block>

            @if (auth()->user()->role === 'admin')
                {{-- Action Buttons --}}
                <div
                    class="flex items-center flex-wrap justify-end max-xs:justify-start gap-3 pt-6 border-t border-gray-200">

                    @if ($kgb->status_kgb === 'Ditolak' || auth()->user()->role === 'admin')
                        <a href="{{ route(auth()->user()->routePrefix() . 'kgb.edit', $kgb->id) }}"
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
                    @endif

                    <x-ui.delete-button :action="route('admin.kgb.destroy', $kgb)" title="Hapus"
                        message="Apakah Anda yakin ingin menghapus data kenaikan gaji berkala?" confirmText="Ya, hapus"
                        cancelText="Tidak, batalkan" />
                </div>
            @endif
        </x-ui.detail-section>
    </section>
@endsection
