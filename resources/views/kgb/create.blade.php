@extends('layouts.app')

@section('content')
    {{-- Header --}}
    @if (auth()->user()->role !== 'admin')
        <x-ui.header :back="route(auth()->user()->routePrefix() . 'kgb.index')">Tambah Kenaikan Gaji Berkala</x-ui.header>
    @else
        <x-ui.header :back="route(auth()->user()->routePrefix() . 'pegawai.show', $pegawai)">Tambah Kenaikan Gaji Berkala</x-ui.header>
    @endif

    {{-- BreadCrumb --}}
    <x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate(auth()->user()->routePrefix() . 'kgb.create', $pegawai->id)" />

    {{-- Main Content --}}
    <section>
        @if ($errors->any())
            <div class="my-5 p-4 bg-red-100 text-red-700 rounded-base">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-sm p-6 rounded-lg">
            <x-kgb.form-add :pegawai="$pegawai" :pangkat="$pangkat" />
        </div>

    </section>
@endsection
