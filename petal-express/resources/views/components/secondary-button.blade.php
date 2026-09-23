<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center rounded-full border border-gray-200 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 hover:text-accent focus:outline-none focus:ring-2 focus:ring-accent/20 focus:ring-offset-2 transition']) }}>
    {{ $slot }}
</button>
