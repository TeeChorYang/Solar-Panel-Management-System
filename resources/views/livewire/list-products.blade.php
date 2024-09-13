<div class="fi-main mx-auto w-full px-4 md:px-6 lg:px-8 max-w-7xl">
    <div class="grid auto-cols-fr gap-y-8 py-8">
        <div class="items-center" style="width: 100%;">
            <h1 class="mb-4" style="font-size: 30px; font-weight:bold;">Products</h1>
            {{-- <div class="button_leave">
                <button class="create_leave_application" onclick="location.href='leave-application-form'">&nbsp; Create Leave Application &nbsp;</button>
            </div> --}}
            <div>
                {{ $this->table }}
            </div>
        </div>
    </div>

    <style>
        .fi-compact {
            display: none;
        }
    </style>
</div>