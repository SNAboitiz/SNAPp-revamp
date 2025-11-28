<x-filament-panels::header :actions="$actions">
    <x-slot:heading>
        <span style="color: #1443e0">{{ isset($title) ? $title : 'Reports' }}</span>
    </x-slot:heading>
</x-filament-panels::header>
