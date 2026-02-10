@extends('admin.layouts.app')

@section('content')
    <div>
        {{-- Header --}}
        <header class="mb-10">
            <h1 class="text-2xl font-bold">Selamat Datang di SIMPEG-PPPA Kota Kendari</h1>
        </header>

        {{-- Main Content --}}
        <section class="space-y-8">
            {{-- Statistics Badges --}}
            <x-dashboard.stat-badge-block :data="$gender" />

            {{-- Gender Composition --}}
            <x-dashboard.gender-stats :data="$gender" />

            {{-- Field Composition --}}
            <x-dashboard.field-stats :bidang="$bidang" />

            {{-- Field Composition Table --}}
            <x-dashboard.field-stats-table :bidang="$bidang" :total="$gender['total']" />
        </section>
    </div>
@endsection
