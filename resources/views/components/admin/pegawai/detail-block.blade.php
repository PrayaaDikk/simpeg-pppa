@props(['title'])

<div class="border border-default rounded-lg p-6">
    <h3 class="text-lg font-semibold text-heading mb-6 pb-3 border-b border-default">
        {{ $title }}
    </h3>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{ $slot }}
    </div>
</div>
