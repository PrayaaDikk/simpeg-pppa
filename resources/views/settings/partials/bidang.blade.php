@props(['bidang'])

<x-ui.table class="flex-1" :headers="['No', 'Nama Bidang', 'Akronim (Singkatan)', 'Aksi']" overflow>
    @if ($bidang->isNotEmpty())
        @foreach ($bidang as $item)
            <tr class="bg-neutral-primary border-b border-default">
                <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                    {{ $loop->iteration }}
                </th>
                <td class="px-6 py-4">{{ $item->nama_bidang }}</td>
                <td class="px-6 py-4">{{ $item->akronim }}</td>
                <td class="px-6 py-4 flex items-center gap-3">
                    <a href="{{ route('settings.bidang.edit', $item->id) }}"
                        class="bg-brand hover:bg-brand-strong p-2 rounded-full cursor-pointer text-white">
                        <x-ui.edit-icon />
                    </a>
                    <form action="{{ route('settings.bidang.destroy', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="submit" class="bg-danger hover:bg-danger-strong p-2 rounded-full cursor-pointer">
                            <x-ui.delete-icon />
                        </button>
                    </form>
                </td>
            </tr>
        @endforeach
    @else
        <tr class="bg-neutral-primary border-b border-default">
            <td class="px-6 py-4 text-center" colspan="4">Tidak ada data bidang</td>
        </tr>
    @endif
</x-ui.table>
