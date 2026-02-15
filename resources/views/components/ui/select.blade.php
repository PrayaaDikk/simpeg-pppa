@props([
    'attributes' => [],
    'name' => '',
    'label' => '',
    'options' => [],
    'required' => false,
])

<div class="w-full">
    @if ($required)
        <label for="{{ $name }}" class="block mb-1.5 text-sm font-medium text-heading">{{ $label }} <span
                class="text-muted">*</span></label>
    @else
        <label for="{{ $name }}" class="block mb-1.5 text-sm font-medium text-heading">{{ $label }}</label>
    @endif

    <select id="{{ $name }}" name="{{ $name }}"
        {{ $attributes->merge(['class' => 'block w-full px-3 py-2.5 bg-neutral-secondary-medium border border-default-medium text-heading text-sm rounded-base focus:ring-primary focus:border-primary shadow-xs placeholder:text-muted cursor-pointer']) }}
        {{ $required ? 'required' : '' }}>
        @foreach ($options as $option)
            <option value="{{ $option['value'] }}" {{ old($name) == $option['value'] ? 'selected' : '' }}>
                {{ $option['label'] }}</option>
        @endforeach
    </select>
</div>
