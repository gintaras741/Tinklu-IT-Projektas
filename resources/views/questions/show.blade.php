@extends('layouts.app')

@section('title', 'Question Details')

@section('content')
    @php($status = session('status'))

    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('questions.index') }}"
                class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to FAQ
            </a>
        </div>

        @if ($status)
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ $status }}
            </div>
        @endif

        <!-- Question -->
        <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-6 mb-6">
            <div class="flex items-start justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="rounded-full bg-indigo-100 p-2">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">
                            Asked by <span class="font-medium text-gray-900">{{ $question->user->name }}</span>
                        </p>
                        <p class="text-xs text-gray-400">{{ $question->created_at->format('M d, Y \a\t H:i') }}</p>
                    </div>
                </div>
                @if ($question->user_id === auth()->id() || auth()->user()->isAdmin())
                    <form method="POST" action="{{ route('questions.destroy', $question) }}"
                        onsubmit="return confirm('Are you sure you want to delete this question?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center rounded-md bg-red-100 px-3 py-1.5 text-sm font-medium text-red-700 hover:bg-red-200 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </button>
                    </form>
                @endif
            </div>

            <div class="prose prose-indigo max-w-none">
                <p class="text-gray-900 text-lg">{{ $question->text }}</p>
            </div>
        </div>

        <!-- Answers Section -->
        <div class="mb-6">
            <h2 class="text-xl font-bold text-gray-900 mb-4">
                Answers
                @if ($question->answers->count() > 0)
                    <span class="text-sm font-normal text-gray-500">({{ $question->answers->count() }})</span>
                @endif
            </h2>

            @if ($question->answers->isEmpty())
                <div class="rounded-lg border border-gray-200 bg-gray-50 p-8 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-3" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    <p class="text-gray-600">No answers yet. An admin or worker will respond soon.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach ($question->answers as $answer)
                        <div class="rounded-lg border border-gray-200 bg-white shadow-sm p-5">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center gap-3">
                                    <div class="rounded-full bg-green-100 p-2">
                                        <svg class="h-5 w-5 text-green-600" fill="none" viewBox="0 0 24 24"
                                            stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $answer->user->name }}
                                            @if ($answer->user->hasRole([\App\Enums\Role::Admin, \App\Enums\Role::Worker]))
                                                <span
                                                    class="ml-2 inline-flex items-center rounded-full bg-indigo-100 px-2 py-0.5 text-xs font-medium text-indigo-800">
                                                    {{ ucfirst($answer->user->role->value) }}
                                                </span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-400">{{ $answer->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @if ($answer->user_id === auth()->id() || auth()->user()->isAdmin())
                                    <form method="POST"
                                        action="{{ route('questions.answers.destroy', [$question, $answer]) }}"
                                        onsubmit="return confirm('Are you sure you want to delete this answer?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center rounded-md bg-red-50 p-2 text-red-600 hover:bg-red-100 transition-colors"
                                            title="Delete answer">
                                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                @endif
                            </div>
                            <p class="text-gray-900 whitespace-pre-wrap">{{ $answer->text }}</p>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Answer Form (for admin/worker only) -->
        @if (auth()->user()->hasRole([\App\Enums\Role::Admin, \App\Enums\Role::Worker]))
            <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Post an Answer</h3>
                <form method="POST" action="{{ route('questions.answers.store', $question) }}">
                    @csrf
                    <div class="mb-4">
                        <textarea name="text" rows="5" required maxlength="2000" placeholder="Write your answer here..."
                            class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 text-base">{{ old('text') }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">
                            Minimum 5 characters, maximum 2000 characters
                        </p>
                        @error('text')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <button type="submit"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Post Answer
                    </button>
                </form>
            </div>
        @endif
    </div>
@endsection
