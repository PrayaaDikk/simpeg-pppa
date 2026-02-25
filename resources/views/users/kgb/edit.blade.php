@extends('layouts.app')

@section('content')

<x-ui.header :back="route('kgb.index')">
    Informasi Pegawai DP3A Kota Kendari
</x-ui.header>

<x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate('kgb.edit', $kgb->id)" />

<section>
    <div class="bg-white shadow-sm rounded-lg p-8">

        <h2 class="text-2xl font-semibold text-heading mb-8">
            Edit KGB (Perbaikan)
        </h2>

        <x-kgb.form 
            :golonganOptions="$golonganOptions"
            :kgb="$kgb" />

    </div>
</section>

@endsection