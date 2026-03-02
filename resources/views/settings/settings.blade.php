@extends('layouts.app')

@section('content')
    <x-ui.header>Pengaturan</x-ui.header>

    @if (session('success'))
        <div class="my-5 p-4 bg-green-100 text-green-700 rounded-base">
            <p class="list-disc list-inside">
                {{ session('success') }}
            </p>
        </div>
    @endif

    @if ($errors->any())
        <div class="my-5 p-4 bg-red-100 text-red-700 rounded-base">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section>
        <div class="bg-white shadow-sm p-6 rounded-lg">
            <div class="flex items-center justify-end gap-4 max-xs:justify-start mb-8">
                <a href="{{ route('settings.pangkat.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90 focus:ring-4 focus:ring-primary-medium transition-colors">
                    Tambah Pangkat
                </a>
                <a href="{{ route('settings.bidang.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90 focus:ring-4 focus:ring-primary-medium transition-colors">
                    Tambah Bidang
                </a>
                <a href="{{ route('settings.jabatan.create') }}"
                    class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90 focus:ring-4 focus:ring-primary-medium transition-colors">
                    Tambah Jabatan
                </a>
            </div>

            <div class="space-y-6">
                @include('settings.partials.pangkat', [
                    'pangkat' => $pangkat,
                ])

                @include('settings.partials.bidang', [
                    'bidang' => $bidang,
                ])

                @include('settings.partials.jabatan', [
                    'jabatan' => $jabatan,
                ])
            </div>
        </div>
    </section>
@endsection
