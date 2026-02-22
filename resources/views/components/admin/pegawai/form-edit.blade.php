@props(['pegawai', 'pangkat', 'bidang'])

<form class="max-w-5xl mx-auto" action="{{ route('admin.pegawai.update', $pegawai->id) }}" method="POST"
    enctype="multipart/form-data" x-data="{ loading: false }" @submit="loading = true">
    @csrf
    @method('PUT')

    {{-- Nama Lengkap - NIP --}}
    <x-input-form-block>
        <x-ui.input name="nama" label="Nama Lengkap" placeholder="John Doe" :value="old('nama', $pegawai->nama)" required />
        <x-ui.input name="nip" label="NIP" placeholder="198xxxxxxxxxxx" :value="old('nip', $pegawai->nip)" required />
    </x-input-form-block>

    {{-- Kartu Pegawai - Jenis Kelamin - Agama --}}
    <x-input-form-block>
        <x-ui.input name="karpeg" label="Kartu Pegawai" placeholder="198xxxxxxxxxxx" :value="old('karpeg', $pegawai->karpeg)" />
        <x-ui.select name="jns_kelamin" label="Jenis Kelamin" :options="[['value' => 'L', 'label' => 'Laki-laki'], ['value' => 'P', 'label' => 'Perempuan']]" :value="old('jns_kelamin', $pegawai->jns_kelamin)" required />
        <x-ui.select name="agama" label="Agama" :options="[
            ['value' => 'Islam', 'label' => 'Islam'],
            ['value' => 'Kristen', 'label' => 'Kristen'],
            ['value' => 'Katolik', 'label' => 'Katolik'],
            ['value' => 'Hindu', 'label' => 'Hindu'],
            ['value' => 'Buddha', 'label' => 'Buddha'],
            ['value' => 'Konghucu', 'label' => 'Konghucu'],
        ]" :value="old('agama', $pegawai->agama)" required />
    </x-input-form-block>

    {{-- Tempat Lahir - Tanggal Lahir --}}
    <x-input-form-block>
        <x-ui.input name="tpt_lahir" label="Tempat Lahir" placeholder="Kota Kendari" :value="old('tpt_lahir', $pegawai->tpt_lahir)" required />
        <x-ui.input type="date" name="tgl_lahir" label="Tanggal Lahir" :value="old('tgl_lahir', $pegawai->tgl_lahir ? $pegawai->tgl_lahir->format('Y-m-d') : '')" required>
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

    {{-- Pendidikan Terakhir - No. Telepon - Kode Pos --}}
    <x-input-form-block>
        <x-ui.input name="telp" label="No. Telepon" placeholder="08xxxxxxxxxx" :value="old('telp', $pegawai->telp)" required />
        <x-ui.input name="kode_pos" label="Kode Pos" placeholder="xxxxx" :value="old('kode_pos', $pegawai->kode_pos)" required />
    </x-input-form-block>

    {{-- Jenis Pegawai - Golongan/Ruang --}}
    <x-input-form-block>
        <x-ui.select name="jns_karyawan" label="Jenis Pegawai" :options="[
            ['value' => 'PNS', 'label' => 'PNS'],
            ['value' => 'CPNS', 'label' => 'CPNS'],
            ['value' => 'PPPK', 'label' => 'PPPK'],
        ]" :value="old('jns_karyawan', $pegawai->jns_karyawan)" required />
        <x-ui.select name="gol_ruang" label="Golongan/Ruang" :options="$pangkat
            ->map(function ($item) {
                return ['value' => $item->golongan, 'label' => $item->golongan . ' - ' . $item->nama_pangkat];
            })
            ->toArray()" :value="old('gol_ruang', $pegawai->gol_ruang)" required />
    </x-input-form-block>

    {{-- Jabatan - Bidang --}}
    <x-input-form-block>
        <x-ui.input name="jabatan" label="Jabatan" placeholder="Analis Kebijakan" :value="old('jabatan', $pegawai->jabatan)" required />
        <x-ui.select name="bidang_id" label="Bidang" :options="$bidang
            ->map(function ($item) {
                return ['value' => $item->id, 'label' => $item->nama_bidang];
            })
            ->toArray()" :value="old('bidang_id', $pegawai->bidang_id)" required />
    </x-input-form-block>

    {{-- TMT Pangkat --}}
    <x-ui.input type="date" name="tmt_pangkat" label="TMT Pangkat" :value="old('tmt_pangkat', $pegawai->tmt_pangkat ? $pegawai->tmt_pangkat->format('Y-m-d') : '')" required class="mb-5">
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

    {{-- Alamat --}}
    <x-ui.textarea name="alamat" label="Alamat" placeholder="Kelurahan/Desa, Kecamatan, Kab/Kota, Provinsi"
        :value="old('alamat', $pegawai->alamat)" required class="mb-5" />

    {{-- Status Kawin - Nama Pasangan --}}
    <x-input-form-block>
        <x-ui.select name="status_kawin" label="Status Kawin" :options="[
            ['value' => 'Belum Kawin', 'label' => 'Belum Kawin'],
            ['value' => 'Kawin', 'label' => 'Kawin'],
            ['value' => 'Cerai Hidup', 'label' => 'Cerai Hidup'],
            ['value' => 'Cerai Mati', 'label' => 'Cerai Mati'],
        ]" :value="old('status_kawin', $pegawai->status_kawin)" required />
        <x-ui.input name="suami_istri" label="Nama Pasangan" placeholder="Nama Pasangan" :value="old('suami_istri', $pegawai->suami_istri)" />
    </x-input-form-block>

    {{-- Status Kerja Pasangan - Jumlah Anak --}}
    <x-input-form-block>
        <x-ui.input name="sta_kerja_suami_istri" label="Status Kerja Pasangan"
            placeholder="PNS / Swasta / Tidak Bekerja" :value="old('sta_kerja_suami_istri', $pegawai->sta_kerja_suami_istri)" />
        <x-ui.input name="jumlah_anak" label="Jumlah Anak" placeholder="0" type="number" :value="old('jumlah_anak', $pegawai->jumlah_anak)" required />
    </x-input-form-block>

    {{-- Foto --}}
    <div class="mb-5">
        <label class="block mb-1.5 text-sm font-medium text-heading" for="file_input">Unggah Foto</label>

        @if ($pegawai->foto)
            <div class="mb-3">
                <img src="{{ route('pegawai.showFile', $pegawai->foto) }}" alt="{{ $pegawai->nama }}"
                    class="h-32 w-32 object-cover rounded-lg border-2 border-gray-300">
                <p class="text-xs text-gray-500 mt-1">Foto saat ini</p>
            </div>
        @endif

        <input
            class="cursor-pointer bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-primary focus:border-primary block w-full shadow-xs placeholder:text-muted hover:file:bg-blue-100"
            id="file_input" type="file" name="foto" accept="image/jpeg, image/png, image/jpg" />
        <p class="text-xs text-gray-500 mt-1">Kosongkan jika tidak ingin mengubah foto. Format: JPG, JPEG, PNG. Max 2MB
        </p>
    </div>

    {{-- Buttons --}}
    <div class="flex items-center gap-3">
        <button type="submit" :disabled="loading"
            class="text-white bg-primary box-border border border-transparent hover:bg-primary/90 focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none"
            :class="loading ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'">
            Simpan Perubahan
        </button>
        <a href="{{ route('admin.pegawai.show', $pegawai) }}"
            class="cursor-pointer text-gray-700 bg-gray-100 box-border border border-transparent hover:bg-gray-200 focus:ring-4 focus:ring-gray-300 shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">
            Batal
        </a>
    </div>
</form>
