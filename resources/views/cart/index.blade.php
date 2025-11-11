@extends('layouts.app')

@section('title', 'Shopping Cart')

@section('content')
    @php($status = session('status'))
    @php($error = session('error'))

    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-semibold text-gray-900">Shopping Cart</h1>
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

        @if ($partItems->isEmpty() && $bicycleItems->isEmpty())
            <div class="rounded-xl border border-gray-200 bg-white p-12 text-center shadow-sm">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Your cart is empty</h3>
                <p class="text-gray-500 mb-6">Start adding parts or bicycles to your cart!</p>
                <div class="flex gap-3 justify-center">
                    <a href="{{ route('parts.index') }}"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500">
                        Browse Parts
                    </a>
                    <a href="{{ route('bicycles.index') }}"
                        class="inline-flex items-center rounded-md bg-white border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-50">
                        My Bicycles
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Cart Items -->
                <div class="lg:col-span-2 space-y-4">
                    @if ($partItems->isNotEmpty())
                        <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Parts</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    @foreach ($partItems as $item)
                                        <div class="flex items-center justify-between border-b pb-4 last:border-b-0">
                                            <div class="flex items-center space-x-4">
                                                @if ($item->part->image)
                                                    <img src="{{ asset('storage/' . $item->part->image) }}"
                                                        alt="{{ $item->part->name }}"
                                                        class="h-16 w-16 rounded object-cover">
                                                @else
                                                    <div
                                                        class="h-16 w-16 rounded bg-gray-200 flex items-center justify-center">
                                                        <span class="text-gray-400 text-xs">No image</span>
                                                    </div>
                                                @endif
                                                <div>
                                                    <p class="font-medium text-gray-900">{{ $item->part->name }}</p>
                                                    <p class="text-sm text-gray-500">{{ $item->part->type->value }}</p>
                                                    <p class="text-sm text-gray-600">Quantity: {{ $item->amount }}</p>
                                                    <p class="text-sm font-medium text-gray-900">
                                                        €{{ number_format($item->part->price, 2) }} each</p>
                                                </div>
                                            </div>
                                            <div class="flex items-center space-x-4">
                                                <form method="POST"
                                                    action="{{ route('cart.removePart', [$cart->id, $item->part->id]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                <p class="font-medium text-gray-900 w-24 text-right">
                                                    €{{ number_format($item->part->price * $item->amount, 2) }}</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    @if ($bicycleItems->isNotEmpty())
                        <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Bicycles</h3>
                            </div>
                            <div class="p-6">
                                <div class="space-y-4">
                                    @foreach ($bicycleItems as $item)
                                        <div class="flex items-center justify-between border-b pb-4 last:border-b-0">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $item->bicycle->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $item->bicycle->components->count() }}
                                                    components</p>
                                                <p class="text-sm text-gray-600">Quantity: {{ $item->amount }}</p>
                                                <p class="text-sm font-medium text-gray-900">
                                                    €{{ number_format($item->bicycle->calculatePrice(), 2) }} each</p>
                                            </div>
                                            <div class="flex items-center space-x-4">
                                                <form method="POST"
                                                    action="{{ route('cart.removeBicycle', [$cart->id, $item->bicycle->id]) }}">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                                            stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                                <p class="font-medium text-gray-900 w-24 text-right">
                                                    €{{ number_format($item->bicycle->calculatePrice() * $item->amount, 2) }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm sticky top-4">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Order Summary</h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-2">
                                <div class="flex justify-between text-base font-medium text-gray-900">
                                    <p>Total</p>
                                    <p>€{{ number_format($total, 2) }}</p>
                                </div>
                            </div>
                            <div class="mt-6">
                                <a href="{{ route('orders.create') }}"
                                    class="w-full flex justify-center items-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700">
                                    Proceed to Checkout
                                </a>
                            </div>
                            <div class="mt-3">
                                <form method="POST" action="{{ route('cart.clear') }}"
                                    onsubmit="return confirm('Are you sure you want to clear your cart?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-full flex justify-center items-center rounded-md border border-gray-300 bg-white px-6 py-3 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                                        Clear Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
