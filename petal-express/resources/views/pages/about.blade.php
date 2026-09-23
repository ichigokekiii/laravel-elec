<x-site-layout title="About">
    <section class="bg-page pt-24 md:pt-28 pb-24 px-6 md:px-12">
        <div class="max-w-content mx-auto lg:grid lg:grid-cols-2 lg:gap-16 lg:items-start">
            <div>
                <x-page-banner
                    eyebrow="Our Story"
                    title="Explore our"
                    script="humble beginnings"
                >
                    <p>
                        Petal Express started as a small flower desk for friends and classmates who needed
                        same-day blooms without the mall chaos. We still keep things simple. Honest pricing,
                        careful packing, and deliveries that show up when you need them.
                    </p>
                </x-page-banner>

                <a href="{{ route('bouquets.index') }}" class="inline-flex rounded-full bg-gray-900 text-white px-10 py-4 text-sm font-semibold uppercase tracking-wide border-2 border-gray-900 hover:bg-transparent hover:text-gray-900 transition">
                    See the collection
                </a>
            </div>

            <div class="mt-12 lg:mt-0 grid grid-cols-2 gap-4">
                <div class="row-span-3 overflow-hidden rounded-[2rem] min-h-[320px]">
                    <img src="https://images.unsplash.com/photo-1487530811176-3780de880c2d?q=80&w=900" alt="Studio flowers" class="h-full w-full object-cover">
                </div>
                @foreach ([
                    ['num' => '3+', 'label' => 'Years arranging'],
                    ['num' => '1 day', 'label' => 'Fast delivery'],
                    ['num' => '100%', 'label' => 'Hand finished'],
                ] as $stat)
                    <div class="border-2 border-gray-900 rounded-2xl p-5 bg-white">
                        <p class="font-playfair text-2xl md:text-4xl font-bold text-gray-900">{{ $stat['num'] }}</p>
                        <p class="mt-2 text-[10px] font-bold uppercase tracking-widest text-gray-500">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-white border-y border-gray-100 py-24 px-6 md:px-12">
        <div class="max-w-content mx-auto max-w-3xl">
            <p class="text-sm font-semibold uppercase tracking-widest text-accent mb-3">FAQs</p>
            <h2 class="font-playfair text-4xl md:text-5xl font-bold text-gray-900 mb-10">Quick answers</h2>

            <div class="space-y-4">
                <details class="rounded-[1.5rem] border border-gray-200 open:border-gray-900 bg-page px-8 py-2">
                    <summary class="cursor-pointer py-6 font-playfair text-xl font-semibold text-gray-900">Do you deliver same day?</summary>
                    <p class="pb-6 text-gray-600 leading-relaxed">Yes in selected Metro Manila areas when you order early enough.</p>
                </details>
                <details class="rounded-[1.5rem] border border-gray-200 open:border-gray-900 bg-page px-8 py-2">
                    <summary class="cursor-pointer py-6 font-playfair text-xl font-semibold text-gray-900">Is this the same as petalexpress.shop?</summary>
                    <p class="pb-6 text-gray-600 leading-relaxed">Yes. John (me) is one of main developers on that website, he's also the one who implemented the React + laravel stack for it.</p>
                </details>
            </div>
        </div>
    </section>
</x-site-layout>
