@props(['kgb'])

<x-ui.table class="flex-1" :headers="['No', 'TMT KGB', 'Gaji Lama', 'Gaji Baru', 'Gol. Baru', 'Status', '']">
    @if ($kgb->isNotEmpty())
        @foreach ($kgb as $item)
            <tr class="bg-neutral-primary border-b border-default">
                <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                    {{ ($kgb->currentPage() - 1) * $kgb->perPage() + $loop->iteration }}
                </th>
                <td class="px-6 py-4">{{ $item->tmt_kgb }}</td>
                <td class="px-6 py-4">{{ $item->gaji_lama }}</td>
                <td class="px-6 py-4">{{ $item->gaji_baru }}</td>
                <td class="px-6 py-4">{{ $item->golongan_baru }}</td>
                <td class="px-6 py-4">{{ $item->status_kgb }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.kgb.show', $item->id) }}" class="text-blue-600 hover:underline">Lihat
                        Detail</a>
                </td>
            </tr>
        @endforeach
    @else
        <tr class="bg-neutral-primary border-b border-default">
            <td class="px-6 py-4 text-center" colspan="7">Tidak ada data kgb</td>
        </tr>
    @endif

</x-ui.table>
