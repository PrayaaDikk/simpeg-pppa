@extends('layouts.app')

@section('content')
    @php
        // 1. Tentukan status apakah ini halaman Create atau Edit
        $isCreate = request()->routeIs('settings.bidang.create');

        // 2. Tentukan Route Action secara dinamis
        $formAction = $isCreate ? route('settings.bidang.store') : route('settings.bidang.update', $bidang->id);
    @endphp

    {{-- Header --}}
    <x-ui.header :back="route('settings.index')">
        {{ $isCreate ? 'Tambah Bidang' : 'Edit Bidang' }}
    </x-ui.header>

    <section class="mt-5">
        {{-- Error Alert --}}
        @if ($errors->any())
            <div class="mb-5 p-4 bg-red-100 text-red-700 rounded-base border border-red-200">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-sm p-6 rounded-lg">
            <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data" class="max-w-5xl mx-auto"
                x-data="{ loading: false }" @submit="loading = true">

                @csrf
                @if (!$isCreate)
                    @method('PUT')
                @endif

                <x-input-form-block>
                    <x-ui.input name="nama_bidang" label="Nama Bidang" placeholder="e.g. Perlindungan Hak Perempuan"
                        :value="$isCreate ? old('nama_bidang') : old('nama_bidang', $bidang->nama_bidang)" required />
                </x-input-form-block>

                <x-input-form-block>
                    <x-ui.input name="akronim" label="Akronim (Singkatan)" placeholder="e.g. PHA" :value="$isCreate ? old('akronim') : old('akronim', $bidang->akronim)"
                        required />
                </x-input-form-block>

                <div class="mt-6">
                    <button type="submit" :disabled="loading"
                        class="text-white bg-primary border border-transparent hover:bg-primary/90 focus:ring-4 focus:ring-brand-medium shadow-xs font-medium rounded-base text-sm px-4 py-2.5 flex items-center gap-2"
                        :class="loading ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'">

                        Simpan Bidang
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
