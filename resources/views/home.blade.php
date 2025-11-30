@extends('layouts.app')

@section('title', 'Pagrindinis')

@section('content')
    <!-- Hero Section -->
    <div class="rounded-2xl bg-gradient-to-r from-indigo-600 to-purple-600 p-8 text-white shadow-lg mb-8">
        <div class="text-center">
            <h1 class="text-4xl font-bold tracking-tight sm:text-5xl">Sveiki atvykę į dviračių parduotuvę</h1>
            <p class="mt-4 text-lg text-indigo-100">Kurkite individualius dviračius, užsakykite dalis ir sekite savo
                užsakymus vienoje vietoje.</p>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Greitųjų veiksmų meniu</h2>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            <!-- Browse Parts -->
            <a href="{{ route('parts.index') }}" <a href="{{ route('parts.index') }}"
                class="group block rounded-lg bg-white p-6 shadow hover:shadow-md transition-shadow border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="rounded-lg bg-indigo-100 p-3">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600">Naršyti dalis</h3>
                <p class="mt-1 text-sm text-gray-500">Peržiūrėti galimas dalis</p>
            </a>

            <!-- Shopping Cart -->
            <a href="{{ route('cart.index') }}"
                class="group block rounded-lg bg-white p-6 shadow hover:shadow-md transition-shadow border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="rounded-lg bg-green-100 p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-green-600">Pirkinų krepšelis</h3>
                <p class="mt-1 text-sm text-gray-500">Peržiūrėti krepšelį</p>
            </a>

            <!-- My Orders -->
            <a href="{{ route('orders.index') }}"
                class="group block rounded-lg bg-white p-6 shadow hover:shadow-md transition-shadow border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="rounded-lg bg-blue-100 p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-blue-600">Mano užsakymai</h3>
                <p class="mt-1 text-sm text-gray-500">Sekti užsakymus</p>
            </a>

            <!-- My Bicycles -->
            <a href="{{ route('bicycles.index') }}"
                class="group block rounded-lg bg-white p-6 shadow hover:shadow-md transition-shadow border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="rounded-lg bg-purple-100 p-3">
                        <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-purple-600">Mano dviračiai</h3>
                <p class="mt-1 text-sm text-gray-500">Valdyti konstrukcijas</p>
            </a>

            <!-- Alerts -->
            <a href="{{ route('alerts.index') }}"
                class="group block rounded-lg bg-white p-6 shadow hover:shadow-md transition-shadow border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="rounded-lg bg-red-100 p-3 relative">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                        </svg>
                        @php($unreadCount = auth()->user()->alerts()->whereNull('read_at')->count())
                        @if ($unreadCount > 0)
                            <span
                                class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full">{{ $unreadCount }}</span>
                        @endif
                    </div>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-red-600">Pranešimai</h3>
                <p class="mt-1 text-sm text-gray-500">Peržiūrėti pranešimus</p>
            </a>

            <!-- FAQ -->
            <a href="{{ route('questions.index') }}"
                class="group block rounded-lg bg-white p-6 shadow hover:shadow-md transition-shadow border border-gray-200">
                <div class="flex items-center justify-between mb-3">
                    <div class="rounded-lg bg-yellow-100 p-3">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <h3 class="font-semibold text-gray-900 group-hover:text-yellow-600">DUK</h3>
                <p class="mt-1 text-sm text-gray-500">Užduoti klausimus</p>
            </a>
        </div>
    </div>

    @auth
        @if (auth()->user()->hasRole([\App\Enums\Role::Admin, \App\Enums\Role::Worker]))
            <!-- Admin Section -->
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-4">Administravimo įrankiai</h2>
                <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Warehouse Management -->
                    <a href="{{ route('warehouse.index') }}"
                        class="group block rounded-lg bg-white p-6 shadow hover:shadow-md transition-shadow border border-gray-200">
                        <div class="flex items-center justify-between mb-3">
                            <div class="rounded-lg bg-orange-100 p-3">
                                <svg class="h-6 w-6 text-orange-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-orange-600">Sandėlis</h3>
                        <p class="mt-1 text-sm text-gray-500">Valdyti inventorių</p>
                    </a>

                    <!-- Order Management -->
                    <a href="{{ route('admin.orders.index') }}"
                        class="group block rounded-lg bg-white p-6 shadow hover:shadow-md transition-shadow border border-gray-200">
                        <div class="flex items-center justify-between mb-3">
                            <div class="rounded-lg bg-red-100 p-3">
                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-red-600">Valdyti užsakymus</h3>
                        <p class="mt-1 text-sm text-gray-500">Peržiūrėti visus užsakymus</p>
                    </a>

                    <!-- Statistics -->
                    <a href="{{ route('admin.orders.statistics') }}"
                        class="group block rounded-lg bg-white p-6 shadow hover:shadow-md transition-shadow border border-gray-200">
                        <div class="flex items-center justify-between mb-3">
                            <div class="rounded-lg bg-teal-100 p-3">
                                <svg class="h-6 w-6 text-teal-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                </svg>
                            </div>
                        </div>
                        <h3 class="font-semibold text-gray-900 group-hover:text-teal-600">Statistika</h3>
                        <p class="mt-1 text-sm text-gray-500">Peržiūrėti metrikas</p>
                    </a>

                    @if (auth()->user()->isAdmin())
                        <!-- Manage Users -->
                        <a href="{{ route('admin.users.index') }}"
                            class="group block rounded-lg bg-white p-6 shadow hover:shadow-md transition-shadow border border-gray-200">
                            <div class="flex items-center justify-between mb-3">
                                <div class="rounded-lg bg-cyan-100 p-3">
                                    <svg class="h-6 w-6 text-cyan-600" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </div>
                            </div>
                            <h3 class="font-semibold text-gray-900 group-hover:text-cyan-600">Valdyti naudotojus</h3>
                            <p class="mt-1 text-sm text-gray-500">Peržiūrėti ir redaguoti naudotojus</p>
                        </a>
                    @endif
                </div>
            </div>
        @endif
    @endauth
@endsection
