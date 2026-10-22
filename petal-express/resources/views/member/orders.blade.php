<x-member-page
    heading="My orders"
    intro="Delivery tracking will appear here once checkout is connected. For now, browse the showcase or send a custom request."
>
    <div class="rounded-xl border border-gray-200 bg-white px-6 py-10 sm:px-8 sm:py-12 text-left">
        <p class="font-playfair text-2xl font-semibold text-gray-900">No deliveries yet</p>
        <p class="mt-3 max-w-md text-sm leading-relaxed text-gray-600">
            When you order through Petal Express, your receipt and status will list here.
        </p>
        <div class="mt-8 flex flex-col sm:flex-row sm:items-center gap-3">
            <a
                href="{{ route('bouquets.index') }}"
                class="inline-flex min-h-11 w-full sm:w-auto items-center justify-center rounded-full bg-gray-900 px-8 text-xs font-semibold uppercase tracking-wide text-white border-2 border-gray-900 transition active:scale-[0.98] hover:bg-transparent hover:text-gray-900"
            >
                View showcase
            </a>
            @if (Auth::user()->isUser())
                <a
                    href="{{ route('posts.submit') }}"
                    class="inline-flex min-h-11 w-full sm:w-auto items-center justify-center rounded-full border border-gray-300 bg-white px-8 text-xs font-semibold uppercase tracking-wide text-gray-700 transition active:scale-[0.98] hover:border-accent hover:text-accent"
                >
                    Custom order
                </a>
            @endif
        </div>
    </div>
</x-member-page>
