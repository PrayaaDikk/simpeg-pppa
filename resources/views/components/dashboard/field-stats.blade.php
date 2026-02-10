@props(['bidang'])

<div class="bg-white rounded-xl p-4 sm:p-12 shadow-sm">
    <h2 class="font-semibold mb-4">Distribusi Pegawai per Bidang</h2>

    @if (count($bidang) > 0)
        <div class="w-full" style="height: {{ max(400, count($bidang) * 60) }}px;">
            <canvas id="fieldBar"></canvas>
        </div>
    @else
        <x-ui.empty-state-chart message="Data bidang belum tersedia" />
    @endif
</div>

@push('scripts')
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
