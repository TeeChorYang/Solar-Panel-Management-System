<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Order Details') }}
    </h2>
    <script src="{{ asset('js/toggle-disclosure.js') }}" defer></script>
</x-slot>

<div class="relative bg-white">
    <div class="relative max-w-7xl mx-auto lg:grid lg:grid-cols-5">
        <div class="bg-gray-50 py-16 px-4 sm:px-6 lg:col-span-2 lg:px-8 lg:py-24 xl:pr-12">
            <div class="max-w-lg mx-auto">
                {{ $this->displayProductInfoList }}
            </div>
        </div>
        <div class="bg-white py-16 px-4 sm:px-6 lg:col-span-3 lg:py-24 lg:px-8 xl:pl-12">
            <div class="max-w-lg mx-auto lg:max-w-none">
                {{ $this->displayOrderInfoList }}
            </div>
            <div class="border-t divide-y divide-gray-200 mt-4">
                <div>
                    <h3>
                        <!-- Expand/collapse button -->
                        <button type="button"
                            class="group relative w-full py-6 flex justify-between items-center text-left"
                            aria-controls="disclosure-1" aria-expanded="false"
                            onclick="toggleDisclosure('disclosure-1', this)">

                            <span class="text-gray-900 text-sm font-medium"> Amount Structure </span>
                            <span class="ml-6 flex items-center">

                                <svg class="block h-6 w-6 text-yellow-400 group-hover:text-yellow-600 plus-icon"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                </svg>

                                <svg class="hidden h-6 w-6 text-gray-400 group-hover:text-gray-600 minus-icon"
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M18 12H6" />
                                </svg>
                            </span>
                        </button>
                    </h3>
                    <div class="prose prose-sm hidden" id="disclosure-1">
                        {{ $this->displayAmountInfoList }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
