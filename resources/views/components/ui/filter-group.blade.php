@props(['label', 'name', 'options' => [], 'selected' => null])

<div class="mb-4 last:mb-0">
    <label class="block text-xs font-semibold text-body mb-2">{{ $label }}</label>
    <div class="space-y-1">
        @foreach ($options as $value => $label)
            <label
                class="flex items-center p-2 hover:bg-neutral-tertiary-medium rounded-base cursor-pointer transition-colors">
                <input type="checkbox" name="{{ $name }}[]" value="{{ $value }}"
                    {{ in_array($value, (array) $selected) ? 'checked' : '' }}
                    class="w-4 h-4 text-primary border-gray-300 rounded-base focus:ring-primary">
                <span class="ml-2 text-sm text-body">{{ $label }}</span>
            </label>
        @endforeach
    </div>
</div>
