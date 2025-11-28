<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        @include('partials.head')

        <!-- MODIFICATION: Added styles for x-cloak to prevent the "flash" of un-styled content. -->
        <style>
            [x-cloak] {
                display: none !important;
            }
        </style>
    </head>

    <body x-data="sidebar()" x-init="init()" class="min-h-screen flex bg-white dark:bg-zinc-800">

        <!-- Sidebar -->
        <!-- MODIFICATION: Added x-cloak to the aside element. It will now only become visible after AlpineJS has initialized its state. -->
        <aside x-cloak :class="collapsed ? 'w-20' : 'w-64'"
            class="transition-all duration-300 border-r border-zinc-200 bg-[#1443e0] dark:border-zinc-700 dark:bg-[#1443e0] flex flex-col fixed h-full">

            <!-- Branding & Toggles -->
            <div class="flex items-center justify-between p-4 flex-shrink-0">
                <a href="{{ route('dashboard') }}" wire:navigate>
                    <!-- MODIFICATION: Removed the conflicting x-data="{ collapsed: false }" from this div. This was the primary cause of the flash. -->
                    <div class="flex items-center h-10 w-full">
                        <!-- Full Logo -->
                        <template x-if="!collapsed">
                            <img src="{{ asset('images/snapp-logo-white.png') }}" alt="SnaPp Logo"
                                class="w-auto object-contain transition-all duration-300 ease-in-out" x-cloak />
                        </template>

                        <!-- Abbreviation (SN) -->
                        <template x-if="collapsed">
                            <flux:icon name="bars-3" class="text-white" />
                        </template>
                    </div>
                </a>

                <!-- Collapse/Expand Button -->
                <button @click="collapsed = !collapsed" class="text-white focus:outline-none">
                    <template x-if="!collapsed">
                        <flux:icon name="chevron-left" class="w-6 h-6" />
                    </template>
                    <template x-if="collapsed">
                        <flux:icon name="chevron-right" class="w-6 h-6" />
                    </template>
                </button>
            </div>

            <!-- Scrollable Navigation Container -->
            <div class="flex-1 overflow-y-auto">
                <!-- The even vertical spacing is preserved as requested. -->
                <nav class="h-full flex flex-col justify-evenly px-2">

                    <!-- Main Navigation Links -->
                    <!-- <a
                    href="{{ route('dashboard') }}"
                    class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                    :class="{ 'justify-center': collapsed, 'space-x-2': !collapsed }">
                    <flux:icon name="gauge" class="w-6 h-6 text-white" />
                    <span x-show="!collapsed" class="text-white">{{ __('Dashboard') }}</span>
                </a> -->
                    @can('can view bills')
                        <a href="{{ route('bills.show') }}"
                            class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                            :class="{ 'justify-center': collapsed, 'space-x-2': !collapsed }">
                            <flux:icon name="scroll" class="w-6 h-6 text-white" />
                            <span x-show="!collapsed" class="text-white">{{ __('Bills and Payment History') }}</span>
                        </a>
                    @endcan
                    @can('can view contracts')
                        <a href="{{ route('my-contracts') }}"
                            class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                            :class="{ 'justify-center': collapsed, 'space-x-2': !collapsed }">
                            <flux:icon name="receipt" class="w-6 h-6 text-white" />
                            <span x-show="!collapsed" class="text-white">{{ __('Contracts') }}</span>
                        </a>
                    @endcan
                    @can('can view econ')
                        <a href="{{ route('energy-consumption') }}"
                            class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                            :class="{ 'justify-center': collapsed, 'space-x-2': !collapsed }">
                            <flux:icon name="lightbulb" class="w-6 h-6 text-white" />
                            <span x-show="!collapsed" class="text-white">{{ __('Energy Consumption Report') }}</span>
                        </a>
                    @endcan
                    @can('can view advisories')
                        <a href="{{ route('advisories.index') }}"
                            class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                            :class="{ 'justify-center': collapsed, 'space-x-2': !collapsed }">
                            <flux:icon name="megaphone" class="w-6 h-6 text-white" />
                            <span x-show="!collapsed" class="text-white">{{ __('Advisories') }}</span>
                        </a>
                    @endcan
                    @can('can view profile')
                        <a href="{{ route('profiles.index') }}"
                            class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                            :class="{ 'justify-center': collapsed, 'space-x-2': !collapsed }">
                            <flux:icon name="id-card" class="w-6 h-6 text-white" />
                            <span x-show="!collapsed" class="text-white">{{ __('Profile') }}</span>
                        </a>
                    @endcan


                    @role('customer')
                        <a href="{{ route('help') }}"
                            class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                            :class="{ 'justify-center': collapsed, 'space-x-2': !collapsed }">
                            <flux:icon name="message-circle" class="w-6 h-6 text-white" />
                            <span x-show="!collapsed" class="text-white">{{ __('Helpdesk') }}</span>
                        </a>
                    @endrole

                    <!-- Admin Section Links (Now part of the same container) -->
                    @role('admin')
                <a
                    href="{{ route('customers.index') }}"
                    class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                    :class="{'justify-center': collapsed, 'space-x-2': !collapsed}">
                    <flux:icon name="book-user" class="w-6 h-6 text-white" />
                    <span x-show="!collapsed" class="text-white">{{ __('Customers') }}</span>
                </a>

                <a
                    href="{{ route('facilities.index') }}"
                    class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                    :class="{'justify-center': collapsed, 'space-x-2': !collapsed}">
                    <flux:icon name="building" class="w-6 h-6 text-white" />
                    <span x-show="!collapsed" class="text-white">{{ __('Facilities') }}</span>
                </a>
                
                        <a href="{{ route('bills.manage') }}"
                            class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                            :class="{ 'justify-center': collapsed, 'space-x-2': !collapsed }">
                            <flux:icon name="scroll" class="w-6 h-6 text-white" />
                            <span x-show="!collapsed" class="text-white">{{ __('Manage Bills') }}</span>
                        </a>
                        <flux:dropdown position="right-start" class="w-full">
                            <button
                                class="group flex items-center w-full p-2 rounded-md hover:bg-blue-500 transition-colors"
                                :class="{ 'justify-center': collapsed, 'space-x-2': !collapsed }">
                                <flux:icon name="squares-plus" class="w-6 h-6 text-white" />
                                <span x-show="!collapsed" class="text-white">{{ __('Accounts') }}</span>
                            </button>
                            <flux:menu class="w-48">
                                <flux:menu.item href="{{ route('all-user-list') }}" icon="user-group">
                                    {{ __('All User Accounts') }}
                                </flux:menu.item>
                                <flux:menu.item href="{{ route('users.index') }}" icon="user-group">
                                    {{ __('Customer Accounts') }}
                                </flux:menu.item>
                                <flux:menu.item href="{{ route('admin-list') }}" icon="user-group">
                                    {{ __('Admin Accounts') }}
                                </flux:menu.item>
                                <flux:menu.item href="{{ route('account-executive-list') }}" icon="briefcase">
                                    {{ __('Account Executive Accounts') }}
                                </flux:menu.item>
                            </flux:menu>
                        </flux:dropdown>
                        <a href="{{ route('admin.profiles.list') }}"
                            class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                            :class="{ 'justify-center': collapsed, 'space-x-2': !collapsed }">
                            <flux:icon name="book-user" class="w-6 h-6 text-white" />
                            <span x-show="!collapsed" class="text-white">{{ __('Profiles') }}</span>
                        </a>
                        <a href="{{ route('role.permission.list') }}"
                            class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                            :class="{ 'justify-center': collapsed, 'space-x-2': !collapsed }">
                            <flux:icon name="shield-user" class="w-6 h-6 text-white" />
                            <span x-show="!collapsed" class="text-white">{{ __('Roles & Permissions') }}</span>
                        </a>
                        <a href="{{ route('advisories.list') }}"
                            class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                            :class="{ 'justify-center': collapsed, 'space-x-2': !collapsed }">
                            <flux:icon name="megaphone" class="w-6 h-6 text-white" />
                            <span x-show="!collapsed" class="text-white">{{ __('Manage Advisories') }}</span>
                        </a>
                        <a href="{{ route('reports') }}"
                            class="group flex items-center p-2 rounded-md hover:bg-blue-500 transition-colors"
                            :class="{ 'justify-center': collapsed, 'space-x-2': !collapsed }">
                            <flux:icon name="chart-bar" class="w-6 h-6 text-white" />
                            <span x-show="!collapsed" class="text-white">{{ __('Manage Reports') }}</span>
                        </a>
                    @endrole
                </nav>
            </div>

            <!-- Fixed User Menu at Bottom -->
            <div class="px-2 py-4 border-t border-blue-600 flex-shrink-0">
                <flux:dropdown position="bottom" align="start">
                    <button
                        class="w-full rounded-md hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-blue-800 focus:ring-white transition-colors duration-150"
                        :class="collapsed ? 'p-1 flex justify-center' : 'p-1.5'">
                        <!-- Expanded View -->
                        <div x-show="!collapsed" x-cloak class="flex items-center w-full">
                            <span
                                class="inline-flex items-center justify-center h-8 w-8 rounded-md bg-blue-600 flex-shrink-0">
                                <span
                                    class="text-sm font-medium leading-none text-white">{{ auth()->user()->initials() }}</span>
                            </span>
                            <span
                                class="ml-2 text-sm font-medium text-white truncate">{{ auth()->user()->name }}</span>
                            <svg class="ml-auto h-5 w-5 text-gray-300 group-hover:text-white"
                                xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>

                        <!-- Collapsed View -->
                        <div x-show="collapsed" x-cloak>
                            <span class="inline-flex items-center justify-center h-8 w-8 rounded-md bg-blue-600">
                                <span
                                    class="text-sm font-medium leading-none text-white">{{ auth()->user()->initials() }}</span>
                            </span>
                        </div>
                    </button>

                    <flux:menu class="w-[220px]">
                        <flux:menu.radio.group>
                            <!-- profile info -->
                        </flux:menu.radio.group>
                        <flux:menu.separator />
                        <flux:menu.radio.group>
                            <flux:menu.item :href="route('settings.profile')" icon="cog" wire:navigate>
                                {{ __('Settings') }}
                            </flux:menu.item>
                        </flux:menu.radio.group>
                        <flux:menu.separator />
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                                class="w-full">
                                {{ __('Log Out') }}
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>
            </div>
        </aside>

        <!-- Global Manual Loader Overlay -->
        <div id="global-loader" class="hidden fixed inset-0 bg-white/75 z-[9999] flex items-center justify-center">
            <!-- MODIFICATION: Restored the loader button and SVG content. -->
            <button type="button" class="bg-indigo-600 text-white px-4 py-2 rounded inline-flex items-center"
                disabled>
                <svg class="animate-spin h-5 w-5 mr-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4" />
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                </svg>
                Loading Customer Data…
            </button>
        </div>

        <!-- Main content -->
        <div class="flex-1" :class="collapsed ? 'ml-20' : 'ml-64'">
            {{ $slot }}
        </div>

        <!-- Scripts -->
        <script>
            const loader = document.getElementById('global-loader');

            document.addEventListener('click', e => {
                const a = e.target.closest('a');
                if (a && a.href && !a.href.startsWith('#') && !a.target) {
                    loader.classList.remove('hidden');
                }
            });

            document.addEventListener('submit', () => {
                loader.classList.remove('hidden');
            });

            window.addEventListener('pageshow', () => {
                loader.classList.add('hidden');
            });
        </script>
        <script>
            // The stateful logic remains the same, but will now work correctly without the conflicting code.
            function sidebar() {
                return {
                    // Get initial state from localStorage, or default to collapsed on mobile.
                    collapsed: JSON.parse(localStorage.getItem('sidebarCollapsed')) ?? (window.innerWidth < 768),

                    init() {
                        // When the 'collapsed' property changes, save its new value to localStorage.
                        this.$watch('collapsed', (value) => {
                            localStorage.setItem('sidebarCollapsed', JSON.stringify(value))
                        });

                        // Automatically collapse on very small screens.
                        window.addEventListener('resize', () => {
                            if (window.innerWidth < 768) {
                                this.collapsed = true;
                            }
                        });
                    }
                }
            }
        </script>


        @livewireScripts
        @stack('scripts')
        @fluxScripts
    </body>

</html>
