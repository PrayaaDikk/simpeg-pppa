@props(['headers' => [], 'footers' => []])

<div
    {{ $attributes->merge(['class' => 'w-full overflow-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default']) }}>
    <table class="w-full text-sm text-left rtl:text-right text-body">
        {{-- Table Header --}}
        @if (count($headers) > 0)
            <thead class="text-sm text-body bg-neutral-secondary-soft border-b rounded-base border-default">
                <tr>
                    @foreach ($headers as $header)
                        <th scope="col" class="px-6 py-3 font-medium">
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
            <tfoot class="text-sm text-body bg-neutral-secondary-soft border-b rounded-base border-default">
                <tr>
                    @foreach ($footers as $footer)
                        <th scope="col" class="px-6 py-3 font-medium">
                            {{ $footer }}
                        </th>
                    @endforeach
                </tr>
            </tfoot>
        @endif
    </table>
</div>
