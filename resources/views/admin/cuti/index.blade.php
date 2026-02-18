@extends('layouts.app')

@section('header')
    Daftar Cuti Pegawai
@endsection

@section('content')
    {{-- BreadCrumb --}}
    <x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate('admin.cuti.index')" />

    @if (session('success'))
        <div class="my-5 p-4 bg-green-100 text-green-700 rounded-base">
            <p class="list-disc list-inside">
                {{ session('success') }}
            </p>
        </div>
    @endif

    {{-- Main Content --}}
    <section>
        <x-admin.cuti.cuti-list-table :cuti="$cuti" />
    </section>
    {{ $cuti->links() }}
@endsection
