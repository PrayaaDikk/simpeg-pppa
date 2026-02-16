@extends('layouts.app')

@section('header')
    Daftar Cuti Pegawai
@endsection

@section('content')
    {{-- BreadCrumb --}}
    <x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate('admin.cuti.index')" />

    {{-- Main Content --}}
    <section>
        <x-admin.cuti.cuti-list-table :cuti="$cuti" />
    </section>
    {{ $cuti->links() }}
@endsection
