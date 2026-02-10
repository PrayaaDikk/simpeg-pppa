{{-- Badge --}}
<div class="flex items-center gap-3 max-w-64">

    {{-- Icon --}}
    <div class="bg-linear-to-t from-lavender-light to-lavender rounded-full p-6 ">
        <span class="w-12 h-12 text-[#4A4AFF]">
            {{ $icon }}
        </span>
    </div>

    {{-- Content --}}
    <div>
        <h2 class="text-sm font-bold opacity-60">{{ $title }}</h2>
        <p class="font-semibold text-3xl">{{ $value }}</p>
    </div>
</div>
