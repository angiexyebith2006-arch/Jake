<div class="flex flex-col items-center justify-center">

    <!-- Logo -->
    <div class="mb-4">
        {{ $logo }}
    </div>

    <!-- Card -->
    <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
        {{ $slot }}
    </div>
</div>
