@props([
    'name' => '',
    'label' => '',
    'value' => '',
    'options' => [],
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

    <select id="{{ $name }}" name="{{ $name }}"
        class="block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-primary focus:border-primary shadow-xs cursor-pointer @error($name) border-red-500 @enderror"
        {{ $required ? 'required' : '' }} {{ $disabled ? 'disabled' : '' }}>
        <option value="">Pilih {{ $label }}</option>
        @foreach ($options as $option)
            <option value="{{ $option['value'] }}" {{ old($name, $value) == $option['value'] ? 'selected' : '' }}>
                {{ $option['label'] }}
            </option>
        @endforeach
    </select>
</div>
