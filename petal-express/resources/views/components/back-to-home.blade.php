<a
    href="{{ route('home') }}"
    {{ $attributes->merge(['class' => 'inline-flex min-h-11 items-center gap-2 text-sm font-medium text-gray-600 transition hover:text-accent']) }}
>
    <svg class="h-4 w-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
    Back to home
</a>
