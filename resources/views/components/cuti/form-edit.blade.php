@props(['cuti', 'pegawai'])

<form class="max-w-5xl mx-auto" action="{{ route(auth()->user()->routePrefix() . 'cuti.update', $cuti->id) }}"
    method="POST" x-data="{ loading: false }" @submit="loading = true">
    @csrf
    @method('PUT')

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
        ]" required :value="old('jenis_cuti', $cuti->jenis_cuti)" />
        <x-ui.input name="no_telp" label="No. Telepon" placeholder="Masukkan no. telepon" :value="old('no_telp', $cuti->no_telp)" required />

    </x-input-form-block>

    <x-input-form-block>
        <x-ui.input type="date" name="tanggal_mulai" label="Tanggal Mulai" placeholder="dd-mm-yyyy" :value="old('tanggal_mulai', $cuti->tanggal_mulai->format('Y-m-d'))"
            required />
        <x-ui.input type="date" name="tanggal_selesai" label="Tanggal Selesai" placeholder="dd-mm-yyyy"
            :value="old('tanggal_selesai', $cuti->tanggal_selesai->format('Y-m-d'))" required />
    </x-input-form-block>

    <x-input-form-block>
        <x-ui.input type="number" name="n" label="Sisa Cuti Tahun Ini" placeholder="e.g 12" :value="old('n', $cuti->n)" />
        <x-ui.input type="number" name="n_1" label="Sisa Cuti Tahun Lalu" placeholder="e.g 12" :value="old('n_1', $cuti->n_1)" />
        <x-ui.input type="number" name="n_2" label="Sisa Cuti 2 Tahun Lalu" placeholder="e.g 12"
            :value="old('n_2', $cuti->n_2)" />
    </x-input-form-block>

    <x-input-form-block>
        <x-ui.input name="alamat_cuti" label="Alamat Selama Cuti" placeholder="Masukkan alamat selama cuti"
            :value="old('alamat_cuti', $cuti->alamat_cuti)" required />
    </x-input-form-block>

    <x-input-form-block>
        <x-ui.textarea name="alasan_cuti" label="Alasan Cuti" placeholder="Masukkan alasan cuti" :value="old('alasan_cuti', $cuti->alasan_cuti)" />
    </x-input-form-block>


    <x-input-form-block>
        <x-ui.textarea name="catatan_cuti" label="Catatan Tambahan" placeholder="Masukkan catatan" :value="old('catatan_cuti', $cuti->catatan_cuti)" />
    </x-input-form-block>

    @if (auth()->user()->role === 'admin')
        <x-input-form-block>
            <x-ui.select name="keputusan_atasan" label="Keputusan Atasan" placeholder="e.g Menunggu" :options="[
                ['value' => 'Menunggu', 'label' => 'Menunggu'],
                ['value' => 'Disetujui', 'label' => 'Disetujui'],
                ['value' => 'Perubahan', 'label' => 'Perubahan'],
                ['value' => 'Ditangguhkan', 'label' => 'Ditangguhkan'],
                ['value' => 'Tidak disetujui', 'label' => 'Tidak disetujui'],
            ]"
                required :value="old('keputusan_atasan', $cuti->keputusan_atasan)" />
            <x-ui.select name="status_cuti" label="Status Cuti" placeholder="e.g Menunggu" :options="[
                ['value' => 'Menunggu', 'label' => 'Menunggu'],
                ['value' => 'Disetujui', 'label' => 'Disetujui'],
                ['value' => 'Perubahan', 'label' => 'Perubahan'],
                ['value' => 'Ditangguhkan', 'label' => 'Ditangguhkan'],
                ['value' => 'Tidak disetujui', 'label' => 'Tidak disetujui'],
            ]" required
                :value="old('status_cuti', $cuti->status_cuti)" />
        </x-input-form-block>
    @endif

    {{-- Buttons --}}
    <div class="flex items-center gap-3">
        <button type="submit" :disabled="loading"
            class="text-white bg-primary box-border border border-transparent hover:bg-primary/90 focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
            :class="loading ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'">
            Simpan Perubahan
        </button>
        <a href="{{ route(auth()->user()->routePrefix() . 'cuti.edit', $cuti) }}"
            class="cursor-pointer text-gray-700 bg-gray-100 box-border border border-transparent hover:bg-gray-200 focus:ring-4 focus:ring-gray-300 shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
            Batal
        </a>
    </div>
</form>
