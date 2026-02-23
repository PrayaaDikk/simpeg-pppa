<li class="me-2">
    <a {{ $attributes->merge(['class' => 'inline-block p-4 rounded-t-base transition-all duration-100 ' . ($active ? 'bg-secondary active text-heading' : 'hover:text-heading hover:bg-gray-100 bg-white')]) }}
        aria-current="page">{{ $slot }}</a>
</li>
