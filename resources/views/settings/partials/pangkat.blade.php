@props(['pangkat'])

<x-ui.table class="flex-1" :headers="['No', 'Nama Pangkat', 'Golongan', 'Aksi']" overflow>
    @if ($pangkat->isNotEmpty())
        @foreach ($pangkat as $item)
            <tr class="bg-neutral-primary border-b border-default">
                <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                    {{ $loop->iteration }}
                </th>
                <td class="px-6 py-4">{{ $item->nama_pangkat }}</td>
                <td class="px-6 py-4">{{ $item->golongan }}</td>
                <td class="px-6 py-4 flex items-center gap-3">
                    <a href="{{ route('settings.pangkat.edit', $item->id) }}"
                        class="bg-brand hover:bg-brand-strong p-2 rounded-full cursor-pointer text-white">
                        <x-ui.edit-icon />
                    </a>
                    <form action="{{ route('settings.pangkat.destroy', $item->id) }}" method="POST">
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
            <td class="px-6 py-4 text-center" colspan="4">Tidak ada data pangkat</td>
        </tr>
    @endif
</x-ui.table>
