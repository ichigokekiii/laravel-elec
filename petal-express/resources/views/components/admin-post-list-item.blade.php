@props([
    'title',
    'image',
    'imageTitle' => '',
])

<li>
    <article {{ $attributes->merge(['class' => 'flex w-full flex-col gap-4 rounded-2xl border border-gray-200 bg-white p-4 transition hover:border-accent/30 sm:flex-row sm:items-center sm:gap-5 sm:p-5']) }}>
        <x-admin-photo-thumb :src="$image" :title="$imageTitle !== '' ? $imageTitle : $title" />

        <div class="min-w-0 flex-1 flex flex-col justify-center gap-0.5">
            @isset($meta)
                <p class="text-xs font-medium uppercase tracking-wide text-gray-500">
                    {{ $meta }}
                </p>
            @endisset

            <h3 class="font-playfair text-lg font-semibold leading-snug text-gray-900 sm:text-xl truncate">
                {{ $title }}
            </h3>

            @isset($subline)
                <div class="text-sm font-semibold text-accent sm:text-base">
                    {{ $subline }}
                </div>
            @endisset

            @isset($note)
                <p class="text-sm text-gray-600">
                    {{ $note }}
                </p>
            @endisset
        </div>

        <div class="flex shrink-0 flex-wrap items-center gap-1 sm:justify-end">
            {{ $actions }}
        </div>
    </article>
</li>
