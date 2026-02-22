@props(['riwayatPendidikan'])

<x-ui.table class="flex-1 rounded-none" :headers="['No', 'Tingkat', 'Jurusan', 'Institusi', 'Tahun Lulus', 'Bukti Ijazah', '']">
    @if ($riwayatPendidikan->isNotEmpty())
        @foreach ($riwayatPendidikan as $item)
            <tr class="bg-neutral-primary border-b border-default">
                <td class="px-6 py-4">
                    {{ $loop->iteration }}
                </td>
                <td class="px-6 py-4">{{ $item->tingkat }}</td>
                <td class="px-6 py-4">{{ $item->jurusan }}</td>
                <td class="px-6 py-4">{{ $item->institusi }}</td>
                <td class="px-6 py-4">{{ $item->tahun_lulus }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.riwayat-pendidikan.show', $item->id) }}"
                        class="text-blue-600 hover:underline">Lihat
                        Detail</a>
                </td>
            </tr>
        @endforeach
    @else
        <tr class="bg-neutral-primary border-b border-default">
            <td class="px-6 py-4 text-center" colspan="7">Tidak ada riwayat pendidikan</td>
        </tr>

    @endif

</x-ui.table>
