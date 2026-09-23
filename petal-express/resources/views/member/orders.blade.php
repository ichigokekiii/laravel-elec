<x-app-layout>
    <x-slot name="header">
        <h2 class="font-playfair font-bold text-2xl text-gray-900 leading-tight">My Orders</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-[2rem] border border-gray-100">
                <div class="p-8 text-gray-900 space-y-4">
                    <p class="text-gray-600 font-light">Sample order history for your Petal Express member account.</p>
                    @forelse ($orders as $order)
                        <div class="rounded-[1.5rem] border border-gray-100 px-5 py-4 flex justify-between gap-4">
                            <div>
                                <p class="font-playfair text-lg font-semibold">{{ $order['code'] }}</p>
                                <p class="text-sm text-gray-500">{{ $order['status'] }}</p>
                            </div>
                            <p class="font-semibold text-accent">{{ $order['total'] }}</p>
                        </div>
                    @empty
                        <p>No orders yet.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
