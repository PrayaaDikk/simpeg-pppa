@props(['src' => null, 'alt' => 'Photo', 'size' => 'md'])

@php
    $sizes = [
        'sm' => 'h-16 w-16',
        'md' => 'h-24 w-24',
        'lg' => 'h-32 w-32',
        'xl' => 'h-40 w-40',
    ];
    $sizeClass = $sizes[$size] ?? $sizes['md'];
@endphp

<div
    class="{{ $sizeClass }} border-2 bg-white border-muted rounded-lg overflow-hidden flex items-center justify-center">
    @if ($src)
        <img src="{{ route('pegawai.showFile', $src) }}" alt="{{ $alt }}" class="w-full h-full object-cover">
    @else
        <svg class="w-1/2 h-1/2 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
        </svg>
    @endif
</div>
