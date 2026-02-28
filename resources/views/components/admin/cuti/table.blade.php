@props(['cuti', 'atasan'])

<x-ui.table class="flex-1" :headers="['No', 'Nama Pegawai', 'Jenis', 'Status', 'Dibuat', 'Diperbarui', '']">
    @if ($cuti->isNotEmpty())
        @foreach ($cuti as $item)
            <tr class="bg-neutral-primary border-b border-default">
                <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                    {{ ($cuti->currentPage() - 1) * $cuti->perPage() + $loop->iteration }}
                </th>
                <td class="px-6 py-4">{{ $item->pegawai->nama }}</td>
                <td class="px-6 py-4 capitalize">{{ $item->jenis_cuti }}</td>
                <td class="px-6 py-4 capitalize">
                    <span
                        class="{{ App\Constants\Cuti::STATUS_COLOR[$item->status_cuti] }} . ' text-sm font-medium px-2 py-1 rounded capitalize'">{{ $item->status_cuti }}</span>
                </td>
                <td class="px-6 py-4">{{ $item->created_at->format('H.i - d M Y') }}</td>
                <td class="px-6 py-4">{{ $item->updated_at->format('H.i - d M Y') }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.cuti.show', $item->id) }}" class="text-blue-600 hover:underline">Lihat
                        Detail</a>
                </td>
            </tr>
        @endforeach
    @else
        <tr class="bg-neutral-primary border-b border-default">
            <td class="px-6 py-4 text-center" colspan="7">Tidak ada data cuti</td>
        </tr>
    @endif

</x-ui.table>
