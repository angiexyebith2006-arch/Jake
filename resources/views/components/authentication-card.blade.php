<div class="flex flex-col items-center justify-center">

    <!-- Logo -->
    <div class="mb-4">
        {{ $logo }}
    </div>

    <!-- Card -->
    <div class="w-full max-w-sm px-6 py-4 bg-white/10 backdrop-blur-lg border border-white/20 shadow-2xl rounded-2xl">
        {{ $slot }}
    </div>

</div>
