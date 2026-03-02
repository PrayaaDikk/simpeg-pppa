@props(['headers' => [], 'footers' => [], 'overflow' => false])

{{-- 
    Jika overflow true, kita beri max-height dan overflow-auto pada wrapper-nya. 
    Ini cara paling aman agar kolom (td) tidak berantakan.
--}}
<div
    {{ $attributes->merge([
        'class' =>
            'w-full bg-neutral-primary-soft shadow-xs rounded-base border border-default ' .
            ($overflow ? 'max-h-75 overflow-auto' : ''),
    ]) }}>
    <table class="w-full text-sm text-left rtl:text-right text-body">
        {{-- Table Header --}}
        @if (count($headers) > 0)
            {{-- 
                Jika overflow true, kita buat header menjadi 'sticky' 
                supaya saat body di-scroll, header tetap kelihatan di atas.
            --}}
            <thead
                class="text-sm text-body bg-neutral-secondary-soft border-b border-default {{ $overflow ? 'sticky top-0 z-10' : '' }}">
                <tr>
                    @foreach ($headers as $header)
                        <th scope="col" class="px-6 py-3 font-medium bg-neutral-secondary-soft">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
        @endif

        {{-- Table Body --}}
        <tbody>
            {{ $slot }}
        </tbody>

        {{-- Table Footer --}}
        @if (count($footers) > 0)
            <tfoot
                class="text-sm text-body bg-neutral-secondary-soft border-t border-default {{ $overflow ? 'sticky bottom-0' : '' }}">
                <tr>
                    @foreach ($footers as $footer)
                        <th scope="col" class="px-6 py-3 font-medium bg-neutral-secondary-soft">
                            {{ $footer }}
                        </th>
                    @endforeach
                </tr>
            </tfoot>
        @endif
    </table>
</div>
