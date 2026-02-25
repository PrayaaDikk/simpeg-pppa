@extends('layouts.app')

@section('content')

    {{-- Header --}}
    <x-ui.header :back="route('kgb.index')">
        Informasi Pegawai DP3A Kota Kendari
    </x-ui.header>

    {{-- Breadcrumb --}}
    <x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate('kgb.show', $kgb->id)" />

    <section>
        <div class="bg-white shadow-sm rounded-lg p-8">

            <h2 class="text-2xl font-semibold text-heading mb-8">
                Detail KGB (Kenaikan Gaji Berkala)
            </h2>

            {{-- Informasi Surat --}}
            <x-input-form-block>
                <x-ui.input name="nomor_sk"
                            label="Nomor Surat"
                            :value="$kgb->nomor_sk"
                            disabled />

                <x-ui.input type="date"
                            name="tanggal_surat"
                            label="Tanggal Surat"
                            :value="$kgb->tanggal_surat"
                            disabled />
            </x-input-form-block>

            {{-- Gaji Lama --}}
            <x-input-form-block>
                <x-ui.input name="gaji_lama"
                            label="Gaji Lama"
                            :value="'Rp ' . number_format($kgb->gaji_lama,0,',','.')"
                            disabled />

                <x-ui.input type="date"
                            name="tmt_gaji_lama"
                            label="TMT Gaji Lama"
                            :value="$kgb->tmt_gaji_lama"
                            disabled />
            </x-input-form-block>

            {{-- Gaji Baru --}}
            <x-input-form-block>
                <x-ui.input name="gaji_baru"
                            label="Gaji Baru"
                            :value="'Rp ' . number_format($kgb->gaji_baru,0,',','.')"
                            disabled />

                <x-ui.input type="date"
                            name="tmt_kgb"
                            label="TMT KGB"
                            :value="$kgb->tmt_kgb"
                            disabled />
            </x-input-form-block>

            {{-- KGB Berikutnya --}}
            <x-ui.input type="date"
                        name="kgb_berikutnya"
                        label="KGB Berikutnya"
                        :value="$kgb->kgb_berikutnya"
                        disabled
                        class="mb-6" />

            {{-- Masa Kerja --}}
            <x-input-form-block>
                <x-ui.input name="masa_kerja_lama"
                            label="Masa Kerja Lama"
                            :value="$kgb->masa_kerja_lama"
                            disabled />

                <x-ui.input name="masa_kerja_baru"
                            label="Masa Kerja Baru"
                            :value="$kgb->masa_kerja_baru"
                            disabled />
            </x-input-form-block>

            {{-- Golongan --}}
            <x-input-form-block>
                <x-ui.input name="golongan_lama"
                            label="Golongan Lama"
                            :value="$kgb->golongan_lama"
                            disabled />

                <x-ui.input name="golongan_baru"
                            label="Golongan Baru"
                            :value="$kgb->golongan_baru"
                            disabled />
            </x-input-form-block>

            {{-- Pejabat --}}
            <x-input-form-block>
                <x-ui.input name="pejabat_penetap"
                            label="Pejabat Penetap"
                            :value="$kgb->pejabat_penetap"
                            disabled />

                <x-ui.input name="nip_pejabat"
                            label="NIP Pejabat"
                            :value="$kgb->nip_pejabat"
                            disabled />
            </x-input-form-block>

            {{-- Status --}}
            <x-ui.input name="status_kgb"
                        label="Status"
                        :value="ucfirst($kgb->status_kgb)"
                        disabled
                        class="mb-6" />

            {{-- File SK --}}
            @if($kgb->file_sk)
                <div class="mb-6">
                    <label class="block mb-1.5 text-sm font-medium text-heading">
                        File SK
                    </label>

                    <a href="{{ asset('storage/'.$kgb->file_sk) }}"
                       target="_blank"
                       class="text-primary hover:underline text-sm">
                        Download / Lihat SK
                    </a>
                </div>
            @endif

            {{-- Action Buttons --}}
            <div class="flex gap-4 mt-8">

                <a href="{{ route('kgb.index') }}"
                   class="px-6 py-2 bg-neutral-secondary-medium rounded-base text-sm">
                    Kembali
                </a>

                @if($kgb->status_kgb === 'ditolak')
                    <a href="{{ route('kgb.edit', $kgb->id) }}"
                       class="px-6 py-2 bg-primary text-white rounded-base text-sm">
                        Edit
                    </a>
                @endif

            </div>

        </div>
    </section>

@endsection