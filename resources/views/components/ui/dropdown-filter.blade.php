@props([
    'title' => 'Filter',
    'filters' => [],
])

<div class="relative" x-data="{ open: false }" @click.away="open = false">
    {{-- Trigger Button --}}
    <button @click="open = !open" type="button" id="dropdownDefaultButton" data-dropdown-toggle="dropdown"
        class="cursor-pointer shrink-0 inline-flex items-center justify-center text-muted bg-neutral-secondary-medium box-border border border-default-medium hover:bg-neutral-tertiary-medium hover:text-primary focus:ring-4 focus:ring-neutral-tertiary shadow-xs font-medium leading-5 rounded-base text-sm px-3 py-2.5 focus:outline-none"
        type="button">
        <svg class="w-4 h-4 me-1.5 -ms-0.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24"
            height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="currentColor" stroke-linecap="round" stroke-width="2"
                d="M18.796 4H5.204a1 1 0 0 0-.753 1.659l5.302 6.058a1 1 0 0 1 .247.659v4.874a.5.5 0 0 0 .2.4l3 2.25a.5.5 0 0 0 .8-.4v-7.124a1 1 0 0 1 .247-.659l5.302-6.059c.566-.646.106-1.658-.753-1.658Z" />
        </svg>
        Filter
    </button>

    {{-- Dropdown Menu --}}
    <div x-show="open" x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute left-0 z-50 mt-2 w-64 max-h-60 overflow-y-auto bg-neutral-primary-medium border border-default-medium rounded-base shadow-lg"
        style="display: none;">

        <div class="p-3">
            {{ $slot }}
        </div>
    </div>
</div>
