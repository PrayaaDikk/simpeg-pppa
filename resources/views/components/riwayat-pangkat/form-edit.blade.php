@props(['riwayatPangkat', 'pangkat'])

<form class="max-w-5xl mx-auto" action="{{ route('admin.riwayat-pangkat.update', $riwayatPangkat->id) }}" method="POST"
    enctype="multipart/form-data" x-data="{ loading: false }" @submit="loading = true">
    @csrf
    @method('PUT')

    {{-- Pangkat - TMT Pangkat --}}
    <x-input-form-block>
        <x-ui.select name="pangkat_id" label="Pangkat" :options="$pangkat
            ->map(function ($item) {
                return ['value' => $item->id, 'label' => $item->golongan . ' - ' . $item->nama_pangkat];
            })
            ->toArray()" :value="$riwayatPangkat->pangkat_id" required />
        <x-ui.input type="date" name="tmt_pangkat" label="TMT Pangkat" placeholder="dd-mm-yyyy" :value="$riwayatPangkat->tmt_pangkat->format('Y-m-d')"
            required />
    </x-input-form-block>

    {{-- Nomor SK - tanggal Input --}}
    <x-input-form-block>
        <x-ui.input name="nomor_sk" label="Nomor SK" placeholder="e.g. SK.01/DIR-UT/X/2023" :value="$riwayatPangkat->nomor_sk"
            required />
        <x-ui.input type="date" name="tanggal_input" label="Tanggal Input" placeholder="dd-mm-yyyy" :value="$riwayatPangkat->tanggal_input->format('Y-m-d')"
            required />
    </x-input-form-block>

    {{-- File SK --}}
    <div class="mb-5">
        <label class="block mb-1.5 text-sm font-medium text-heading" for="file_input">Unggah File SK</label>

        @if ($riwayatPangkat->file_sk)
            <div class="mb-3">
                <p class="text-sm text-gray-500 mt-1 hover:underline"><a
                        href="{{ route('riwayat-pangkat.showFile', $riwayatPangkat->file_sk) }}" target="_blank">Lihat
                        file sebelumnya</a></p>
            </div>
        @endif

        <input
            class="cursor-pointer bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-primary focus:border-primary block w-full shadow-xs placeholder:text-muted hover:file:bg-blue-100"
            id="file_input" type="file" name="file_sk" accept="pdf" />
        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah file.
        </p>
    </div>

    {{-- Buttons --}}
    <div class="flex items-center gap-3">
        <button type="submit" :disabled="loading"
            class="text-white bg-primary box-border border border-transparent hover:bg-primary/90 focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
            :class="loading ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'">
            Simpan Perubahan
        </button>
        <a href="{{ route('admin.riwayat-pangkat.show', $riwayatPangkat) }}"
            class="cursor-pointer text-gray-700 bg-gray-100 box-border border border-transparent hover:bg-gray-200 focus:ring-4 focus:ring-gray-300 shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
            Batal
        </a>
    </div>
</form>
