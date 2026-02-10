@props(['class' => ''])

<svg
    {{ $attributes->merge([
        'class' => 'w-5 h-5 transition duration-75 ' . $class,
        'aria-hidden' => 'true',
    ]) }}>
    {{ $slot }}
</svg>
