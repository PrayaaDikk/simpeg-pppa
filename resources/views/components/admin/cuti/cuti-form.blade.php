@props(['pangkat', 'bidang'])

<form class="max-w-5xl mx-auto" action="" method="POST" enctype="multipart/form-data">
    @csrf

    <x-input-form-block>
        <x-ui.input name="jenis_cuti" label="Jenis Cuti" placeholder="Pilih jenis cuti" required />
        <x-ui.input type="date" name="tanggal_mulai" label="Tanggal Mulai" placeholder="dd-mm-yyyy" required />
    </x-input-form-block>

    <x-input-form-block>
        <x-ui.input name="lama_cuti" label="Lama Cuti" placeholder="Lama cuti" required />
        <x-ui.input type="date" name="tanggal_selesai" label="Tanggal Selesai" placeholder="dd-mm-yyyy" required />
    </x-input-form-block>

    <x-input-form-block>
        <x-ui.input name="alasan_cuti" label="Alasan Cuti" placeholder="Masukkan alasan cuti" required />
    </x-input-form-block>

    <x-input-form-block>
        <x-ui.input name="alamat_cuti" label="Alamat Selama Cuti" placeholder="Masukkan alamat selama cuti" required />
    </x-input-form-block>

    <x-input-form-block>
        <x-ui.input name="no_telp" label="No. Telepon" placeholder="Masukkan no. telepon" required />
    </x-input-form-block>

    <x-input-form-block>
        <x-ui.input name="catatan_cuti" label="Catatan Tambahan" placeholder="Masukkan catatan" required />
    </x-input-form-block>

    <button type="submit"
        class="cursor-pointer text-white bg-primary box-border border border-transparent hover:bg-primary/90 focus:ring-4 focus:ring-brand-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none">Tambah
        Cuti</button>
</form>
