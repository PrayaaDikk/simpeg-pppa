@props([
    'name' => '',
    'label' => '',
    'placeholder' => '',
    'value' => '',
    'required' => false,
    'rows' => 3,
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

    <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}"
        class="bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-primary focus:border-primary block w-full px-3 py-2.5 shadow-xs placeholder:text-muted resize-none @error($name) border-red-500 @enderror"
        placeholder="{{ $placeholder }}" {{ $required ? 'required' : '' }} {{ $disabled ? 'disabled' : '' }}>{{ old($name, $value) }}</textarea>
</div>
