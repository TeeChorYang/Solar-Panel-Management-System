<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Make Order Request') }}
    </h2>
</x-slot>

<div class="relative bg-white">
    <div class="relative max-w-7xl mx-auto lg:grid lg:grid-cols-5">
        <div class="bg-gray-50 py-16 px-4 sm:px-6 lg:col-span-2 lg:px-8 lg:py-24 xl:pr-12">
            <div class="max-w-lg mx-auto">
                {{ $this->makeOrderRequestInfoList }}
            </div>
        </div>
        <div class="bg-white py-16 px-4 sm:px-6 lg:col-span-3 lg:py-24 lg:px-8 xl:pl-12">
            <div class="max-w-lg mx-auto lg:max-w-none">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight pb-4">
                    {{ __('Enter Request Quantity:') }}
                </h2>
                <form wire:submit="create" class="grid grid-cols-1 gap-y-6">
                    {{ $this->form }}
                    <div>
                        <button type="submit"
                            class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-yellow-400 hover:bg-yellow-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-amber-500">
                            Place Request
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
