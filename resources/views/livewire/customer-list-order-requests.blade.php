<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Order Requests') }}
    </h2>

    <link href="{{ asset('css/snackbar-style.css') }}" rel="stylesheet">
    <script src="{{ asset('js/snackbar-listener.js') }}" defer></script>
</x-slot>

<div>
    {{ $this->table }}
    @if (session()->has('message'))
        <div id="snackbar" class="snackbar pt-4">
            {{ session('message') }}
        </div>
    @endif
</div>
