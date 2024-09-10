<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('List Product') }}
    </h2>
</x-slot>

{{-- Can use HTML, Tailwindcss etc here --}}
    <div>
        {{ $this->table }}
    </div>
