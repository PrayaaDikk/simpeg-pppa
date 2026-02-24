@props(['riwayatJabatan'])

<x-ui.table class="flex-1 rounded-none" :headers="['No', 'TMT Jabatan', 'Nama Jabatan', 'Nomor SK', 'Tanggal SK', '']">
    @if ($riwayatJabatan->isNotEmpty())
        @foreach ($riwayatJabatan as $item)
            <tr class="bg-neutral-primary border-b border-default">
                <td class="px-6 py-4">
                    {{ $loop->iteration }}
                </td>
                <td class="px-6 py-4">{{ $item->tmt_jabatan->format('d M Y') }}</td>
                <td class="px-6 py-4">{{ $item->nama_jabatan }}</td>
                <td class="px-6 py-4">{{ $item->nomor_sk }}</td>
                <td class="px-6 py-4">{{ $item->tanggal_sk->format('d M Y') }}</td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.riwayat-jabatan.show', $item->id) }}"
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
