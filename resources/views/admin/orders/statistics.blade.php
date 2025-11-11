<x-layouts.app>
    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
        <div class="md:flex md:items-center md:justify-between">
            <div class="min-w-0 flex-1">
                <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:truncate sm:text-3xl sm:tracking-tight">
                    Order Statistics
                </h2>
            </div>
            <div class="mt-4 flex md:ml-4 md:mt-0">
                <a href="{{ route('admin.orders.index') }}"
                    class="inline-flex items-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                    Back to Orders
                </a>
            </div>
        </div>

        <!-- Statistics Grid -->
        <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4">
            <!-- Total Orders -->
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Orders</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-gray-900">{{ $totalOrders }}</dd>
            </div>

            <!-- Total Revenue -->
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Total Revenue</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-green-600">
                    €{{ number_format($totalRevenue, 2) }}</dd>
            </div>

            <!-- Pending Orders -->
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Pending</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-yellow-600">{{ $pendingOrders }}</dd>
            </div>

            <!-- Processing Orders -->
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Processing</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-blue-600">{{ $processingOrders }}</dd>
            </div>

            <!-- Shipped Orders -->
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Shipped</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-purple-600">{{ $shippedOrders }}</dd>
            </div>

            <!-- Delivered Orders -->
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Delivered</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-green-600">{{ $deliveredOrders }}</dd>
            </div>

            <!-- Cancelled Orders -->
            <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
                <dt class="truncate text-sm font-medium text-gray-500">Cancelled</dt>
                <dd class="mt-1 text-3xl font-semibold tracking-tight text-red-600">{{ $cancelledOrders }}</dd>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="mt-8">
            <div class="rounded-lg bg-white shadow">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-4">Recent Orders</h3>

                    @if ($recentOrders->isEmpty())
                        <p class="text-sm text-gray-500">No recent orders.</p>
                    @else
                        <div class="overflow-hidden">
                            <ul role="list" class="divide-y divide-gray-200">
                                @foreach ($recentOrders as $order)
                                    <li class="py-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ route('admin.orders.show', $order) }}"
                                                    class="text-sm font-medium text-indigo-600 hover:text-indigo-900">
                                                    {{ $order->order_number }}
                                                </a>
                                                <span
                                                    class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-{{ $order->status->color() }}-100 text-{{ $order->status->color() }}-800">
                                                    {{ $order->status->label() }}
                                                </span>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ $order->user->name }} -
                                                €{{ number_format($order->total_amount, 2) }}
                                            </div>
                                        </div>
                                        <div class="mt-1 text-xs text-gray-500">
                                            {{ $order->ordered_at->format('M d, Y H:i') }}
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
