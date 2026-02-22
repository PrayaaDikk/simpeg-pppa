@props(['title' => null])

<div {{ $attributes->merge(['class' => 'bg-white p-6 shadow-sm rounded-bl-lg rounded-br-lg space-y-6']) }}>
    @if ($title)
        <h2 class="text-xl font-bold text-heading mb-6">{{ $title }}</h2>
    @endif

    {{ $slot }}
</div>
