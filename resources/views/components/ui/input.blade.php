@props([
    'type' => 'text',
    'name' => '',
    'label' => '',
    'placeholder' => '',
    'value' => '',
    'required' => false,
    'disabled' => false,
])

<div {{ $attributes->merge(['class' => 'w-full']) }}>
    @if ($label)
        <label for="{{ $name }}" class="block mb-1.5 text-sm font-medium text-heading">
            {{ $label }}
            @if ($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative">
        <input type="{{ $type }}" id="{{ $name }}" name="{{ $name }}"
            class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base peer focus:ring-primary focus:border-primary block w-full px-3 py-2.5 shadow-xs placeholder:text-muted @error($name) border-red-500 @enderror"
            placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }} value="{{ old($name, $value) }}"
            {{ $disabled ? 'disabled' : '' }} />

        @if (isset($icon))
            <div
                class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none peer-focus-within:text-primary">
                {!! $icon !!}
            </div>
        @endif
    </div>
</div>
