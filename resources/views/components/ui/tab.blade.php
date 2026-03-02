@props(['id'])


<ul class="flex flex-wrap text-sm font-medium text-center text-body">
    <x-ui.tab-link :active="request()->routeIs(auth()->user()->routePrefix() . 'pegawai.show*')" :href="route(auth()->user()->routePrefix() . 'pegawai.show', $id)">
        Detail Pegawai
    </x-ui.tab-link>
    <x-ui.tab-link :active="request()->routeIs('*riwayat-pendidikan*')" :href="route(auth()->user()->routePrefix() . 'riwayat-pendidikan.index', $id)">Riwayat Pendidikan</x-ui.tab-link>
    <x-ui.tab-link :active="request()->routeIs('*riwayat-pangkat*')" :href="route(auth()->user()->routePrefix() . 'riwayat-pangkat.index', $id)">Riwayat Pangkat</x-ui.tab-link>
    <x-ui.tab-link :active="request()->routeIs('*riwayat-jabatan*')" :href="route(auth()->user()->routePrefix() . 'riwayat-jabatan.index', $id)">Riwayat Jabatan</x-ui.tab-link>
</ul>
