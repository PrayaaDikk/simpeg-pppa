@extends('layouts.app')

@section('content')
    @php
        // 1. Tentukan status apakah ini halaman Create atau Edit
        $isCreate = request()->routeIs('settings.jabatan.create');

        // 2. Tentukan Route Action secara dinamis
        $formAction = $isCreate ? route('settings.jabatan.store') : route('settings.jabatan.update', $jabatan->id);
    @endphp

    {{-- Header --}}
    <x-ui.header :back="route('settings.index')">
        {{ $isCreate ? 'Tambah Jabatan' : 'Edit Jabatan' }}
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
                    <x-ui.input name="nama_jabatan" label="Nama Jabatan" placeholder="e.g. Pengurus Bidang"
                        :value="$isCreate ? old('nama_jabatan') : old('nama_jabatan', $jabatan->nama_jabatan)" required />
                </x-input-form-block>

                <x-input-form-block>
                    <x-ui.select name="bidang_id" label="Bidang" placeholder="e.g. Sekretariat" :options="$bidang
                        ->map(function ($item) {
                            return ['value' => $item->id, 'label' => $item->nama_bidang];
                        })
                        ->toArray()"
                        :value="$isCreate ? old('bidang_id') : old('bidang_id', $jabatan->bidang_id)" />
                </x-input-form-block>

                <div class="flex items-center gap-2">
                    {{-- Input hidden ini memastikan jika checkbox tidak dicentang, value '0' tetap terkirim ke server --}}
                    <input type="hidden" name="is_singleton" value="0">

                    <input type="checkbox" name="is_singleton" id="is_singleton" value="1"
                        {{ old('is_singleton', $isCreate ? 0 : $jabatan->is_singleton) ? 'checked' : '' }}
                        class="bg-neutral-secondary-medium border border-default-medium text-primary rounded-base focus:ring-primary focus:ring-offset-0 h-5 w-5 cursor-pointer disabled:cursor-not-allowed" />

                    <label for="is_singleton" class="text-sm font-medium text-heading cursor-pointer">Tunggal</label>
                </div>

                <div class="mt-6">
                    <button type="submit" :disabled="loading"
                        class="text-white bg-primary border border-transparent hover:bg-primary/90 focus:ring-4 focus:ring-brand-medium shadow-xs font-medium rounded-base text-sm px-4 py-2.5 flex items-center gap-2"
                        :class="loading ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'">

                        Simpan Jabatan
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
