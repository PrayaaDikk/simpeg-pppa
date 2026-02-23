@extends('layouts.app')

@section('content')
    {{-- Header --}}
    <x-ui.header>Edit Data Riwayat Pangkat</x-ui.header>

    <x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate('admin.riwayat-pangkat.edit', $riwayatPangkat->id)" />

    <section>
        @if ($errors->any())
            <div class="my-5 p-4 bg-red-100 text-red-700 rounded-base">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white shadow-sm p-6 rounded-lg">
            <x-admin.riwayat-pangkat.form-edit :riwayatPangkat="$riwayatPangkat" :pangkat="$pangkat" />
        </div>

    </section>
@endsection
