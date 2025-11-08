<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Bicycle shop')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen">
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Left: Brand and nav -->
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-lg font-semibold text-gray-900">Bicycle shop</a>
                <nav class="hidden md:flex items-center gap-6 text-sm">
                    <a href="#" class="text-gray-700 hover:text-indigo-600">Order Parts</a>
                    <a href="#" class="text-gray-700 hover:text-indigo-600">My Bicycles</a>
                    @auth
                        @if (auth()->user()->isAdmin() || auth()->user()->isWorker())
                            <a href="{{ route('warehouse.index') }}"
                                class="text-gray-700 hover:text-indigo-600">Warehouse</a>
                        @endif
                    @endauth
                </nav>
            </div>

            <!-- Right: Auth info -->
            <div class="flex items-center gap-4">
                @auth
                    <div class="text-right">
                        <div class="text-sm font-medium text-gray-900">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-gray-500">
                            {{ ucfirst(auth()->user()->role->value ?? auth()->user()->role) }}</div>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white shadow hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                            Log out
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-indigo-600">Log in</a>
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white shadow hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400">Register</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-6">
        @yield('content')
    </main>
</body>

</html>
