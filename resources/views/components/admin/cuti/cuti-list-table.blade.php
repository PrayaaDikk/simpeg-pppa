@props(['cuti'])

<x-ui.table class="flex-1" :headers="['No', 'Nama Pegawai', 'Jenis', 'Status', 'Atasan', 'Pejabat']">
    @if ($cuti->isNotEmpty())
        @foreach ($cuti as $item)
            <tr class="bg-neutral-primary border-b border-default">
                <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                    {{ ($cuti->currentPage() - 1) * $cuti->perPage() + $loop->iteration }}
                </th>
                <td class="px-6 py-4">{{ $item->pegawai()->first()->nama }}</td>
                <td class="px-6 py-4">{{ $item->jenis_cuti }}</td>
                <td class="px-6 py-4">{{ $item->status_cuti }}</td>
                <td class="px-6 py-4">{{ $item->disetujui_oleh }}</td>
                <td class="px-6 py-4">-</td>
                <td class="px-6 py-4">
                    <a href="" class="text-blue-600 hover:underline">Lihat Detail</a>
                </td>
            </tr>
        @endforeach
    @else
        <tr class="bg-neutral-primary border-b border-default">
            <td class="px-6 py-4 text-center" colspan="6">Tidak ada data cuti</td>
        </tr>
    @endif

</x-ui.table>
