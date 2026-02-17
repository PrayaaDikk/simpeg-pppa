@props(['title', 'value'])

<div>
    <dt class="text-sm font-medium text-gray-500 mb-1">{{ $title }}</dt>
    <dd class="text-sm text-gray-900">{{ $value ?? '-' }}</dd>
</div>
