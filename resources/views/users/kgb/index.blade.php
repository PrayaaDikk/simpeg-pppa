@extends('layouts.app')

@section('content')

    {{-- Header --}}
    <x-ui.header :back="route('admin.dashboard')">
        Informasi Pegawai DP3A Kota Kendari
    </x-ui.header>

    {{-- Breadcrumb --}}
    <x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate('kgb.index')" />

    <section>

        {{-- Success Message --}}
        @if (session('success'))
            <div class="my-5 p-4 bg-green-100 text-green-700 rounded-base">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg">

            {{-- Helper Section --}}
            <div class="flex items-center justify-between flex-wrap mb-2 p-6 gap-4">

                {{-- Search --}}
                <form method="GET" action="{{ route('kgb.index') }}"
                    class="flex items-center gap-2">

                    <input type="search"
                        name="search"
                        class="px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium rounded-base text-heading text-sm focus:ring-primary focus:border-primary block w-full placeholder:text-muted"
                        placeholder="Cari Nomor Surat..."
                        value="{{ request('search') }}" />
                </form>

                {{-- Tambah KGB --}}
                <a href="{{ route('kgb.create') }}"
                    class="inline-flex items-center gap-2 text-white bg-primary hover:bg-primary/90 transition-all focus:ring-4 focus:ring-primary-medium shadow-xs font-medium rounded-base text-sm px-4 py-2.5">

                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M8 3V13M3 8H13"
                            stroke="currentColor"
                            stroke-width="2"
                            stroke-linecap="round"/>
                    </svg>

                    <span class="font-bold text-xs">Tambah KGB</span>
                </a>
            </div>

            {{-- Table Component --}}
            <x-kgb.table :kgb="$kgb" />

        </div>
    </section>

@endsection