@extends('layouts.admin-app')

@section('content')
    {{-- Header --}}
    <x-ui.header>Informasi Cuti Pegawai DP3A Kota Kendari</x-ui.header>

    {{-- BreadCrumb --}}
    <x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate('admin.cuti.index')" />

    {{-- Main Content --}}
    <section>
        @if (session('success'))
            <div class="my-5 p-4 bg-green-100 text-green-700 rounded-base">
                <p class="list-disc list-inside">
                    {{ session('success') }}
                </p>
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg ">
            <x-cuti.table :cuti="$cuti" />
        </div>
    </section>
@endsection
