{{-- wrap admin list pages; thumbnails dispatch open-photo-preview --}}
<div
    x-data="{ previewOpen: false, previewSrc: '', previewTitle: '' }"
    @open-photo-preview.window="previewOpen = true; previewSrc = $event.detail.src; previewTitle = $event.detail.title || ''"
    @keydown.escape.window="previewOpen = false"
>
    {{ $slot }}

    <div
        x-show="previewOpen"
        x-cloak
        class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-8"
        role="dialog"
        aria-modal="true"
        :aria-label="previewTitle ? 'Photo preview for ' + previewTitle : 'Photo preview'"
    >
        <div class="absolute inset-0 bg-gray-900/80" @click="previewOpen = false"></div>

        <div class="relative z-10 w-full max-w-4xl rounded-[2rem] border border-gray-100 bg-white p-4 sm:p-6 shadow-xl">
            <div class="flex items-start justify-between gap-4 mb-4">
                <div class="min-w-0">
                    <p class="text-xs font-semibold uppercase tracking-widest text-accent">Watermark preview</p>
                    <p class="font-playfair text-lg font-bold text-gray-900 truncate" x-text="previewTitle"></p>
                </div>
                <button
                    type="button"
                    class="shrink-0 rounded-full border border-gray-200 px-4 py-2 text-xs font-semibold uppercase tracking-wide text-gray-700 hover:text-accent"
                    @click="previewOpen = false"
                >
                    Close
                </button>
            </div>

            <img
                :src="previewSrc"
                alt=""
                class="w-full max-h-[75vh] rounded-[1.5rem] object-contain bg-gray-50"
            >

            <p class="mt-3 text-xs text-gray-500">Look at the bottom-right corner for the petal express watermark.</p>
        </div>
    </div>
</div>
