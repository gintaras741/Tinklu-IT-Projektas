@extends('layouts.app')

@section('title', 'Valdyti užsakymus')

@section('content')
    @php($status = session('status'))

    <div class="max-w-7xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-semibold text-gray-900">Valdyti užsakymus</h1>
        </div>

        @if ($status)
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ $status }}
            </div>
        @endif

        @if ($orders->isEmpty())
            <div class="rounded-xl border border-gray-200 bg-white p-12 text-center shadow-sm">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Užsakymų nerasta</h3>
                <p class="text-gray-500">Užsakymai bus rodomi čia, kai klientai pradės juos teikti.</p>
            </div>
        @else
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                <ul role="list" class="divide-y divide-gray-200">
                    @foreach ($orders as $order)
                        <li>
                            <a href="{{ route('admin.orders.show', $order) }}"
                                class="block hover:bg-gray-50 transition-colors">
                                <div class="px-6 py-4">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <p class="text-sm font-semibold text-indigo-600">{{ $order->order_number }}</p>
                                            <span
                                                class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-{{ $order->status->color() }}-100 text-{{ $order->status->color() }}-800">
                                                {{ $order->status->label() }}
                                            </span>
                                        </div>
                                        <div class="ml-2 flex flex-shrink-0">
                                            <p class="text-sm font-bold text-gray-900">
                                                €{{ number_format($order->total_amount, 2) }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-2 flex items-center justify-between text-sm text-gray-500">
                                        <div class="flex items-center gap-4">
                                            <p>Klientas: <span
                                                    class="font-medium text-gray-700">{{ $order->user->name }}</span></p>
                                            <p>{{ $order->partItems->count() + $order->bicycleItems->count() }} prekė(-s)
                                            </p>
                                        </div>
                                        <p>{{ $order->ordered_at->format('M d, Y H:i') }}</p>
                                    </div>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            @if ($orders->hasPages())
                <div class="mt-6">
                    {{ $orders->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
