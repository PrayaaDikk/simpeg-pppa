@extends('layouts.admin-app')

@section('content')
    {{-- Header --}}
    <x-ui.header>Informasi Cuti Pegawai DP3A Kota Kendari</x-ui.header>

    {{-- BreadCrumb --}}
    <x-ui.breadcrumb :breadcrumbs="Breadcrumbs::generate('admin.cuti.index')" />

    {{-- Main Content --}}
    <section>
        @if (session('success'))
            <div class="my-5 p-4 bg-green-100 text-green-700 rounded-base">
                <p class="list-disc list-inside">
                    {{ session('success') }}
                </p>
            </div>
        @endif

        <div class="bg-white shadow-sm rounded-lg ">
            <div class="flex items-center justify-between flex-wrap mb-2 p-6 gap-4">
                {{-- Search and Filter --}}
                <div class="inline-flex items-center justify-between gap-2 flex-wrap">
                    <form method="GET" action="{{ route('admin.cuti.index') }}" id="filterForm"
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
                                        'label' => 'Jenis Cuti',
                                        'name' => 'jenis_cuti',
                                        'options' => [
                                            'Tahunan' => 'Cuti Tahunan',
                                            'Besar' => 'Cuti Besar',
                                            'Sakit' => 'Cuti Sakit',
                                            'Melahirkan' => 'Cuti Melahirkan',
                                            'Alasan Penting' => 'Cuti Alasan Penting',
                                            'Diluar Tanggungan Negara' => 'Cuti Di Luar Tanggungan Negara',
                                        ],
                                    ],
                                    [
                                        'label' => 'Status Cuti',
                                        'name' => 'status_cuti',
                                        'options' => [
                                            'Menunggu' => 'Menunggu',
                                            'Disetujui' => 'Disetujui',
                                            'Perubahan' => 'Perubahan',
                                            'Ditangguhkan' => 'Ditangguhkan',
                                            'Tidak disetujui' => 'Tidak disetujui',
                                        ],
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
            </div>


            <x-admin.cuti.table :cuti="$cuti" />
        </div>
    </section>
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
