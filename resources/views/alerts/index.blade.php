@extends('layouts.app')

@section('title', 'Mano pranešimai')

@section('content')
    @php($status = session('status'))

    <div class="max-w-4xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-semibold text-gray-900">Mano pranešimai</h1>
            @if ($alerts->total() > 0)
                <form method="POST" action="{{ route('alerts.destroyAll') }}"
                    onsubmit="return confirm('Ar tikrai norite ištrinti visus pranešimus?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center rounded-md bg-red-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-red-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Ištrinti visus
                    </button>
                </form>
            @endif
        </div>

        @if ($status)
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ $status }}
            </div>
        @endif

        @if ($alerts->isEmpty())
            <div class="rounded-xl border border-gray-200 bg-white p-12 text-center shadow-sm">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Pranešimų nėra</h3>
                <p class="text-gray-500">Šiuo metu neturite jokių pranešimų.</p>
            </div>
        @else
            <div class="space-y-3">
                @foreach ($alerts as $alert)
                    <div class="rounded-lg border border-gray-200 bg-white shadow-sm hover:shadow transition-shadow">
                        <div class="p-4 flex items-start justify-between gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="h-5 w-5 text-indigo-600 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <span class="text-xs text-gray-500">
                                        {{ $alert->created_at->diffForHumans() }}
                                    </span>
                                </div>
                                <p class="text-gray-900">{{ $alert->text }}</p>
                            </div>
                            <form method="POST" action="{{ route('alerts.destroy', $alert) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="inline-flex items-center rounded-md bg-gray-100 p-2 text-gray-600 hover:bg-gray-200 hover:text-gray-800 transition-colors"
                                    title="Dismiss alert">
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($alerts->hasPages())
                <div class="mt-6">
                    {{ $alerts->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
