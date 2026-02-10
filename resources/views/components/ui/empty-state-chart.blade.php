@props(['message' => 'Data tidak tersedia', 'icon' => null])

<div
    {{ $attributes->merge(['class' => 'flex-1 flex flex-col items-center justify-center text-center text-sm text-gray-500 py-8']) }}>
    @if ($icon)
        {{ $icon }}
    @else
        <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M11 3v18m4-14v14M3 11h18" />
        </svg>
    @endif
    <p>{{ $message }}</p>
</div>
