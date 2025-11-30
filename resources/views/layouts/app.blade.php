<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dviračių parduotuvė')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-50 min-h-screen">
    <header class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <!-- Left: Brand and nav -->
            <div class="flex items-center gap-8">
                <a href="{{ route('home') }}" class="text-lg font-semibold text-gray-900">Dviračių parduotuvė</a>
                <nav class="hidden md:flex items-center gap-6 text-sm">
                    <a href="{{ route('parts.index') }}" class="text-gray-700 hover:text-indigo-600">Dalys</a>
                    <a href="{{ route('bicycles.index') }}" class="text-gray-700 hover:text-indigo-600">Mano
                        dviračiai</a>
                    <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-indigo-600">Krepšelis</a>
                    <a href="{{ route('orders.index') }}" class="text-gray-700 hover:text-indigo-600">Mano užsakymai</a>
                    <a href="{{ route('alerts.index') }}" class="text-gray-700 hover:text-indigo-600 relative">
                        Pranešimai
                        @php($unreadCount = auth()->user()->alerts()->whereNull('read_at')->count())
                        @if ($unreadCount > 0)
                            <span
                                class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-600 rounded-full">{{ $unreadCount }}</span>
                        @endif
                    </a>
                    <a href="{{ route('questions.index') }}" class="text-gray-700 hover:text-indigo-600">DUK</a>
                    @auth
                        @if (auth()->user()->isAdmin() || auth()->user()->isWorker())
                            <a href="{{ route('warehouse.index') }}"
                                class="text-gray-700 hover:text-indigo-600">Sandėlis</a>
                            <a href="{{ route('admin.orders.index') }}" class="text-gray-700 hover:text-indigo-600">Valdyti
                                užsakymus</a>
                        @endif
                        @if (auth()->user()->isAdmin())
                            <a href="{{ route('admin.users.index') }}" class="text-gray-700 hover:text-indigo-600">Valdyti
                                naudotojus</a>
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
                            Atsijungti
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="text-sm text-gray-700 hover:text-indigo-600">Prisijungti</a>
                    <a href="{{ route('register') }}"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm font-medium text-white shadow hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400">Registruotis</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto p-6">
        @yield('content')
    </main>
</body>

</html>
