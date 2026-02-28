@props(['pegawai'])

<form class="max-w-5xl mx-auto" action="{{ route('admin.cuti.store', 1) }}" method="POST" x-data="{ loading: false }"
    @submit="loading = true">
    @csrf

    <x-input-form-block>
        <x-ui.input name="pegawai_id" type="hidden" class="hidden" value="{{ $pegawai->id }}" required />
        <x-ui.input value="{{ $pegawai->nama }}" disabled label="Nama Pegawai" required />
    </x-input-form-block>

    <x-input-form-block>
        <x-ui.select name="jenis_cuti" label="Jenis Cuti" :options="[
            ['value' => 'Tahunan', 'label' => 'Cuti Tahunan'],
            ['value' => 'Besar', 'label' => 'Cuti Besar'],
            ['value' => 'Sakit', 'label' => 'Cuti Sakit'],
            ['value' => 'Melahirkan', 'label' => 'Cuti Melahirkan'],
            ['value' => 'Alasan Penting', 'label' => 'Cuti Alasan Penting'],
            ['value' => 'Diluar Tanggungan Negara', 'label' => 'Cuti Di Luar Tanggungan Negara'],
        ]" required />

        <x-ui.input name="no_telp" label="No. Telepon" placeholder="e.g. 081234567890" required />
    </x-input-form-block>

    <x-input-form-block>
        <x-ui.input type="date" name="tanggal_mulai" label="Tanggal Mulai" placeholder="dd-mm-yyyy" required />
        <x-ui.input type="date" name="tanggal_selesai" label="Tanggal Selesai" placeholder="dd-mm-yyyy" required />
    </x-input-form-block>

    <x-input-form-block>
        <x-ui.input type="number" name="n" label="Sisa Cuti Tahun Ini" placeholder="e.g 12" />
        <x-ui.input type="number" name="n_1" label="Sisa Cuti Tahun Lalu" placeholder="e.g 12" />
        <x-ui.input type="number" name="n_2" label="Sisa Cuti 2 Tahun Lalu" placeholder="e.g 12" />
    </x-input-form-block>

    <x-input-form-block>
        <x-ui.input name="alamat_cuti" label="Alamat Selama Cuti"
            placeholder="e.g. Jl. Malaka 8, Anduonohu, Kec. Poasia, Kota Kendari, Sulawesi Tenggara" required />
    </x-input-form-block>

    <x-input-form-block>
        <x-ui.textarea name="alasan_cuti" label="Alasan Cuti" placeholder="Masukkan alasan cuti" />
    </x-input-form-block>


    <x-input-form-block>
        <x-ui.textarea name="catatan_cuti" label="Catatan Tambahan" placeholder="Masukkan catatan" />
    </x-input-form-block>

    <button type="submit"
        class="text-white bg-primary box-border border border-transparent hover:bg-primary/90 focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
        :class="loading ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'" :disabled="loading">Tambah
        Cuti</button>
</form>
