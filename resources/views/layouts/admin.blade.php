<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <div class="bg-gray-800 text-white w-64 min-h-screen flex-shrink-0">
            <div class="p-6">
                <h1 class="text-2xl font-bold">{{ config('app.name', 'Laravel') }}</h1>
                <p class="text-gray-400 text-sm">Admin Panel</p>
            </div>

            <nav class="mt-6">
                <div class="px-4 space-y-2">
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('dashboard') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        Dashboard
                    </a>
                    <div class="text-xs text-gray-400 uppercase mt-6 mb-2 tracking-wider">PO Management</div>
                    <a href="{{ route('purchase-orders.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('purchase-orders.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        Purchase Orders
                    </a>
                    <a href="{{ route('vendors.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('vendors.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        Vendors
                    </a>
                    <a href="{{ route('settings.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('settings.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        Settings
                    </a>
                    <div class="text-xs text-gray-400 uppercase mt-6 mb-2 tracking-wider">User Management</div>
                    <a href="{{ route('users.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('users.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        Users
                    </a>
                    <a href="{{ route('roles.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('roles.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        Roles
                    </a>
                    <a href="{{ route('permissions.index') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('permissions.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        Permissions
                    </a>
                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('profile.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                        Profile
                    </a>
                    @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                        @can('teams.create')
                        <a href="{{ route('teams.create') }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('teams.create') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            Create Team
                        </a>
                        @endcan
                        <a href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" class="block px-4 py-2 rounded-lg {{ request()->routeIs('teams.*') ? 'bg-gray-700 text-white' : 'text-gray-300 hover:bg-gray-700 hover:text-white' }}">
                            Team Settings
                        </a>
                    @endif
                </div>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navigation -->
            <header class="bg-white shadow-sm border-b border-gray-200">
                <div class="flex items-center justify-between px-6 py-4">
                    <div>
                        @if (isset($header))
                            <h2 class="text-xl font-semibold text-gray-800">{{ $header }}</h2>
                        @endif
                    </div>
                    <div class="flex items-center">
                        @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                            <div class="ml-3 relative">
                                <x-dropdown align="right" width="60">
                                    <x-slot name="trigger">
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                                {{ Auth::user()->currentTeam->name }}
                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    </x-slot>
                                    <x-slot name="content">
                                        <!-- Team Management -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Manage Team') }}
                                        </div>
                                        <!-- Team Settings -->
                                        <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                            {{ __('Team Settings') }}
                                        </x-dropdown-link>
                                        @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                            <x-dropdown-link href="{{ route('teams.create') }}">
                                                {{ __('Create New Team') }}
                                            </x-dropdown-link>
                                        @endcan
                                        <div class="border-t border-gray-100"></div>
                                        <!-- Team Switcher -->
                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Switch Teams') }}
                                        </div>
                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    </x-slot>
                                </x-dropdown>
                            </div>
                        @endif

                        <!-- Settings Dropdown -->
                        <div class="ml-3 relative">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                        <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                            <img class="h-8 w-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                        </button>
                                    @else
                                        <span class="inline-flex rounded-md">
                                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition">
                                                {{ Auth::user()->name }}
                                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                                </svg>
                                            </button>
                                        </span>
                                    @endif
                                </x-slot>

                                <x-slot name="content">
                                    <!-- Account Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Account') }}
                                    </div>

                                    <x-dropdown-link href="{{ route('profile.show') }}">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>

                                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                        <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                            {{ __('API Tokens') }}
                                        </x-dropdown-link>
                                    @endif

                                    <div class="border-t border-gray-100"></div>

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}" x-data>
                                        @csrf
                                        <x-dropdown-link href="{{ route('logout') }}"
                                                 @click.prevent="$root.submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-6">
                 @yield('content')
            </main>
        </div>
    </div>

    @stack('modals')
    @livewireScripts
</body>
</html>