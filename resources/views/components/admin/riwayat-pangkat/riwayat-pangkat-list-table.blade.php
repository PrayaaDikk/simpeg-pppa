@props(['riwayatPangkat'])

<x-ui.table class="flex-1 rounded-none" :headers="['No', 'TMT Pangkat', 'Nomor SK', 'Tanggal Input', '']">
    @if ($riwayatPangkat->isNotEmpty())
        @foreach ($riwayatPangkat as $item)
            <tr class="bg-neutral-primary border-b border-default">
                <td class="px-6 py-4">
                    {{ $loop->iteration }}
                </td>
                <td class="px-6 py-4">{{ $item->tmt_pangkat->format('d M Y') }}</td>
                <td class="px-6 py-4">{{ $item->nomor_sk }}</td>
                <td class="px-6 py-4">{{ $item->tanggal_input->format('d M Y') }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.riwayat-pangkat.show', $item->id) }}"
                        class="text-blue-600 hover:underline">Lihat
                        Detail</a>
                </td>
            </tr>
        @endforeach
    @else
        <tr class="bg-neutral-primary border-b border-default">
            <td class="px-6 py-4 text-center" colspan="6">Tidak ada riwayat pendidikan</td>
        </tr>

    @endif

</x-ui.table>
