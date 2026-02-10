@props(['active' => false])

<li>
    <a
        {{ $attributes->merge([
            'class' =>
                'flex items-center p-2 rounded-sm group transition-colors ' .
                ($active ? 'bg-primary text-white' : 'text-body hover:bg-neutral-tertiary hover:text-primary'),
        ]) }}>

        {{-- Icon --}}
        @isset($icon)
            <div class="shrink-0">
                {{ $icon }}
            </div>
        @endisset

        {{-- Title --}}
        <span class="flex-1 ms-3 whitespace-nowrap select-none">{{ $slot }}</span>
    </a>
</li>
