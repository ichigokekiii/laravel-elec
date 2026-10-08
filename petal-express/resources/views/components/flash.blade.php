@php
    $flashMessage = null;
    $flashKind = null;

    if (session('error')) {
        $flashKind = 'error';
        $flashMessage = session('error');
    } elseif ($errors->any()) {
        $flashKind = 'error';
        $flashMessage = 'Please fix the highlighted fields.';
    } elseif (session('success')) {
        $flashKind = 'success';
        $flashMessage = session('success');
    }
@endphp

@if ($flashMessage)
    <div
        x-data="{ show: true }"
        x-show="show"
        x-init="setTimeout(() => show = false, 4000)"
        x-transition.opacity.duration.300ms
        x-cloak
        role="status"
        class="fixed top-20 right-4 z-50 w-[min(100%-2rem,22rem)]"
    >
        <div class="flex items-start gap-3 rounded-2xl bg-white px-4 py-3 shadow-lg shadow-gray-900/10 {{ $flashKind === 'error' ? 'border border-red-200' : 'border border-accent/40' }}">
            <p class="flex-1 text-sm text-gray-800">{{ $flashMessage }}</p>
            <button type="button" @click="show = false" aria-label="Close" class="shrink-0 text-gray-500 hover:text-gray-900">
                <svg class="h-4 w-4" viewBox="0 0 20 20" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" d="M5 5l10 10M15 5L5 15"/>
                </svg>
            </button>
        </div>
    </div>
@endif
