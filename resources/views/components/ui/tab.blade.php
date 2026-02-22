@props(['id'])

<ul class="flex flex-wrap text-sm font-medium text-center text-body">
    <x-ui.tab-link :active="request()->routeIs('admin.pegawai.show')" :href="route('admin.pegawai.show', $id)">
        Detail Pegawai
    </x-ui.tab-link>
    <x-ui.tab-link :active="request()->is('admin/pegawai/' . $id . '/riwayat-pendidikan*') ||
        request()->is('admin/pegawai/riwayat-pendidikan*')" :href="route('admin.riwayat-pendidikan.index', $id)">Riwayat Pendidikan</x-ui.tab-link>
    <x-ui.tab-link :active="request()->is('admin/pegawai/' . $id . '/riwayat-pangkat*') ||
        request()->is('admin/pegawai/riwayat-pangkat*')" :href="route('admin.riwayat-pangkat.index', $id)">Riwayat Pangkat</x-ui.tab-link>
    <x-ui.tab-link :active="request()->is('admin/pegawai/' . $id . '/riwayat-jabatan*') ||
        request()->is('admin/pegawai/riwayat-jabatan*')" :href="route('admin.riwayat-jabatan.index', $id)">Riwayat Jabatan</x-ui.tab-link>
</ul>
