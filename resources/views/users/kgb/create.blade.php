@extends('layouts.app')

@section('content')

    {{-- Header --}}
    <x-ui.header :back="route('kgb.index')">
        Informasi Pegawai DP3A Kota Kendari
    </x-ui.header>

    {{-- Breadcrumb --}}
    <x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate('kgb.create')" />

    {{-- Main Content --}}
    <section>
        <div class="bg-white shadow-sm rounded-lg p-8">

            <h2 class="text-2xl font-semibold text-heading mb-8">
                Ajukan KGB (Kenaikan Gaji Berkala)
            </h2>

            {{-- Form --}}
            <x-kgb.form-add :golonganOptions="$golonganOptions" />

        </div>
    </section>

@endsection