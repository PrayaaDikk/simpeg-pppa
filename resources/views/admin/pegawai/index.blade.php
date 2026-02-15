@extends('layouts.app')

@section('header')
    Informasi Pegawai DP3A Kota Kendari
@endsection

@section('content')
    {{-- Main Content --}}
    <section>

        {{-- BreadCrumb --}}
        <x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate('admin.pegawai.index')" />

        @if (session('success'))
            <div class="mt-5 p-4 bg-green-100 text-green-700 rounded-base">
                <p class="list-disc list-inside">
                    {{ session('success') }}
                </p>
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg ">

            {{-- Table Helper --}}
            <div class="flex items-center justify-between flex-wrap mb-2 p-6 gap-4">

                {{-- Search and Filter --}}
                <div class="inline-flex items-center justify-between gap-2 flex-wrap">
                    <form method="GET" action="{{ route('admin.pegawai.index') }}" id="filterForm"
                        class="flex items-center gap-2 flex-wrap" data-auto-submit="true">

                        {{-- Search Input --}}
                        <div class="flex items-center max-w-sm">
                            <label for="pegawai-search" class="sr-only">Cari pegawai</label>
                            <input type="search" id="pegawai-search" name="search"
                                class="px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium rounded-base text-heading text-sm focus:ring-primary focus:border-primary block w-full placeholder:text-muted"
                                placeholder="Cari..." value="{{ request('search', '') }}" aria-label="Cari pegawai" />
                        </div>

                        {{-- Dropdown Filter --}}
                        <x-ui.dropdown-filter title="Filter">
                            @php
                                $filters = [
                                    [
                                        'label' => 'Jenis Pegawai',
                                        'name' => 'jenis',
                                        'options' => ['PNS' => 'PNS', 'CPNS' => 'CPNS', 'PPPK' => 'PPPK'],
                                    ],
                                    [
                                        'label' => 'Golongan',
                                        'name' => 'gol_ruang',
                                        'options' => [
                                            'I' => 'Golongan I',
                                            'II' => 'Golongan II',
                                            'III' => 'Golongan III',
                                            'IV' => 'Golongan IV',
                                        ],
                                    ],
                                    [
                                        'label' => 'Bidang',
                                        'name' => 'bidang',
                                        'options' => $bidangOptions,
                                    ],
                                ];
                            @endphp

                            @foreach ($filters as $filter)
                                <x-ui.filter-group label="{{ $filter['label'] }}" name="{{ $filter['name'] }}"
                                    :options="$filter['options']" :selected="request($filter['name'], [])" />
                            @endforeach

                            {{-- Action Buttons --}}
                            <div class="flex items-center gap-2 pt-3 border-t border-default-medium">
                                <button type="submit"
                                    class="flex-1 px-4 py-2 text-xs font-medium text-white bg-primary rounded hover:bg-primary/90 transition-colors">
                                    Terapkan
                                </button>
                                <a href="{{ route('admin.pegawai.index') }}"
                                    class="flex-1 px-4 py-2 text-xs font-medium text-center text-body bg-neutral-secondary-medium rounded hover:bg-neutral-tertiary-medium transition-colors">
                                    Reset
                                </a>
                            </div>
                        </x-ui.dropdown-filter>
                    </form>
                </div>

                <a href="{{ route('admin.pegawai.create') }}"
                    class="inline-flex items-center gap-2 text-white bg-primary box-border border border-transparent hover:bg-primary/90 duration-200 transition-all focus:ring-4 focus:ring-primary-medium shadow-xs font-medium leading-5 rounded-base text-sm px-4 py-2.5 focus:outline-none cursor-pointer">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" class="size-4"
                        xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                        <path
                            d="M8.5 2.75C8.5 2.55109 8.42098 2.36032 8.28033 2.21967C8.13968 2.07902 7.94891 2 7.75 2C7.55109 2 7.36032 2.07902 7.21967 2.21967C7.07902 2.36032 7 2.55109 7 2.75V7H2.75C2.55109 7 2.36032 7.07902 2.21967 7.21967C2.07902 7.36032 2 7.55109 2 7.75C2 7.94891 2.07902 8.13968 2.21967 8.28033C2.36032 8.42098 2.55109 8.5 2.75 8.5H7V12.75C7 12.9489 7.07902 13.1397 7.21967 13.2803C7.36032 13.421 7.55109 13.5 7.75 13.5C7.94891 13.5 8.13968 13.421 8.28033 13.2803C8.42098 13.1397 8.5 12.9489 8.5 12.75V8.5H12.75C12.9489 8.5 13.1397 8.42098 13.2803 8.28033C13.421 8.13968 13.5 7.94891 13.5 7.75C13.5 7.55109 13.421 7.36032 13.2803 7.21967C13.1397 7.07902 12.9489 7 12.75 7H8.5V2.75Z"
                            fill="currentColor" />
                    </svg>
                    <span class="font-bold text-xs">Tambah Pegawai</span>
                </a>
            </div>

            <x-pegawai.table :pegawai="$pegawai" />
        </div>
    </section>
    {{ $pegawai->links() }}
@endsection

@push('scripts')
    <script>
        // Filter Form Auto-Submit Module
        class FilterFormManager {
            constructor(formSelector = '#filterForm', searchInputSelector = '#pegawai-search') {
                this.form = document.querySelector(formSelector);
                this.searchInput = document.querySelector(searchInputSelector);
                this.debounceDelay = 500;
                this.searchTimeout = null;

                if (!this.form) {
                    console.warn('Filter form not found');
                    return;
                }

                this.init();
            }

            init() {
                // Setup search input listener with debounce
                if (this.searchInput) {
                    this.searchInput.addEventListener('keyup', () => this.handleSearchInput());
                }

                // Setup filter select listeners
                const filterSelects = this.form.querySelectorAll('select');
                filterSelects.forEach(select => {
                    select.addEventListener('change', () => this.submitForm());
                });
            }

            handleSearchInput() {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => this.submitForm(), this.debounceDelay);
            }

            submitForm() {
                if (this.form) {
                    this.form.submit();
                }
            }
        }

        // Initialize on DOM ready
        document.addEventListener('DOMContentLoaded', () => {
            new FilterFormManager();
        });
    </script>
@endpush
