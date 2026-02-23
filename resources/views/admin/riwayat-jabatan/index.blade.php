@extends('layouts.app')

@section('content')
    {{-- Header --}}
    <x-ui.header :back="route('admin.pegawai.show', $pegawaiId)">Riwayat Jabatan</x-ui.header>

    {{-- BreadCrumb --}}
    <x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate('admin.riwayat-jabatan.index', $pegawaiId)" />

    {{-- Main Content --}}
    <section>
        @if (session('success'))
            <div class="my-5 p-4 bg-green-100 text-green-700 rounded-base">
                <p class="list-disc list-inside">
                    {{ session('success') }}
                </p>
            </div>
        @endif

        {{-- Tab --}}
        <x-ui.tab :id="$pegawaiId" />

        <div class="bg-white shadow-sm rounded-bl-lg rounded-br-lg ">

            {{-- Table Helper --}}
            <div class="ml-auto w-fit mb-2 p-6">

                {{-- Create Button --}}
                <a href="{{ route('admin.riwayat-jabatan.create', $pegawaiId) }}"
                    class="inline-flex items-center gap-2 text-white bg-primary box-border border border-transparent hover:bg-primary/90 duration-200 transition-all focus:ring-4 focus:ring-primary-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" class="size-4"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path
                            d="M8.5 2.75C8.5 2.55109 8.42098 2.36032 8.28033 2.21967C8.13968 2.07902 7.94891 2 7.75 2C7.55109 2 7.36032 2.07902 7.21967 2.21967C7.07902 2.36032 7 2.55109 7 2.75V7H2.75C2.55109 7 2.36032 7.07902 2.21967 7.21967C2.07902 7.36032 2 7.55109 2 7.75C2 7.94891 2.07902 8.13968 2.21967 8.28033C2.36032 8.42098 2.55109 8.5 2.75 8.5H7V12.75C7 12.9489 7.07902 13.1397 7.21967 13.2803C7.36032 13.421 7.55109 13.5 7.75 13.5C7.94891 13.5 8.13968 13.421 8.28033 13.2803C8.42098 13.1397 8.5 12.9489 8.5 12.75V8.5H12.75C12.9489 8.5 13.1397 8.42098 13.2803 8.28033C13.421 8.13968 13.5 7.94891 13.5 7.75C13.5 7.55109 13.421 7.36032 13.2803 7.21967C13.1397 7.07902 12.9489 7 12.75 7H8.5V2.75Z"
                            fill="currentColor" />
                    </svg>
                    <span class="font-bold text-xs">Tambah Riwayat</span>
                </a>
            </div>

            <x-admin.riwayat-jabatan.table :riwayatJabatan="$riwayatJabatan" />
        </div>
    </section>
@endsection
