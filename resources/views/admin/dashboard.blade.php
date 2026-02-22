@extends('layouts.app')

@section('content')
    <x-ui.header>Selamat Datang di SIMPEG-PPPA Kota Kendari</x-ui.header>

    {{-- BreadCrumb --}}
    <x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate('admin.dashboard')" />

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
@endsection
