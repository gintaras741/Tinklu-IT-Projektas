@extends('layouts.app')

@section('title', 'Order Details')

@section('content')
    @php($status = session('status'))
    @php($error = session('error'))

    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-semibold text-gray-900">Order #{{ $order->order_number }}</h1>
            <a href="{{ route('admin.orders.index') }}"
                class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
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

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Order Details -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Customer Information -->
                <div class="rounded-xl bg-white shadow-sm border border-gray-200">
                    <div class="px-6 py-5">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Name</span>
                                <span class="font-medium">{{ $order->user->name }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Email</span>
                                <span class="font-medium">{{ $order->user->email }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items - Parts -->
                @if ($order->partItems->isNotEmpty())
                    <div class="rounded-xl bg-white shadow-sm border border-gray-200">
                        <div class="px-6 py-5">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Parts</h3>
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
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Bicycles</h3>
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
                <div class="rounded-xl bg-white shadow-sm border border-gray-200">
                    <div class="px-6 py-5">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipping Address</h3>
                        <p class="text-sm text-gray-600 whitespace-pre-line">{{ $order->shipping_address }}</p>
                    </div>
                </div>

                <!-- Notes -->
                @if ($order->notes)
                    <div class="rounded-xl bg-white shadow-sm border border-gray-200">
                        <div class="px-6 py-5">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Notes</h3>
                            <p class="text-sm text-gray-600 whitespace-pre-line">{{ $order->notes }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Order Summary & Status Update -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Order Summary -->
                <div class="rounded-xl bg-white shadow-sm border border-gray-200 sticky top-4">
                    <div class="px-6 py-5">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>

                        <div class="space-y-3 mb-4">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Status</span>
                                @if ($order->status->value === 'pending')
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-yellow-100 text-yellow-800">
                                        Pending
                                    </span>
                                @elseif($order->status->value === 'processing')
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-800">
                                        Processing
                                    </span>
                                @elseif($order->status->value === 'shipped')
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-purple-100 text-purple-800">
                                        Shipped
                                    </span>
                                @elseif($order->status->value === 'delivered')
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-green-100 text-green-800">
                                        Delivered
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-red-100 text-red-800">
                                        Cancelled
                                    </span>
                                @endif
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Order Date</span>
                                <span class="font-medium">{{ $order->ordered_at->format('M d, Y H:i') }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-600">Order Number</span>
                                <span class="font-medium">{{ $order->order_number }}</span>
                            </div>
                        </div>

                        <div class="border-t pt-4">
                            <div class="flex justify-between text-lg font-bold text-gray-900">
                                <p>Total</p>
                                <p>€{{ number_format($order->total_amount, 2) }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Update Status -->
                @if ($order->status->value !== 'cancelled' && $order->status->value !== 'delivered')
                    <div class="rounded-xl bg-white shadow-sm border border-gray-200">
                        <div class="px-6 py-5">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Status</h3>
                            <form method="POST" action="{{ route('admin.orders.updateStatus', $order) }}">
                                @csrf
                                @method('PUT')
                                <div class="space-y-4">
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700">New
                                            Status</label>
                                        <select id="status" name="status" required
                                            class="mt-1 block w-full h-11 rounded-lg border border-gray-300 bg-white px-4 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="pending"
                                                {{ $order->status->value === 'pending' ? 'selected' : '' }}>Pending
                                            </option>
                                            <option value="processing"
                                                {{ $order->status->value === 'processing' ? 'selected' : '' }}>
                                                Processing</option>
                                            <option value="shipped"
                                                {{ $order->status->value === 'shipped' ? 'selected' : '' }}>Shipped
                                            </option>
                                            <option value="delivered"
                                                {{ $order->status->value === 'delivered' ? 'selected' : '' }}>Delivered
                                            </option>
                                            <option value="cancelled"
                                                {{ $order->status->value === 'cancelled' ? 'selected' : '' }}>Cancelled
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
                                        Update Status
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
