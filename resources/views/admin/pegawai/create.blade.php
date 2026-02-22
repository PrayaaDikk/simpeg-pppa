@extends('layouts.app')

@section('content')
    {{-- Header --}}
    <x-ui.header :back="route('admin.pegawai.index')">Tambah Pegawai</x-ui.header>

    {{-- BreadCrumb --}}
    <x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate('admin.pegawai.create')" />

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
            <x-admin.pegawai.form-add :pangkat="$pangkat" :bidang="$bidang" />
        </div>

    </section>
@endsection
