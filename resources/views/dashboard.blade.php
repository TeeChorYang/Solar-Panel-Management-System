<x-app-layout>
    <x-slot name="header">
        @if (Auth::user()->type === 'manager')
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Manager Dashboard') }}
            </h2>
        @endif
        @if (Auth::user()->type === 'supplier')
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Supplier Dashboard') }}
            </h2>
        @endif
        @if (Auth::user()->type === 'customer')
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Customer Dashboard') }}
            </h2>
        @endif
    </x-slot>

    @if (Auth::user()->type === 'manager')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    @livewire('manager-orders')
                </div>
            </div>
        </div>
    @endif

    @if (Auth::user()->type === 'supplier')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <x-welcome />
                </div>
            </div>
        </div>
    @endif

    @if (Auth::user()->type === 'customer')
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                    <x-welcome />
                </div>
            </div>
        </div>
    @endif
</x-app-layout>
