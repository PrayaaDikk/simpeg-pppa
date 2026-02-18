@extends('layouts.app')

@section('header')
    Kenaikan Gaji Berkala
@endsection

@section('content')
    <section>
        <x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate('admin.kgb.index')" />

        <div class="bg-white rounded-lg shadow-sm">

        </div>
    </section>
@endsection
