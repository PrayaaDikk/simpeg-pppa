@php
    use App\Helpers\StatsHelper;
@endphp

@props(['bidang', 'total'])

<div class="bg-white rounded-xl flex flex-col p-4 sm:p-12 shadow-sm">
    <h2 class="font-semibold mb-2">Komposisi Pegawai per Bidang</h2>

    <div>
        {{-- Table Section --}}
        <x-ui.table class="flex-1" :headers="['No', 'Bidang', 'Jumlah', 'Persentase']" :footers="['', 'Jumlah', $total, '100%']">
            @foreach ($bidang as $key => $item)
                <tr class="bg-neutral-primary border-b border-default">
                    <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">
                        {{ $loop->iteration }}</th>
                    <td class="px-6 py-4">{{ $item->first()->bidang->nama_bidang }}</td>
                    <td class="px-6 py-4">{{ $item->count() }}</td>
                    <td class="px-6 py-4">
                        {{ StatsHelper::calculatePercentage($item->count(), $total) }}
                    </td>
                </tr>
            @endforeach
        </x-ui.table>
    </div>
</div>
