@props([
    'title',
    'image',
    'imageTitle' => '',
])

<li>
    <article {{ $attributes->merge(['class' => 'flex w-full items-center gap-6 rounded-2xl border border-gray-200 bg-white p-6 transition hover:border-accent/20 sm:gap-10 sm:p-8']) }}>
        <x-admin-photo-thumb :src="$image" :title="$imageTitle !== '' ? $imageTitle : $title" />

        <div class="min-w-0 flex-1 flex flex-col justify-center gap-1.5 sm:gap-2">
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

        <div class="ml-auto flex shrink-0 flex-col items-end justify-center gap-2 self-center pl-6 sm:flex-row sm:items-center sm:gap-4 sm:pl-8">
            {{ $actions }}
        </div>
    </article>
</li>
