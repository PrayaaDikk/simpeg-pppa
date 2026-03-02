@extends('layouts.app')

@section('content')
    {{-- Header --}}
    <x-ui.header :back="route(auth()->user()->routePrefix() . 'riwayat-jabatan.index', $pegawaiId)">Tambah Riwayat Pangkat</x-ui.header>

    {{-- BreadCrumb --}}
    <x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate(auth()->user()->routePrefix() . 'riwayat-jabatan.create', $pegawaiId)" />

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
            <x-riwayat-jabatan.form-add :pegawaiId="$pegawaiId" />
        </div>

    </section>
@endsection
