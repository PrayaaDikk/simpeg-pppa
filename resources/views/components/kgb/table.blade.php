@props(['kgb'])

<x-ui.table class="flex-1 rounded-none" :headers="['No', 'No. Surat', 'Nama', 'NIP', 'Status', 'Dibuat', 'Diperbarui', '']">
    @if ($kgb->isNotEmpty())
        @foreach ($kgb as $item)
            <tr class="bg-neutral-primary border-b border-default">
                <td class="px-6 py-4">
                    {{ $loop->iteration }}
                </td>
                <td class="px-6 py-4">{{ $item->nomor_surat }}</td>
                <td class="px-6 py-4">{{ $item->pegawai->nama }}</td>
                <td class="px-6 py-4">{{ $item->pegawai->nip }}</td>
                <td class="px-6 py-4">
                    <span
                        class="{{ App\Constants\Cuti::STATUS_COLOR[$item->status_kgb] }} . ' text-sm font-medium px-2 py-1 rounded capitalize'">{{ $item->status_kgb }}</span>
                </td>
                <td class="px-6 py-4">{{ $item->created_at->format('H.i - d M Y') }}</td>
                <td class="px-6 py-4">{{ $item->updated_at->format('H.i - d M Y') }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route(auth()->user()->routePrefix() . 'kgb.show', $item->id) }}"
                        class="text-blue-600 hover:underline">Lihat
                        Detail</a>
                </td>
            </tr>
        @endforeach
    @else
        <tr class="bg-neutral-primary border-b border-default">
            <td class="px-6 py-4 text-center" colspan="8">Tidak ada data KGB</td>
        </tr>

    @endif

</x-ui.table>
