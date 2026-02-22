@props(['bidang'])

<div class="bg-white rounded-xl p-4 sm:p-12 shadow-sm">
    <h2 class="font-semibold mb-4">Distribusi Pegawai per Bidang</h2>

    @if (count($bidang) > 0)
        <div class="w-full h-96">
            <canvas id="fieldBar"></canvas>
        </div>
    @else
        <x-ui.empty-state-chart message="Data bidang belum tersedia" />
    @endif
</div>

@push('scripts')
    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    {{-- ChartJS DataLabels Plugin --}}
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js">
    </script>

    <script type="module">
        import {
            initFieldChart
        } from '/js/charts/field-chart.js';

        const bidangData = [
            @foreach ($bidang as $item)
                {
                    akronim: '{{ $item[0]->bidang->akronim }}',
                    count: {{ count($item) }}
                },
            @endforeach
        ];

        initFieldChart('fieldBar', bidangData);
    </script>
@endpush
