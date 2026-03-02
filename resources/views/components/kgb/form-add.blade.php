@props(['pegawai', 'pangkat'])

<form class="max-w-5xl mx-auto" action="{{ route(auth()->user()->routePrefix() . 'kgb.store', $pegawai->id) }}"
    method="POST" x-data="{ loading: false }" @submit="loading = true">
    @csrf

    <x-input-form-block>
        <x-ui.input name="pegawai_id" type="hidden" class="hidden" value="{{ $pegawai->id }}" required />
        <x-ui.input value="{{ $pegawai->nama }}" disabled label="Nama Pegawai" required />
    </x-input-form-block>

    <x-input-form-block>
        <x-ui.input name="nomor_surat" label="Nomor Surat" placeholder="e.g. SK.01/DIR-UT/X/2023" required />
    </x-input-form-block>

    <x-input-form-block>
        <x-ui.input type="number" name="gaji_pokok_lama" label="Gaji Pokok Lama" placeholder="e.g. 3878500" required />
    </x-input-form-block>

    <div>
        <p class="text-gray-500 text-sm">Surat Keputusan Terakhir</p>
        <div class="h-px bg-gray-200 mt-1 mb-2"></div>
        <x-input-form-block>
            <x-ui.input type="date" name="sk_tanggal" label="Tanggal Surat" placeholder="dd-mm-yyyy" />
            <x-ui.input name="sk_nomor" label="Nomor Surat" placeholder="e.g. SK.01/DIR-UT/X/2023" required />
            <x-ui.input type="date" name="tgl_mulai_gaji_lama" label="Tanggal Masa Berlaku Gaji"
                placeholder="dd-mm-yyyy" required />
        </x-input-form-block>

        <x-input-form-block>
            <x-ui.input name="masa_kerja_tahun_lama" label="Masa Kerja Tahun Lama" placeholder="e.g. 12" required />
            <x-ui.input name="masa_kerja_bulan_lama" label="Masa Kerja Bulan Lama" placeholder="e.g. 12" required />
        </x-input-form-block>
    </div>

    <div>
        <p class="text-gray-500 text-sm">Kenaikan Gaji Berkala yang Diperoleh</p>
        <div class="h-px bg-gray-200 mt-1 mb-2"></div>
        <x-input-form-block>
            <x-ui.input type="number" name="gaji_pokok_baru" label="Gaji Pokok Baru" placeholder="e.g. 3878500"
                required />
        </x-input-form-block>

        <x-input-form-block>
            <x-ui.input name="masa_kerja_tahun_baru" label="Masa Kerja Tahun Baru" placeholder="e.g. 12" required />
            <x-ui.input name="masa_kerja_bulan_baru" label="Masa Kerja Bulan Baru" placeholder="e.g. 12" required />
        </x-input-form-block>

        <x-input-form-block>
            <x-ui.select name="gol_ruang_baru" label="Dalam Golongan" :options="$pangkat
                ->map(function ($item) {
                    return ['value' => $item->golongan, 'label' => $item->nama_pangkat . ', ' . $item->golongan];
                })
                ->toArray()" required />
        </x-input-form-block>

        <x-input-form-block>
            <x-ui.input type="date" name="tgl_mulai_berlaku" label="Mulai Tanggal" placeholder="dd-mm-yyyy" />
            <x-ui.input type="date" name="tgl_kgb_mendatang" label="KGB Yang Akan Datang" placeholder="dd-mm-yyyy"
                required />
            <x-ui.input name="status_pegawai" label="Status Pegawai" placeholder="e.g. PNSD" required />
        </x-input-form-block>
    </div>

    <button type="submit"
        class="text-white bg-primary box-border border border-transparent hover:bg-primary/90 focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
        :class="loading ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'" :disabled="loading">Tambah
        KGB</button>
</form>
