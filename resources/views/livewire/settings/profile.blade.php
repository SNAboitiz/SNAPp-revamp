<?php

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Livewire\Volt\Component;

new class extends Component {
    public string $name = '';
    public string $email = '';

    public function mount(): void
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }
};
?>

<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Profile')" :subheading="__('View your name and email address')">
        <form wire:submit.prevent class="my-6 w-full space-y-6">
            <!-- Name (disabled) -->
            <flux:input wire:model="name" :label="__('Name')" type="text" disabled
                class="bg-gray-100 cursor-not-allowed" />

            <!-- Email (disabled) -->
            <flux:input wire:model="email" :label="__('Email')" type="email" disabled
                class="bg-gray-100 cursor-not-allowed" />

            <div class="text-sm text-gray-500">
                {{ __('Name and email cannot be edited.') }}
            </div>
        </form>

        {{-- Remove the Delete Account form entirely --}}
        {{-- <livewire:settings.delete-user-form /> --}}
    </x-settings.layout>
</section>
