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
            ['value' => 'tahunan', 'label' => 'Cuti Tahunan'],
            ['value' => 'besar', 'label' => 'Cuti Besar'],
            ['value' => 'sakit', 'label' => 'Cuti Sakit'],
            ['value' => 'melahirkan', 'label' => 'Cuti Melahirkan'],
            ['value' => 'alasan penting', 'label' => 'Cuti Alasan Penting'],
            ['value' => 'di luar tanggungan negara', 'label' => 'Cuti Di Luar Tanggungan Negara'],
        ]" required />
        <x-ui.input type="date" name="tanggal_mulai" label="Tanggal Mulai" placeholder="dd-mm-yyyy" required>
            <x-slot:icon>
                <svg width="64px" height="64px" viewBox="0 0 24 24" fill="none" class="size-5"
                    xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path
                            d="M3 10H21M7 3V5M17 3V5M6.2 21H17.8C18.9201 21 19.4802 21 19.908 20.782C20.2843 20.5903 20.5903 20.2843 20.782 19.908C21 19.4802 21 18.9201 21 17.8V8.2C21 7.07989 21 6.51984 20.782 6.09202C20.5903 5.71569 20.2843 5.40973 19.908 5.21799C19.4802 5 18.9201 5 17.8 5H6.2C5.0799 5 4.51984 5 4.09202 5.21799C3.71569 5.40973 3.40973 5.71569 3.21799 6.09202C3 6.51984 3 7.07989 3 8.2V17.8C3 18.9201 3 19.4802 3.21799 19.908C3.40973 20.2843 3.71569 20.5903 4.09202 20.782C4.51984 21 5.07989 21 6.2 21Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                    </g>
                </svg>
            </x-slot:icon>
        </x-ui.input>
    </x-input-form-block>

    <div>
        <x-input-form-block>
            <x-ui.input type="number" name="" label="Tanggal Selesai" />
        </x-input-form-block>
    </div>

    <x-input-form-block>
        <x-ui.input type="date" name="tanggal_selesai" label="Tanggal Selesai" placeholder="dd-mm-yyyy" required>
            <x-slot:icon>
                <svg width="64px" height="64px" viewBox="0 0 24 24" fill="none" class="size-5"
                    xmlns="http://www.w3.org/2000/svg">
                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                    <g id="SVGRepo_iconCarrier">
                        <path
                            d="M3 10H21M7 3V5M17 3V5M6.2 21H17.8C18.9201 21 19.4802 21 19.908 20.782C20.2843 20.5903 20.5903 20.2843 20.782 19.908C21 19.4802 21 18.9201 21 17.8V8.2C21 7.07989 21 6.51984 20.782 6.09202C20.5903 5.71569 20.2843 5.40973 19.908 5.21799C19.4802 5 18.9201 5 17.8 5H6.2C5.0799 5 4.51984 5 4.09202 5.21799C3.71569 5.40973 3.40973 5.71569 3.21799 6.09202C3 6.51984 3 7.07989 3 8.2V17.8C3 18.9201 3 19.4802 3.21799 19.908C3.40973 20.2843 3.71569 20.5903 4.09202 20.782C4.51984 21 5.07989 21 6.2 21Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                    </g>
                </svg>
            </x-slot:icon>
        </x-ui.input>
        <x-ui.input name="no_telp" label="No. Telepon" placeholder="Masukkan no. telepon" required />
    </x-input-form-block>

    <x-input-form-block>
        <x-ui.input name="alamat_cuti" label="Alamat Selama Cuti" placeholder="Masukkan alamat selama cuti" required />
    </x-input-form-block>

    <x-input-form-block>
        <x-ui.textarea name="alasan_cuti" label="Alasan Cuti" placeholder="Masukkan alasan cuti" required />
    </x-input-form-block>


    <x-input-form-block>
        <x-ui.textarea name="catatan_cuti" label="Catatan Tambahan" placeholder="Masukkan catatan" />
    </x-input-form-block>

    <button type="submit"
        class="text-white bg-primary box-border border border-transparent hover:bg-primary/90 focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
        :class="loading ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'" :disabled="loading">Tambah
        Cuti</button>
</form>
