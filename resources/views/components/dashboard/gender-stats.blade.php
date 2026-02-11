@php
    use App\Helpers\StatsHelper;
@endphp

@props(['data'])

<div class="bg-white rounded-xl flex flex-col p-4 sm:p-12 shadow-sm">
    <h2 class="font-semibold mb-2">Komposisi Pegawai</h2>

    @if ($data['total'] > 0)
        <div class="flex items-center sm:items-start flex-col lg:flex-row gap-8">
            {{-- Chart Section --}}
            <div class="p-4 h-75">
                <canvas id="genderPie"></canvas>
            </div>

            {{-- Table Section --}}
            <x-ui.table class="flex-1" :headers="['No', 'Jenis Kelamin', 'Jumlah', 'Persentase']" :footers="['', 'Jumlah', $data['total'], '100%']">
                <tr class="bg-neutral-primary border-b border-default">
                    <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">1</th>
                    <td class="px-6 py-4">Laki-laki</td>
                    <td class="px-6 py-4">{{ $data['lakiLaki'] }}</td>
                    <td class="px-6 py-4">
                        {{ StatsHelper::calculatePercentage($data['lakiLaki'], $data['total']) }}
                    </td>
                </tr>
                <tr class="bg-neutral-primary border-b border-default">
                    <th scope="row" class="px-6 py-4 font-medium text-heading whitespace-nowrap">2</th>
                    <td class="px-6 py-4">Perempuan</td>
                    <td class="px-6 py-4">{{ $data['perempuan'] }}</td>
                    <td class="px-6 py-4">
                        {{ StatsHelper::calculatePercentage($data['perempuan'], $data['total']) }}
                    </td>
                </tr>
            </x-ui.table>
        </div>
    @else
        <x-ui.empty-state-chart message="Data pegawai belum tersedia" />
    @endif
</div>

@push('scripts')
    <script type="module">
        new Chart(document.getElementById('genderPie'), {
            type: 'pie',
            data: {
                labels: ['Laki-laki', 'Perempuan'],
                datasets: [{
                    data: [{{ $data['lakiLaki'] }}, {{ $data['perempuan'] }}],
                    backgroundColor: ['#4A4AFF', '#FF6B9A'],
                }]
            },
            options: {
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                family: 'Poppins',
                                weight: '500',
                                size: 14,
                            },
                            usePointStyle: true,
                            pointStyle: 'circle',
                        },
                    },
                    datalabels: {
                        color: '#fff',
                        font: {
                            family: 'Poppins',
                            weight: 'bold',
                            size: 14,
                        },
                        formatter: (value, ctx) => {
                            const total = ctx.chart.data.datasets[0].data.reduce((a, b) => a + b, 0);
                            return ((value / total) * 100).toFixed(1) + '%';
                        }
                    }
                }
            },
            plugins: [ChartDataLabels]
        });
    </script>
@endpush
