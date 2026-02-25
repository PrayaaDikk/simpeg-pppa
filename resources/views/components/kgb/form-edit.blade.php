@props([
    'golonganOptions',
    'kgb' => null
])

<form
    action="{{ $kgb ? route('kgb.update',$kgb->id) : route('kgb.store') }}"
    method="POST"
    enctype="multipart/form-data"
    x-data="{ loading: false, openModal: false }">

    @csrf
    @if($kgb)
        @method('PUT')
    @endif

    {{-- Nomor Surat & Tanggal Surat --}}
    <x-input-form-block>
        <x-ui.input
            name="nomor_sk"
            label="Nomor Surat"
            placeholder="Ex: 821.4/015/KGB/2025"
            :value="old('nomor_sk', $kgb->nomor_sk ?? '')"
            required />

        <x-ui.input
            type="date"
            name="tanggal_surat"
            label="Tanggal Surat"
            :value="old('tanggal_surat', $kgb->tanggal_surat ?? '')"
            required>
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="size-5 cursor-pointer text-body date-picker-icon"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10m-13 9h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v11a2 2 0 002 2z"/>
                </svg>
            </x-slot:icon>
        </x-ui.input>
    </x-input-form-block>

    {{-- Gaji Lama & TMT Gaji Lama --}}
    <x-input-form-block>
        <x-ui.input
            type="number"
            name="gaji_lama"
            label="Gaji Lama"
            :value="old('gaji_lama', $kgb->gaji_lama ?? '')"
            required />

        <x-ui.input
            type="date"
            name="tmt_gaji_lama"
            label="TMT Gaji Lama"
            :value="old('tmt_gaji_lama', $kgb->tmt_gaji_lama ?? '')"
            required>
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="size-5 cursor-pointer text-body date-picker-icon"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10m-13 9h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v11a2 2 0 002 2z"/>
                </svg>
            </x-slot:icon>
        </x-ui.input>
    </x-input-form-block>

    {{-- Gaji Baru & TMT KGB --}}
    <x-input-form-block>
        <x-ui.input
            type="number"
            name="gaji_baru"
            label="Gaji Baru"
            :value="old('gaji_baru', $kgb->gaji_baru ?? '')"
            required />

        <x-ui.input
            type="date"
            name="tmt_kgb"
            label="TMT KGB"
            :value="old('tmt_kgb', $kgb->tmt_kgb ?? '')"
            required>
            <x-slot:icon>
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="size-5 cursor-pointer text-body date-picker-icon"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10m-13 9h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v11a2 2 0 002 2z"/>
                </svg>
            </x-slot:icon>
        </x-ui.input>
    </x-input-form-block>

    {{-- KGB Berikutnya --}}
    <x-ui.input
        type="date"
        name="kgb_berikutnya"
        label="KGB Berikutnya"
        :value="old('kgb_berikutnya', $kgb->kgb_berikutnya ?? '')"
        class="mb-6"
        required>
        <x-slot:icon>
            <svg xmlns="http://www.w3.org/2000/svg"
                class="size-5 cursor-pointer text-body date-picker-icon"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M8 7V3m8 4V3m-9 8h10m-13 9h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v11a2 2 0 002 2z"/>
            </svg>
        </x-slot:icon>
    </x-ui.input>

    {{-- Masa Kerja --}}
    <x-input-form-block>
        <x-ui.input
            name="masa_kerja_lama"
            label="Masa Kerja Lama"
            :value="old('masa_kerja_lama', $kgb->masa_kerja_lama ?? '')" />

        <x-ui.input
            name="masa_kerja_baru"
            label="Masa Kerja Baru"
            :value="old('masa_kerja_baru', $kgb->masa_kerja_baru ?? '')" />
    </x-input-form-block>

    {{-- Golongan --}}
    <x-input-form-block>
        <x-ui.select
            name="golongan_lama"
            label="Golongan Lama"
            :options="$golonganOptions"
            :selected="old('golongan_lama', $kgb->golongan_lama ?? '')" />

        <x-ui.select
            name="golongan_baru"
            label="Golongan Baru"
            :options="$golonganOptions"
            :selected="old('golongan_baru', $kgb->golongan_baru ?? '')"
            required />
    </x-input-form-block>

    {{-- Pejabat Penetap --}}
    <x-input-form-block>
        <x-ui.input
            name="pejabat_penetap"
            label="Pejabat Penetap"
            :value="old('pejabat_penetap', $kgb->pejabat_penetap ?? '')"
            required />

        <x-ui.input
            name="nip_pejabat"
            label="NIP Pejabat"
            :value="old('nip_pejabat', $kgb->nip_pejabat ?? '')"
            required />
    </x-input-form-block>

    {{-- Upload SK --}}
    <div class="mb-6">
        <label class="block mb-1.5 text-sm font-medium text-heading">
            Upload SK (PDF)
        </label>
        <input type="file"
               name="file_sk"
               accept="application/pdf"
               class="cursor-pointer bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-primary focus:border-primary block w-full shadow-xs placeholder:text-muted" />
    </div>

    {{-- Status --}}
    <x-ui.input
        name="status_kgb"
        label="Status"
        :value="old('status_kgb', $kgb->status_kgb ?? 'diajukan')"
        readonly
        class="mb-6" />

    {{-- Submit --}}
    <button type="button"
            @click="openModal = true"
            class="text-white bg-primary hover:bg-primary/90 focus:ring-4 focus:ring-primary-medium shadow-xs font-medium rounded-base text-sm px-6 py-2.5">
        {{ $kgb ? 'Perbarui KGB' : 'Ajukan KGB' }}
    </button>

    {{-- Modal Konfirmasi --}}
    <div x-show="openModal"
        x-cloak
        x-transition.opacity
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

        <div @click.away="openModal = false"
            class="bg-white rounded-lg shadow-lg w-full max-w-md p-6 text-center">

            {{-- Icon --}}
            <div class="flex justify-center mb-4">
                <div class="w-12 h-12 flex items-center justify-center rounded-full bg-blue-100">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-6 h-6 text-blue-600"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4m0 4h.01M12 3a9 9 0 100 18 9 9 0 000-18z"/>
                    </svg>
                </div>
            </div>

            {{-- Title --}}
            <h3 class="text-lg font-semibold text-heading mb-2">
                Yakin Ingin Mengajukan KGB?
            </h3>

            {{-- Subtitle --}}
            <p class="text-sm text-muted mb-6">
                Data yang telah tersimpan tidak dapat dilakukan perubahan
            </p>

            {{-- Buttons --}}
            <div class="flex justify-center gap-4">
                <button type="button"
                        @click="openModal = false"
                        class="px-6 py-2 text-sm font-medium text-white bg-red-500 rounded-md hover:bg-red-600 transition">
                    Tidak
                </button>

                <button type="submit"
                        @click="loading = true"
                        class="px-6 py-2 text-sm font-medium text-white bg-green-500 rounded-md hover:bg-green-600 transition">
                    Ya
                </button>
            </div>
        </div>
</div>
</form>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // Hanya aktifkan showPicker saat klik icon
    document.querySelectorAll('.date-picker-icon').forEach(function(icon) {

        icon.addEventListener('click', function() {
            const input = this.closest('.relative').querySelector('input[type="date"]');

            if (input && input.showPicker) {
                input.showPicker();
            }
        });

    });

});
</script>
@endpush