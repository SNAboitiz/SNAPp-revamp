@props(['title' => null])

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        @include('partials.head') {{-- Adjust this if you include meta/assets differently --}}
    </head>

    <body class="min-h-screen bg-white text-zinc-900">

        <div class="flex h-screen w-full">
            <!-- LEFT: Form Section -->
            <div class="w-full md:w-1/2 flex items-center justify-center p-6 sm:p-12">
                <div class="w-full max-w-md space-y-6">
                    @if ($title)
                        <h1 class="text-2xl font-semibold text-center">
                            {{ $title }}
                        </h1>
                    @endif

                    {{ $slot }}
                </div>
            </div>

            <!-- RIGHT: Background Image -->
            <div class="hidden md:block w-1/2 h-full">
                <img src="{{ asset('images/background_login.png') }}" class="object-cover w-full h-full"
                    alt="Background Image" />
            </div>
        </div>

        @livewireScripts
        @stack('scripts')
        @fluxScripts
    </body>

</html>
