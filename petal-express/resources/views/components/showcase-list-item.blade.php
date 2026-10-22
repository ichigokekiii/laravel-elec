@props([
    'href',
    'title',
    'image',
    'alt' => '',
])

<li>
    <a
        href="{{ $href }}"
        {{ $attributes->merge(['class' => 'group flex w-full items-center gap-6 rounded-2xl border border-gray-200 bg-white p-6 transition hover:border-accent/25 active:scale-[0.99] sm:gap-10 sm:p-8']) }}
    >
        <img
            src="{{ $image }}"
            alt="{{ $alt !== '' ? $alt : $title }}"
            class="h-20 w-20 shrink-0 rounded-xl object-cover border border-gray-100 sm:h-24 sm:w-24"
        >

        <div class="min-w-0 flex-1 flex flex-col justify-center gap-1.5 sm:gap-2">
            @isset($meta)
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    {{ $meta }}
                </p>
            @endisset

            <h3 class="font-playfair text-lg font-semibold leading-snug text-gray-900 group-hover:text-accent transition-colors sm:text-xl truncate">
                {{ $title }}
            </h3>

            @isset($subline)
                <p class="text-sm font-semibold text-accent sm:text-base">
                    {{ $subline }}
                </p>
            @endisset

            @isset($note)
                <p class="text-sm text-gray-600">
                    {{ $note }}
                </p>
            @endisset
        </div>

        @isset($actions)
            <div class="ml-auto flex shrink-0 flex-col items-end justify-center gap-2 self-center pl-6 sm:flex-row sm:items-center sm:gap-4 sm:pl-8">
                {{ $actions }}
            </div>
        @else
            <span class="ml-auto flex shrink-0 items-center self-center min-h-11 pl-6 text-xs font-semibold uppercase tracking-wide text-gray-400 group-hover:text-accent sm:pl-8">
                View
            </span>
        @endif
    </a>
</li>
