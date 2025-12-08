@extends('layouts.app')

@section('title', 'Naršyti dalis')

@section('content')
    @php($status = session('status'))
    @php($error = session('error'))

    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-semibold text-gray-900">Naršyti dalis</h1>
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

        @if ($parts->isEmpty())
            <div class="rounded-xl border border-gray-200 bg-white p-12 text-center shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="mx-auto h-16 w-16 text-gray-400 mb-4">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Dalių nėra</h3>
                <p class="text-gray-500">Užsukite vėliau dėl dviračių dalių.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($parts as $part)
                    <div
                        class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                        <div class="p-6">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex-1">
                                    <h3 class="text-xl font-semibold text-gray-900 mb-1">{{ $part->name }}</h3>
                                    <span
                                        class="inline-flex items-center rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700">
                                        {{ $part->type->label() }}
                                    </span>
                                </div>
                                <div class="text-right">
                                    <p class="text-2xl font-bold text-gray-900">€{{ number_format($part->price, 2) }}</p>
                                </div>
                            </div>

                            @if ($part->description)
                                <p class="text-sm text-gray-600 mb-4 line-clamp-2">{{ $part->description }}</p>
                            @endif

                            <div class="space-y-2 mb-4">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Sandėlyje:</span>
                                    <span class="font-medium {{ $part->isInStock() ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $part->quantity }} vnt.
                                    </span>
                                </div>
                            </div>

                            @if ($part->isInStock())
                                <form action="{{ route('cart.addPart', $part) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="quantity" value="1">
                                    <button type="submit"
                                        class="w-full inline-flex justify-center items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-indigo-500">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                            class="w-5 h-5">
                                            <path
                                                d="M1 1.75A.75.75 0 011.75 1h1.628a1.75 1.75 0 011.734 1.51L5.18 3a65.25 65.25 0 0113.36 1.412.75.75 0 01.58.875 48.645 48.645 0 01-1.618 6.2.75.75 0 01-.712.513H6a2.503 2.503 0 00-2.292 1.5H17.25a.75.75 0 010 1.5H2.76a.75.75 0 01-.748-.807 4.002 4.002 0 012.716-3.486L3.626 2.716a.25.25 0 00-.248-.216H1.75A.75.75 0 011 1.75zM6 17.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zM15.5 19a1.5 1.5 0 100-3 1.5 1.5 0 000 3z" />
                                        </svg>
                                        Pridėti į krepšelį
                                    </button>
                                </form>
                            @else
                                <button disabled
                                    class="w-full inline-flex justify-center items-center rounded-md bg-gray-300 px-4 py-2 text-sm font-medium text-gray-500 cursor-not-allowed">
                                    Nėra sandėlyje
                                </button>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($parts->hasPages())
                <div class="mt-6">
                    {{ $parts->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
