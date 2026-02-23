@props(['pegawaiId'])

<form class="max-w-5xl mx-auto" action="{{ route('admin.riwayat-pendidikan.store', $pegawaiId) }}" method="POST"
    enctype="multipart/form-data" x-data="{ loading: false }" @submit="loading = true">
    @csrf

    <input type="number" class="hidden" disabled name="pegawai_id" value="{{ $pegawaiId }}">

    {{-- Tingkat Pendidikan - Jurusan --}}
    <x-input-form-block>
        <x-ui.select name="tingkat" label="Tingkat Pendidikan" :options="[
            ['value' => 'SMA', 'label' => 'SMA'],
            ['value' => 'D1', 'label' => 'D1'],
            ['value' => 'D2', 'label' => 'D2'],
            ['value' => 'D3', 'label' => 'D3'],
            ['value' => 'D4', 'label' => 'D4'],
            ['value' => 'S1', 'label' => 'S1'],
            ['value' => 'S2', 'label' => 'S2'],
            ['value' => 'S3', 'label' => 'S3'],
        ]" required />
        <x-ui.input name="jurusan" label="Jurusan" placeholder="e.g. Psikologi" required />
    </x-input-form-block>

    {{-- Institusi - Tahun Lulus --}}
    <x-input-form-block>
        <x-ui.input name="institusi" label="Institusi" placeholder="e.g. Universitas Gadjah Mada" required />
        <x-ui.input type="number" name="tahun_lulus" label="Tahun Lulus" placeholder="e.g. 2000" required />
    </x-input-form-block>

    {{--  --}}
    <x-input-form-block>
        <x-ui.input name="nomor_ijazah" label="Nomor Ijazah" placeholder="e.g. DN-01/M-SMA/23/0000001" required />
    </x-input-form-block>

    {{-- File Ijazah --}}
    <div class="mb-5">
        <label class="block mb-1.5 text-sm font-medium text-heading" for="file_input">Unggah File Ijazah</label>
        <input
            class="cursor-pointer bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-primary focus:border-primary block w-full shadow-xs placeholder:text-muted"
            id="file_input" type="file" name="ijazah" accept="pdf" />

    </div>

    <button type="submit" :disabled="loading"
        class="text-white bg-primary box-border border border-transparent hover:bg-primary/90 focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
        :class="loading ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'">Tambah</button>
</form>
