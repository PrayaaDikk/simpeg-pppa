@props(['pegawaiId', 'pangkat'])

<form class="max-w-5xl mx-auto" action="{{ route('admin.riwayat-pangkat.store', $pegawaiId) }}" method="POST"
    enctype="multipart/form-data" x-data="{ loading: false }" @submit="loading = true">
    @csrf

    <input type="number" class="hidden" disabled name="pegawai_id" value="{{ $pegawaiId }}">

    {{-- Pangkat - TMT Pangkat --}}
    <x-input-form-block>
        <x-ui.select name="pangkat_id" label="Pangkat" :options="$pangkat
            ->map(function ($item) {
                return ['value' => $item->id, 'label' => $item->golongan . ' - ' . $item->nama_pangkat];
            })
            ->toArray()" required />
        <x-ui.input type="date" name="tmt_pangkat" label="TMT Pangkat" placeholder="dd-mm-yyyy" required />
    </x-input-form-block>

    {{-- Nomor SK - tanggal Input --}}
    <x-input-form-block>
        <x-ui.input name="nomor_sk" label="Nomor SK" placeholder="e.g. SK.01/DIR-UT/X/2023" required />
        <x-ui.input type="date" name="tanggal_input" label="Tanggal Input" placeholder="dd-mm-yyyy" required />
    </x-input-form-block>

    {{-- File SK --}}
    <div class="mb-5">
        <label class="block mb-1.5 text-sm font-medium text-heading" for="file_input">Unggah File SK <span
                class="text-red-500">*</span></label>
        <input
            class="cursor-pointer bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-primary focus:border-primary block w-full shadow-xs placeholder:text-muted"
            id="file_input" type="file" name="file_sk" accept="pdf" required />
    </div>

    <button type="submit" :disabled="loading"
        class="text-white bg-primary box-border border border-transparent hover:bg-primary/90 focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
        :class="loading ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'">Tambah</button>
</form>
