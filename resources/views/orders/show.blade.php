@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
    @php($status = session('status'))
    @php($error = session('error'))

    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-semibold text-gray-900">Order #{{ $order->order_number }}</h1>
            <a href="{{ route('orders.index') }}"
                class="inline-flex items-center rounded-md bg-white border border-gray-300 px-4 py-2 text-sm font-semibold text-gray-900 shadow-sm hover:bg-gray-50">
                Back to Orders
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

        <div class="mt-8 grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Order Items - Parts -->
                @if ($order->partItems->isNotEmpty())
                    <div class="rounded-lg bg-white shadow">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Parts</h3>
                            <div class="space-y-4">
                                @foreach ($order->partItems as $item)
                                    <div class="flex items-center justify-between border-b pb-4 last:border-b-0">
                                        <div class="flex items-center space-x-4">
                                            @if ($item->part->image)
                                                <img src="{{ asset('storage/' . $item->part->image) }}"
                                                    alt="{{ $item->part->name }}" class="h-16 w-16 rounded object-cover">
                                            @else
                                                <div class="h-16 w-16 rounded bg-gray-200 flex items-center justify-center">
                                                    <span class="text-gray-400 text-xs">No image</span>
                                                </div>
                                            @endif
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $item->part->name }}</p>
                                                <p class="text-sm text-gray-500">{{ $item->part->type->value }}</p>
                                                <p class="text-sm text-gray-600">Quantity: {{ $item->amount }}</p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm text-gray-500">
                                                €{{ number_format($item->price_at_purchase, 2) }} each</p>
                                            <p class="font-medium text-gray-900">€{{ number_format($item->subtotal, 2) }}
                                            </p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Order Items - Bicycles -->
                @if ($order->bicycleItems->isNotEmpty())
                    <div class="rounded-lg bg-white shadow">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Bicycles</h3>
                            <div class="space-y-4">
                                @foreach ($order->bicycleItems as $item)
                                    <div class="border-b pb-4 last:border-b-0">
                                        <div class="flex items-center justify-between mb-2">
                                            <div>
                                                <p class="font-medium text-gray-900">{{ $item->bicycle->name }}</p>
                                                <p class="text-sm text-gray-600">Quantity: {{ $item->amount }}</p>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-sm text-gray-500">
                                                    €{{ number_format($item->price_at_purchase, 2) }} each</p>
                                                <p class="font-medium text-gray-900">
                                                    €{{ number_format($item->subtotal, 2) }}</p>
                                            </div>
                                        </div>
                                        <div class="mt-2 ml-4 space-y-1">
                                            <p class="text-xs font-medium text-gray-500">Components:</p>
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

                <!-- Shipping Address -->
                <div class="rounded-lg bg-white shadow">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Shipping Address</h3>
                        <p class="text-sm text-gray-600 whitespace-pre-line">{{ $order->shipping_address }}</p>
                    </div>
                </div>

                <!-- Notes -->
                @if ($order->notes)
                    <div class="rounded-lg bg-white shadow">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Notes</h3>
                            <p class="text-sm text-gray-600 whitespace-pre-line">{{ $order->notes }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-1">
                <div class="rounded-lg bg-white shadow sticky top-4">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Order Summary</h3>

                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Status</span>
                                <span
                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-{{ $order->status->color() }}-100 text-{{ $order->status->color() }}-800">
                                    {{ $order->status->label() }}
                                </span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Order Date</span>
                                <span class="font-medium">{{ $order->ordered_at->format('M d, Y') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Order Number</span>
                                <span class="font-medium">{{ $order->order_number }}</span>
                            </div>
                        </div>

                        <div class="border-t pt-4 mb-4">
                            <div class="flex justify-between text-lg font-bold text-gray-900">
                                <p>Total</p>
                                <p>€{{ number_format($order->total_amount, 2) }}</p>
                            </div>
                        </div>

                        @if ($order->canBeCancelled())
                            <form method="POST" action="{{ route('orders.cancel', $order) }}"
                                onsubmit="return confirm('Are you sure you want to cancel this order? This action cannot be undone.');">
                                @csrf
                                <button type="submit"
                                    class="w-full flex justify-center items-center rounded-md border border-red-300 bg-white px-6 py-3 text-base font-medium text-red-700 shadow-sm hover:bg-red-50">
                                    Cancel Order
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
