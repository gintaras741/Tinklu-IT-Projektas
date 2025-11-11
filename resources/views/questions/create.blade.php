@extends('layouts.app')

@section('title', 'Ask a Question')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('questions.index') }}"
                class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
                Back to FAQ
            </a>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm p-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-6">Ask a Question</h1>

            <form method="POST" action="{{ route('questions.store') }}">
                @csrf

                <div class="mb-6">
                    <label for="text" class="block text-sm font-medium text-gray-700 mb-2">
                        Your Question <span class="text-red-500">*</span>
                    </label>
                    <textarea id="text" name="text" rows="6" required maxlength="1000"
                        placeholder="Please describe your question in detail..."
                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-3 text-base @error('text') border-red-500 @enderror">{{ old('text') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">
                        Minimum 10 characters, maximum 1000 characters
                    </p>
                    @error('text')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                    <div class="flex items-start">
                        <svg class="h-5 w-5 text-blue-600 mt-0.5 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <div class="text-sm text-blue-800">
                            <p class="font-medium mb-1">Before asking:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>Check if your question has already been answered</li>
                                <li>Be as specific as possible</li>
                                <li>Our admin or worker team will respond as soon as possible</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit"
                        class="inline-flex items-center rounded-md bg-indigo-600 px-6 py-2.5 text-sm font-semibold text-white shadow hover:bg-indigo-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                        </svg>
                        Submit Question
                    </button>
                    <a href="{{ route('questions.index') }}"
                        class="inline-flex items-center rounded-md bg-gray-200 px-6 py-2.5 text-sm font-semibold text-gray-700 hover:bg-gray-300 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
