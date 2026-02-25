@props(['kgb'])

<div class="overflow-x-auto">
    <table class="min-w-full text-sm text-left text-body">
        <thead class="bg-neutral-secondary-medium text-xs uppercase">
            <tr>
                <th class="px-6 py-3">No</th>
                <th class="px-6 py-3">Nomor Surat</th>
                <th class="px-6 py-3">TMT KGB</th>
                <th class="px-6 py-3">Gaji Lama</th>
                <th class="px-6 py-3">Gaji Baru</th>
                <th class="px-6 py-3">Gol. Baru</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($kgb as $index => $item)
                <tr class="border-b hover:bg-neutral-secondary-medium/40">

                    <td class="px-6 py-4">
                        {{ $index + 1 }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $item->nomor_sk ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        {{ \Carbon\Carbon::parse($item->tmt_kgb)->format('d-m-Y') }}
                    </td>

                    <td class="px-6 py-4">
                        Rp {{ number_format($item->gaji_lama, 0, ',', '.') }}
                    </td>

                    <td class="px-6 py-4">
                        Rp {{ number_format($item->gaji_baru, 0, ',', '.') }}
                    </td>

                    <td class="px-6 py-4">
                        {{ $item->golongan_baru ?? '-' }}
                    </td>

                    <td class="px-6 py-4">
                        <span class="px-2 py-1 text-xs rounded-full
                            {{ $item->status_kgb == 'disetujui'
                                ? 'bg-green-100 text-green-700'
                                : ($item->status_kgb == 'ditolak'
                                    ? 'bg-red-100 text-red-700'
                                    : 'bg-yellow-100 text-yellow-700') }}">
                            {{ ucfirst($item->status_kgb) }}
                        </span>
                    </td>

                    <td class="px-6 py-4 space-x-2">

                        {{-- Detail --}}
                        <a href="{{ route('kgb.show', $item->id) }}"
                            class="text-primary hover:underline text-xs">
                            Lihat
                        </a>

                        {{-- Edit (hanya jika ditolak) --}}
                        @if($item->status_kgb == 'ditolak')
                            <a href="{{ route('kgb.edit', $item->id) }}"
                                class="text-blue-600 hover:underline text-xs">
                                Edit
                            </a>
                        @endif

                        {{-- Download SK --}}
                        @if($item->file_sk)
                            <a href="{{ asset('storage/' . $item->file_sk) }}"
                                target="_blank"
                                class="text-green-600 hover:underline text-xs">
                                Download SK
                            </a>
                        @endif

                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="8" class="px-6 py-6 text-center text-muted">
                        Belum ada data KGB
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>