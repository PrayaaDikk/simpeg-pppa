@props(['riwayatPendidikan'])

<form class="max-w-5xl mx-auto" action="{{ route('admin.riwayat-pendidikan.update', $riwayatPendidikan->id) }}"
    method="POST" enctype="multipart/form-data" x-data="{ loading: false }" @submit="loading = true">
    @csrf
    @method('PUT')

    {{-- Tingkat - Jurusan --}}
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
        ]" :value="old('tingkat', $riwayatPendidikan->tingkat)" required />
        <x-ui.input name="jurusan" label="Jurusan" placeholder="e.g. Psikologi" :value="old('jurusan', $riwayatPendidikan->jurusan)" required />
    </x-input-form-block>

    {{-- Institusi - Tahun Lulus --}}
    <x-input-form-block>
        <x-ui.input name="institusi" label="Institusi" placeholder="e.g. Unversitas Gadjah Mada" :value="old('institusi', $riwayatPendidikan->institusi)"
            required />
        <x-ui.input name="tahun_lulus" label="Tahun Lulus" placeholder="e.g. 2000" type="number" :value="old('tahun_lulus', $riwayatPendidikan->tahun_lulus)"
            required />
    </x-input-form-block>

    {{-- Nomor Ijazah --}}
    <x-input-form-block>
        <x-ui.input name="nomor_ijazah" label="Nomor Ijazah" placeholder="e.g. DN-01/M-SMA/23/0000001" :value="old('nomor_ijazah', $riwayatPendidikan->nomor_ijazah)"
            required />
    </x-input-form-block>

    {{-- File Ijazah --}}
    <div class="mb-5">
        <label class="block mb-1.5 text-sm font-medium text-heading" for="file_input">Unggah Ijazah</label>

        @if ($riwayatPendidikan->ijazah)
            <div class="mb-3">
                <img src="{{ asset('storage/ijazah' . $riwayatPendidikan->ijazah) }}"
                    alt="{{ $riwayatPendidikan->institusi }}"
                    class="h-32 w-32 object-cover rounded-lg border-2 border-gray-300">
                <p class="text-xs text-gray-500 mt-1">Ijazah saat ini</p>
            </div>
        @endif

        <input
            class="cursor-pointer bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-primary focus:border-primary block w-full shadow-xs placeholder:text-muted hover:file:bg-blue-100"
            id="file_input" type="file" name="ijazah" accept="pdf" />
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
        <a href="{{ route('admin.riwayat-pendidikan.show', $riwayatPendidikan) }}"
            class="cursor-pointer text-gray-700 bg-gray-100 box-border border border-transparent hover:bg-gray-200 focus:ring-4 focus:ring-gray-300 shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
            Batal
        </a>
    </div>
</form>
