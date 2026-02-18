@props([
    'action',
    'title' => 'Hapus',
    'message' => 'Apakah Anda yakin ingin menghapus data ini?',
    'confirmText' => 'Ya, hapus',
    'cancelText' => 'Batal',
])

@php
    $modalId = 'delete-modal-' . uniqid();
@endphp

<div x-data="{ loading: false }">
    {{-- Trigger Button --}}
    <button type="button" data-modal-target="{{ $modalId }}" data-modal-toggle="{{ $modalId }}"
        class="cursor-pointer inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-danger border border-transparent rounded-base hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium shadow-xs focus:outline-none transition-colors">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
        </svg>
        <span>{{ $title }}</span>
    </button>

    {{-- Modal --}}
    <div id="{{ $modalId }}" tabindex="-1"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white border border-gray-200 rounded-lg shadow-lg">
                {{-- Close Button --}}
                <button type="button"
                    class="cursor-pointer absolute top-3 right-3 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center"
                    data-modal-hide="{{ $modalId }}">
                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>

                {{-- Modal Content --}}
                <div class="p-6 text-center">
                    <svg class="mx-auto mb-4 text-gray-400 w-12 h-12" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>

                    <h3 class="mb-5 text-lg font-normal text-gray-500">
                        {{ $message }}
                    </h3>

                    <div class="flex items-center justify-center gap-4">
                        <form action="{{ $action }}" method="POST" @submit="loading = true">
                            @csrf
                            @method('DELETE')

                            <button type="submit" :disabled="loading"
                                class="cursor-pointer text-white bg-danger hover:bg-danger-strong focus:ring-4 focus:ring-danger-medium font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center transition-colors"
                                :class="{ 'opacity-50 cursor-not-allowed': loading }">
                                <span x-text="loading ? 'Menghapus...' : '{{ $confirmText }}'"></span>
                            </button>
                        </form>

                        <button type="button" data-modal-hide="{{ $modalId }}"
                            class="cursor-pointer text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 transition-colors">
                            {{ $cancelText }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
