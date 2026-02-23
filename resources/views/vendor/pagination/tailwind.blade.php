@if ($paginator->hasPages())
    <nav aria-label="Page navigation" class="flex items-center flex-col mt-8 gap-2">
        <div>
            <p class="text-xs text-muted">
                {!! __('Menampilkan') !!}
                <span class="fw-semibold">{{ $paginator->firstItem() }}</span>
                {!! __('sampai') !!}
                <span class="fw-semibold">{{ $paginator->lastItem() }}</span>
                {!! __('dari total') !!}
                <span class="fw-semibold">{{ $paginator->total() }}</span>
                {!! __('hasil') !!}
            </p>
        </div>

        <ul class="flex justify-center -space-x-px text-sm">
            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li>
                    <span
                        class="flex items-center justify-center text-gray-400 bg-neutral-secondary-medium box-border border border-default-medium font-medium rounded-s-base text-sm px-3 h-10 cursor-not-allowed">
                        <svg class="w-4 h-4 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m15 19-7-7 7-7" />
                        </svg>

                    </span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        class="flex items-center justify-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading font-medium rounded-s-base text-sm px-3 h-10 focus:outline-none transition-colors">
                        <svg class="w-4 h-4 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m15 19-7-7 7-7" />
                        </svg>
                    </a>
                </li>
            @endif

            {{-- Pagination Elements --}}
            @php
                $currentPage = $paginator->currentPage();
                $lastPage = $paginator->lastPage();
            @endphp

            {{-- First Page --}}
            <li>
                @if ($currentPage == 1)
                    <span aria-current="page"
                        class="flex items-center justify-center text-fg-brand bg-neutral-tertiary-medium box-border border border-default-medium font-medium text-sm w-10 h-10 focus:outline-none">
                        1
                    </span>
                @else
                    <a href="{{ $paginator->url(1) }}"
                        class="flex items-center justify-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading font-medium text-sm w-10 h-10 focus:outline-none transition-colors">
                        1
                    </a>
                @endif
            </li>

            {{-- Dots before current page if needed --}}
            @if ($currentPage > 3)
                <li>
                    <span
                        class="flex items-center justify-center text-body bg-neutral-secondary-medium box-border border border-default-medium font-medium text-sm w-10 h-10">
                        ...
                    </span>
                </li>
            @endif

            {{-- Page before current (if exists and not page 1) --}}
            @if ($currentPage > 2)
                <li>
                    <a href="{{ $paginator->url($currentPage - 1) }}"
                        class="flex items-center justify-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading font-medium text-sm w-10 h-10 focus:outline-none transition-colors">
                        {{ $currentPage - 1 }}
                    </a>
                </li>
            @endif

            {{-- Current Page (if not first or last) --}}
            @if ($currentPage != 1 && $currentPage != $lastPage)
                <li>
                    <span aria-current="page"
                        class="flex items-center justify-center text-fg-brand bg-neutral-tertiary-medium box-border border border-default-medium font-medium text-sm w-10 h-10 focus:outline-none">
                        {{ $currentPage }}
                    </span>
                </li>
            @endif

            {{-- Page after current (if exists and not last page) --}}
            @if ($currentPage < $lastPage - 1)
                <li>
                    <a href="{{ $paginator->url($currentPage + 1) }}"
                        class="flex items-center justify-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading font-medium text-sm w-10 h-10 focus:outline-none transition-colors">
                        {{ $currentPage + 1 }}
                    </a>
                </li>
            @endif

            {{-- Dots after current page if needed --}}
            @if ($currentPage < $lastPage - 2)
                <li>
                    <span
                        class="flex items-center justify-center text-body bg-neutral-secondary-medium box-border border border-default-medium font-medium text-sm w-10 h-10">
                        ...
                    </span>
                </li>
            @endif

            {{-- Last Page (if more than 1 page) --}}
            @if ($lastPage > 1)
                <li>
                    @if ($currentPage == $lastPage)
                        <span aria-current="page"
                            class="flex items-center justify-center text-fg-brand bg-neutral-tertiary-medium box-border border border-default-medium font-medium text-sm w-10 h-10 focus:outline-none">
                            {{ $lastPage }}
                        </span>
                    @else
                        <a href="{{ $paginator->url($lastPage) }}"
                            class="flex items-center justify-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading font-medium text-sm w-10 h-10 focus:outline-none transition-colors">
                            {{ $lastPage }}
                        </a>
                    @endif
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                        class="flex items-center justify-center text-body bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-heading font-medium rounded-e-base text-sm px-3 h-10 focus:outline-none transition-colors">
                        <svg class="w-4 h-4 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m9 5 7 7-7 7" />
                        </svg>

                    </a>
                </li>
            @else
                <li>
                    <span
                        class="flex items-center justify-center text-gray-400 bg-neutral-secondary-medium box-border border border-default-medium font-medium rounded-e-base text-sm px-3 h-10 cursor-not-allowed">
                        <svg class="w-4 h-4 rtl:rotate-180" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m9 5 7 7-7 7" />
                        </svg>

                    </span>
                </li>
            @endif
        </ul>
    </nav>
@endif
