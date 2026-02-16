@props([
    'attributes' => [],
    'type' => 'text',
    'name' => '',
    'label' => '',
    'placeholder' => '',
    'required' => false,
])

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @if ($required)
        <label for="{{ $name }}" class="block mb-1.5 text-sm font-medium text-heading">{{ $label }}
            <span class="text-muted">*</span></label>
    @else
        <label for="{{ $name }}" class="block mb-1.5 text-sm font-medium text-heading">{{ $label }}</label>
    @endif

    <div class="relative">
        <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base peer focus:ring-primary focus:border-primary block w-full px-3 py-2.5 shadow-xs placeholder:text-muted"
            placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }} value="{{ old($name) }}" />
        @if (isset($icon))
            <div
                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none peer-focus-within:text-primary">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
