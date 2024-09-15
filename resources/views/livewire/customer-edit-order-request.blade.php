<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Edit Order Request') }}
    </h2>
</x-slot>

<div class="relative bg-white">
    <div class="relative max-w-7xl mx-auto lg:grid lg:grid-cols-5">
        <div class="bg-gray-50 py-16 px-4 sm:px-6 lg:col-span-2 lg:px-8 lg:py-24 xl:pr-12">
            <div class="max-w-lg mx-auto">
                {{ $this->displayOrderRequestInfoList }}
            </div>
        </div>
        <div class="bg-white py-16 px-4 sm:px-6 lg:col-span-3 lg:py-24 lg:px-8 xl:pl-12">
            <div class="max-w-lg mx-auto lg:max-w-none">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight pb-4">
                    {{ __('Edit Request Quantity:') }}
                </h2>
                <form wire:submit.prevent="save" class="grid grid-cols-1 gap-y-6">
                    {{ $this->form }}
                    <div>
                        <button type="submit"
                            class="inline-flex justify-center py-3 px-6 border border-transparent shadow-sm text-base font-medium rounded-md text-white bg-green-400 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-lime-500">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
