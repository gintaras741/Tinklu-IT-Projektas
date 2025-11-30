@extends('layouts.app')

@section('title', 'DUK - Dažnai užduodami klausimai')

@section('content')
    @php($status = session('status'))

    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-semibold text-gray-900">Dažnai užduodami klausimai</h1>
            <a href="{{ route('questions.create') }}"
                class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Užduoti klausimą
            </a>
        </div>

        @if ($status)
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ $status }}
            </div>
        @endif

        <!-- Filters -->
        <div class="mb-6 bg-white rounded-lg border border-gray-200 p-4">
            <form method="GET" action="{{ route('questions.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Ieškoti klausimų..."
                        class="w-full h-11 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 text-base">
                </div>
                <select name="filter"
                    class="h-11 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 text-base">
                    <option value="">Visi klausimai</option>
                    <option value="answered" {{ request('filter') === 'answered' ? 'selected' : '' }}>Atsakyti</option>
                    <option value="unanswered" {{ request('filter') === 'unanswered' ? 'selected' : '' }}>Neatsakyti
                    </option>
                    <option value="my_questions" {{ request('filter') === 'my_questions' ? 'selected' : '' }}>Mano klausimai
                    </option>
                </select>
                <button type="submit"
                    class="inline-flex items-center rounded-md bg-gray-700 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-gray-600 transition-colors">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Ieškoti
                </button>
                @if (request('search') || request('filter'))
                    <a href="{{ route('questions.index') }}"
                        class="inline-flex items-center rounded-md bg-gray-200 px-4 py-2 text-sm font-semibold text-gray-700 shadow hover:bg-gray-300 transition-colors">
                        Išvalyti
                    </a>
                @endif
            </form>
        </div>

        @if ($questions->isEmpty())
            <div class="rounded-xl border border-gray-200 bg-white p-12 text-center shadow-sm">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Klausimų nerasta</h3>
                <p class="text-gray-500 mb-4">Būkite pirmas užduoti klausimą!</p>
                <a href="{{ route('questions.create') }}"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500">
                    Užduoti klausimą
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach ($questions as $question)
                    <div class="rounded-lg border border-gray-200 bg-white shadow-sm hover:shadow transition-shadow">
                        <a href="{{ route('questions.show', $question) }}" class="block p-5">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-3 mb-2">
                                        <svg class="h-5 w-5 text-indigo-600 flex-shrink-0" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-sm text-gray-500">
                                            Klausė <span class="font-medium">{{ $question->user->name }}</span>
                                        </span>
                                        <span class="text-xs text-gray-400">
                                            {{ $question->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                    <p class="text-gray-900 mb-2">{{ $question->text }}</p>
                                    <div class="flex items-center gap-4 text-sm">
                                        @if ($question->answers->count() > 0)
                                            <span class="inline-flex items-center text-green-600">
                                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M5 13l4 4L19 7" />
                                                </svg>
                                                {{ $question->answers->count() }}
                                                {{ $question->answers->count() === 1 ? 'atsakymas' : 'atsakymų' }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center text-yellow-600">
                                                <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24"
                                                    stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                                Laukiama atsakymo
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if ($questions->hasPages())
                <div class="mt-6">
                    {{ $questions->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
