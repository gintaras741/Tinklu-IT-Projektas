@extends('layouts.app')

@section('title', 'Užsakymo detalės')

@section('content')
    @php
        $status = session('status');
        $error = session('error');
    @endphp

    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-semibold text-gray-900">Užsakymas #{{ $order->order_number }}</h1>
            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Grįžti į užsakymus
            </a>
        </div>

        @if ($status)
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ $status }}
            </div>
        @endif

        @if ($error)
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                {{ $error }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Information -->
                <div class="rounded-xl bg-white shadow-sm border border-gray-200">
                    <div class="px-6 py-5">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Kliento informacija</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Vardas</span>
                                <span class="font-medium">{{ $order->user->name }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">El. paštas</span>
                                <span class="font-medium">{{ $order->user->email }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items - Parts -->
                @if ($order->partItems->isNotEmpty())
                    <div class="rounded-xl bg-white shadow-sm border border-gray-200">
                        <div class="px-6 py-5">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Dalys</h3>
                            <div class="space-y-4">
                                @foreach ($order->partItems as $item)
                                    <div class="flex items-center justify-between border-b pb-4 last:border-b-0">
                                        <div class="flex items-center space-x-4">
                                            @if ($item->part->image)
                                                <img src="{{ asset('storage/' . $item->part->image) }}"
                                                    alt="{{ $item->part->name }}" class="h-16 w-16 rounded object-cover">
                                            @else
                                                <div class="h-16 w-16 rounded bg-gray-200 flex items-center justify-center">
                                                    <span class="text-gray-400 text-xs">Nėra nuotraukos</span>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $item->part->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $item->part->type->label() }}</p>
                                                <p class="text-sm text-gray-600">Kiekis: {{ $item->amount }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-500">
                                                €{{ number_format($item->price_at_purchase, 2) }} vnt.</p>
                                            <p class="font-medium text-gray-900">
                                                €{{ number_format($item->subtotal, 2) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Order Items - Bicycles -->
                @if ($order->bicycleItems->isNotEmpty())
                    <div class="rounded-xl bg-white shadow-sm border border-gray-200">
                        <div class="px-6 py-5">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Dviračiai</h3>
                            <div class="space-y-4">
                                @foreach ($order->bicycleItems as $item)
                                    <div class="border-b pb-4 last:border-b-0">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $item->bicycle->name }}</p>
                                                <p class="text-sm text-gray-600">Kiekis: {{ $item->amount }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm text-gray-500">
                                                    €{{ number_format($item->price_at_purchase, 2) }} vnt.</p>
                                                <p class="font-medium text-gray-900">
                                                    €{{ number_format($item->subtotal, 2) }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-2 ml-4 space-y-1">
                                            <p class="text-xs font-medium text-gray-500">Komponentai:</p>
                                            @foreach ($item->bicycle->components as $component)
                                                <p class="text-xs text-gray-600">
                                                    {{ $component->part->name }} × {{ $component->quantity }}
                                                </p>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Pristatymo adresas -->
                <div class="rounded-xl bg-white shadow-sm border border-gray-200">
                    <div class="px-6 py-5">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Pristatymo adresas</h3>
                        <p class="text-sm text-gray-600 whitespace-pre-line">{{ $order->shipping_address }}</p>
                    </div>
                </div>

                <!-- Notes -->
                @if ($order->notes)
                    <div class="rounded-xl bg-white shadow-sm border border-gray-200">
                        <div class="px-6 py-5">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Pastabos</h3>

                            @if ($order->hasOutOfStockItems())
                                @php
                                    $notes = $order->notes;
                                    $stockInfoStart = strpos($notes, '[TRŪKSTA SANDĖLYJE]');
                                    $regularNotes =
                                        $stockInfoStart !== false ? trim(substr($notes, 0, $stockInfoStart)) : $notes;
                                    $stockInfo = $order->getOutOfStockInfo();
                                @endphp

                                @if ($regularNotes)
                                    <p class="text-sm text-gray-600 whitespace-pre-line mb-4">{{ $regularNotes }}</p>
                                @endif

                                @if ($stockInfo)
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                        <div class="flex items-start">
                                            <svg class="w-5 h-5 text-red-600 mr-2 mt-0.5" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                            </svg>
                                            <div class="flex-1">
                                                <h4 class="text-sm font-semibold text-red-800 mb-2">TRŪKSTAMOS DETALĖS</h4>
                                                <div class="text-sm text-red-700 whitespace-pre-line font-mono">
                                                    {{ trim(str_replace('[TRŪKSTA SANDĖLYJE]', '', $stockInfo)) }}</div>
                                                <a href="{{ route('warehouse.index') }}"
                                                    class="inline-flex items-center mt-3 px-3 py-1.5 border border-transparent text-xs font-medium rounded text-red-700 bg-red-100 hover:bg-red-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                    Eiti į sandėlį
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @else
                                <p class="text-sm text-gray-600 whitespace-pre-line">{{ $order->notes }}</p>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            <!-- Order Summary & Status Update -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Order Summary -->
                <div class="rounded-xl bg-white shadow-sm border border-gray-200 sticky top-4">
                    <div class="px-6 py-5">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Užsakymo santrauka</h3>

                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Būsena</span>
                                @if ($order->status->value === 'pending')
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Laukiama
                                    </span>
                                @elseif($order->status->value === 'processing')
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-800">
                                        Vykdoma
                                    </span>
                                @elseif($order->status->value === 'shipped')
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-purple-100 text-purple-800">
                                        Išsiųsta
                                    </span>
                                @elseif($order->status->value === 'delivered')
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800">
                                        Pristatyta
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-red-100 text-red-800">
                                        Atšaukta
                                    </span>
                                @endif
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Užsakymo data</span>
                                <span class="font-medium">{{ $order->ordered_at->format('Y M j H:i') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Užsakymo numeris</span>
                                <span class="font-medium">{{ $order->order_number }}</span>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <div class="flex justify-between text-lg font-bold text-gray-900">
                                <p>Iš viso</p>
                                <p>€{{ number_format($order->total_amount, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Status -->
                @if ($order->status->value !== 'cancelled')
                    <div class="rounded-xl bg-white shadow-sm border border-gray-200">
                        <div class="px-6 py-5">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Atnaujinti būseną</h3>

                            <!-- Quick Advance Button -->
                            <form method="POST" action="{{ route('admin.orders.advanceStatus', $order) }}"
                                class="mb-4">
                                @csrf
                                <button type="submit"
                                    class="w-full flex justify-center items-center rounded-md border border-transparent bg-green-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-green-500 transition-colors">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                    Pereiti į kitą būseną
                                </button>
                            </form>

                            @if ($order->status->value !== 'delivered')
                                <div class="relative mb-4">
                                    <div class="absolute inset-0 flex items-center">
                                        <div class="w-full border-t border-gray-300"></div>
                                    </div>
                                    <div class="relative flex justify-center text-sm">
                                        <span class="px-2 bg-white text-gray-500">arba</span>
                                    </div>
                                </div>

                                <!-- Manual Status Selection -->
                                <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                                    @csrf
                                    @method('PUT')
                                    <div class="space-y-4">
                                        <div>
                                            <label for="status" class="block text-sm font-medium text-gray-700">Nauja
                                                būsena</label>
                                            <select id="status" name="status" required
                                                class="mt-1 block w-full h-11 rounded-lg border border-gray-300 bg-white px-4 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="pending"
                                                    {{ $order->status->value === 'pending' ? 'selected' : '' }}>Laukiama
                                                </option>
                                                <option value="processing"
                                                    {{ $order->status->value === 'processing' ? 'selected' : '' }}>
                                                    Vykdoma</option>
                                                <option value="shipped"
                                                    {{ $order->status->value === 'shipped' ? 'selected' : '' }}>Išsiųsta
                                                </option>
                                                <option value="delivered"
                                                    {{ $order->status->value === 'delivered' ? 'selected' : '' }}>
                                                    Pristatyta
                                                </option>
                                                <option value="cancelled"
                                                    {{ $order->status->value === 'cancelled' ? 'selected' : '' }}>Atšaukta
                                                </option>
                                            </select>
                                        </div>
                                        <button type="submit"
                                            class="w-full flex justify-center items-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 transition-colors">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                                            </svg>
                                            Atnaujinti būseną
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
