<x-layouts.app.sidebar :title="$title ?? null">
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600&display=swap" rel="stylesheet">
    <flux:main>

        {{ $slot }}
    </flux:main>

</x-layouts.app.sidebar>
