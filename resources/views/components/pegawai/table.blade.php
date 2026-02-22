@props(['pegawai'])

<x-ui.table class="flex-1 rounded-none" :headers="['No', 'Nama', 'NIP', 'Golongan', 'Jabatan', '']">
    @if ($pegawai->isNotEmpty())
        @foreach ($pegawai as $item)
            <tr class="bg-neutral-primary border-b border-default">
                <td class="px-6 py-4">{{ ($pegawai->currentPage() - 1) * $pegawai->perPage() + $loop->iteration }}</td>
                <td class="px-6 py-4">{{ $item->nama }}</td>
                <td class="px-6 py-4">{{ $item->nip }}</td>
                <td class="px-6 py-4">{{ $item->gol_ruang }}</td>
                <td class="px-6 py-4">{{ $item->jabatan }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.pegawai.show', $item->id) }}" class="text-blue-600 hover:underline">Lihat
                        Detail</a>
                </td>
            </tr>
        @endforeach
    @else
        <tr class="bg-neutral-primary border-b border-default">
            <td class="px-6 py-4 text-center" colspan="6">Tidak ada data pegawai</td>
        </tr>
    @endif

</x-ui.table>
