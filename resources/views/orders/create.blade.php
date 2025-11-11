@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
    @php($error = session('error'))

    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-semibold text-gray-900">Checkout</h1>
        </div>

        @if ($error)
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                {{ $error }}
            </div>
        @endif

        <form method="POST" action="{{ route('orders.store') }}">
            @csrf

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
                <!-- Order Form -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Shipping Address -->
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Shipping Address</h3>
                        </div>
                        <div class="p-6">
                            <div>
                                <label for="shipping_address"
                                    class="block text-sm font-medium text-gray-700">Address</label>
                                <textarea id="shipping_address" name="shipping_address" rows="3" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('shipping_address') }}</textarea>
                                @error('shipping_address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Additional Notes -->
                    <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900">Additional Notes</h3>
                        </div>
                        <div class="p-6">
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notes
                                    (Optional)</label>
                                <textarea id="notes" name="notes" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="rounded-lg bg-white shadow">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Order Items</h3>

                            @if ($partItems->isNotEmpty())
                                <div class="mb-4">
                                    <h4 class="font-medium text-gray-700 mb-2">Parts</h4>
                                    <div class="space-y-2">
                                        @foreach ($partItems as $item)
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600">{{ $item->part->name }} ×
                                                    {{ $item->amount }}</span>
                                                <span
                                                    class="font-medium">€{{ number_format($item->part->price * $item->amount, 2) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if ($bicycleItems->isNotEmpty())
                                <div>
                                    <h4 class="font-medium text-gray-700 mb-2">Bicycles</h4>
                                    <div class="space-y-2">
                                        @foreach ($bicycleItems as $item)
                                            <div class="flex justify-between text-sm">
                                                <span class="text-gray-600">{{ $item->bicycle->name }} ×
                                                    {{ $item->amount }}</span>
                                                <span
                                                    class="font-medium">€{{ number_format($item->bicycle->calculatePrice() * $item->amount, 2) }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="rounded-lg bg-white shadow sticky top-4">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Order Summary</h3>
                            <div class="space-y-2 mb-4">
                                <div class="flex justify-between text-base text-gray-600">
                                    <p>Subtotal</p>
                                    <p>€{{ number_format($total, 2) }}</p>
                                </div>
                                <div class="flex justify-between text-base text-gray-600">
                                    <p>Shipping</p>
                                    <p>€0.00</p>
                                </div>
                                <div class="border-t pt-2 flex justify-between text-lg font-bold text-gray-900">
                                    <p>Total</p>
                                    <p>€{{ number_format($total, 2) }}</p>
                                </div>
                            </div>
                            <button type="submit"
                                class="w-full flex justify-center items-center rounded-md border border-transparent bg-indigo-600 px-6 py-3 text-base font-medium text-white shadow-sm hover:bg-indigo-700">
                                Place Order
                            </button>
                            <div class="mt-3">
                                <a href="{{ route('cart.index') }}"
                                    class="w-full flex justify-center items-center rounded-md border border-gray-300 bg-white px-6 py-3 text-base font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                                    Back to Cart
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
