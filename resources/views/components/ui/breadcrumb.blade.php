@unless ($breadcrumbs->isEmpty())
    <nav class="flex mb-4 max-md:hidden" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
            @foreach ($breadcrumbs as $breadcrumb)
                @if ($breadcrumb->url && !$loop->last)
                    {{-- Breadcrumb with link --}}
                    <li class="inline-flex items-center">
                        @if ($loop->first)
                            {{-- First item with home icon --}}
                            <a href="{{ $breadcrumb->url }}"
                                class="inline-flex items-center text-sm font-medium text-body hover:text-primary transition-colors">
                                <svg class="w-4 h-4 me-1.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
                                    height="24" fill="none" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2"
                                        d="m4 12 8-8 8 8M6 10.5V19a1 1 0 0 0 1 1h3v-3a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3h3a1 1 0 0 0 1-1v-8.5" />
                                </svg>
                                {{ $breadcrumb->title }}
                            </a>
                        @else
                            {{-- Other items with separator --}}
                            <div class="flex items-center space-x-1.5">
                                <svg class="w-3.5 h-3.5 rtl:rotate-180 text-body" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m9 5 7 7-7 7" />
                                </svg>
                                <a href="{{ $breadcrumb->url }}"
                                    class="inline-flex items-center text-sm font-medium text-body hover:text-primary transition-colors">
                                    {{ $breadcrumb->title }}
                                </a>
                            </div>
                        @endif
                    </li>
                @else
                    {{-- Current/Last item (no link) --}}
                    <li aria-current="page">
                        <div class="flex items-center space-x-1.5">
                            @if (!$loop->first)
                                <svg class="w-3.5 h-3.5 rtl:rotate-180 text-body" aria-hidden="true"
                                    xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none"
                                    viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="m9 5 7 7-7 7" />
                                </svg>
                            @endif
                            <span class="inline-flex items-center text-sm font-medium text-body-subtle">
                                {{ $breadcrumb->title }}
                            </span>
                        </div>
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endunless
