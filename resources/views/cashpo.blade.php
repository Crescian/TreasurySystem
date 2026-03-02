<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cash Position') }}
        </h2>
    </x-slot>
    <!-- hello -->

    <div class="py-20">
        <div class="max-w-3xl mx-auto text-center">
            <div class="inline-block bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-6 rounded-lg shadow-md">
                <svg class="w-12 h-12 mx-auto mb-4 text-yellow-500" fill="none" stroke="currentColor" stroke-width="2"
                     viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-2xl font-semibold">This Page is Under Development</h3>
                <p class="mt-2 text-sm text-gray-600">We're working hard to finish this feature. Please check back later.</p>
            </div>
        </div>
    </div>
</x-app-layout>
