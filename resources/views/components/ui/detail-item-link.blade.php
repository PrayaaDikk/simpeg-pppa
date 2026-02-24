@props(['title', 'value', 'link'])

<div>
    <dt class="text-sm font-medium text-gray-500 mb-1">{{ $title }}</dt>
    <a href="{{ $link }}" class="text-sm text-gray-900 hover:underline">{{ $value ?? '-' }}</a>
</div>
