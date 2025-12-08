@extends('layouts.app')

@section('title', $bicycle->name)

@section('content')
    @php($status = session('status'))

    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('bicycles.index') }}"
                class="inline-flex items-center text-sm text-gray-600 hover:text-indigo-600 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1">
                    <path fill-rule="evenodd"
                        d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z"
                        clip-rule="evenodd" />
                </svg>
                Atgal į Mano Dviračius
            </a>
            <div class="flex items-center justify-between">
                <h1 class="text-3xl font-semibold text-gray-900">{{ $bicycle->name }}</h1>
                <div class="flex gap-2">
                    <a href="{{ route('bicycles.edit', $bicycle) }}"
                        class="inline-flex items-center gap-2 rounded-md bg-white border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path
                                d="M2.695 14.763l-1.262 3.154a.5.5 0 00.65.65l3.155-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z" />
                        </svg>
                        Redaguoti
                    </a>
                    <form action="{{ route('bicycles.destroy', $bicycle) }}" method="POST" class="inline"
                        onsubmit="return confirm('Ar tikrai norite ištrinti šį dvirati?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center gap-2 rounded-md bg-red-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                <path fill-rule="evenodd"
                                    d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                    clip-rule="evenodd" />
                            </svg>
                            Ištrinti
                        </button>
                    </form>
                </div>
            </div>
        </div>

        @if ($status)
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ $status }}
            </div>
        @endif

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Santrauka</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-3 gap-6">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Iš viso komponentų</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $bicycle->components->count() }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Iš viso dalių</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $bicycle->components->sum('quantity') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Sukurta</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $bicycle->created_at->format('Y M j') }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Komponentai</h2>
            </div>
            <div class="overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">Dalis</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">Tipas</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">Kiekis</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">Nuotrauka</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($bicycle->components as $component)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 text-gray-900 font-medium">{{ $component->part->name }}</td>
                                <td class="px-6 py-4 text-gray-700">
                                    <span
                                        class="inline-flex items-center rounded-full bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700">
                                        {{ $component->part->type->label() }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-gray-700">
                                    <span
                                        class="inline-flex items-center rounded-full bg-gray-100 px-3 py-1 text-sm font-medium text-gray-700">
                                        {{ $component->quantity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($component->part->image)
                                        <img src="{{ asset('storage/' . $component->part->image) }}"
                                            alt="{{ $component->part->name }}"
                                            class="h-16 w-16 object-cover rounded-lg border border-gray-200">
                                    @else
                                        <div class="h-16 w-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-8 h-8 text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                            </svg>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
