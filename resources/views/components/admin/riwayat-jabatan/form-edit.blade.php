@props(['riwayatPangkat'])

<form class="max-w-5xl mx-auto" action="{{ route('admin.riwayat-jabatan.update', $riwayatJabatan->id) }}" method="POST"
    enctype="multipart/form-data" x-data="{ loading: false }" @submit="loading = true">
    @csrf
    @method('PUT')

    {{-- TMT Pangkat - Nama Jabatan --}}
    <x-input-form-block>
        <x-ui.input type="date" name="tmt_jabatan" label="TMT Jabatan" placeholder="dd-mm-yyyy" :value="$riwayatJabatan->tmt_jabatan->format('Y-m-d')"
            required />
        <x-ui.input name="nama_jabatan" label="Nama Jabatan" placeholder="e.g. Pengurus Bidang" :value="$riwayatJabatan->nama_jabatan"
            required />
    </x-input-form-block>

    {{-- Nomor SK - Tanggal SK --}}
    <x-input-form-block>
        <x-ui.input name="nomor_sk" label="Nomor SK" placeholder="e.g. SK.01/DIR-UT/X/2023" :value="$riwayatJabatan->nomor_sk"
            required />
        <x-ui.input type="date" name="tanggal_sk" label="Tanggal SK" placeholder="dd-mm-yyyy" :value="$riwayatJabatan->tanggal_sk->format('Y-m-d')"
            required />
    </x-input-form-block>

    {{-- File SK --}}
    <div class="mb-5">
        <label class="block mb-1.5 text-sm font-medium text-heading" for="file_input">Unggah File SK</label>

        @if ($riwayatJabatan->file_sk)
            <div class="mb-3">
                <p class="text-sm text-gray-500 mt-1 hover:underline"><a
                        href="{{ route('riwayat-jabatan.showFile', $riwayatJabatan->file_sk) }}" target="_blank">Lihat
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
        <a href="{{ route('admin.riwayat-jabatan.show', $riwayatJabatan) }}"
            class="cursor-pointer text-gray-700 bg-gray-100 box-border border border-transparent hover:bg-gray-200 focus:ring-4 focus:ring-gray-300 shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
            Batal
        </a>
    </div>
</form>
