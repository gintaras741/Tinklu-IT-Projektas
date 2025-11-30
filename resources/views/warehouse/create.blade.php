@extends('layouts.app')

@section('title', 'Sukurti dalį')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-3xl font-semibold text-gray-900">Sukurti dalį</h1>
            <p class="mt-1 text-gray-600 text-sm">Pridėkite naują dviračio dalį į sandėlio inventorių.</p>
        </div>

        <form action="{{ route('warehouse.store') }}" method="POST" enctype="multipart/form-data"
            class="space-y-8 bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
            @csrf

            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Tipas</label>
                <select name="type"
                    class="mt-1 block w-full h-11 rounded-lg border border-gray-300 bg-white px-4 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach ($types as $type)
                        <option value="{{ $type }}" @selected(old('type') === $type)>
                            {{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                    @endforeach
                </select>
                @error('type')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Pavadinimas</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="mt-1 block w-full h-11 rounded-lg border border-gray-300 bg-white px-4 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required>
                @error('name')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Kiekis</label>
                <input type="number" min="0" name="amount" value="{{ old('amount', 0) }}"
                    class="mt-1 block w-full h-11 rounded-lg border border-gray-300 bg-white px-4 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required>
                @error('amount')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Kaina (€)</label>
                <input type="number" step="0.01" min="0" name="price" value="{{ old('price', 0) }}"
                    class="mt-1 block w-full h-11 rounded-lg border border-gray-300 bg-white px-4 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    required>
                @error('price')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Aprašymas (neprivaloma)</label>
                <textarea name="description" rows="3"
                    class="mt-1 block w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description') }}</textarea>
                @error('description')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Nuotrauka (neprivaloma)</label>
                <input type="file" name="image" accept="image/*"
                    class="mt-1 block w-full text-base text-gray-700 border border-gray-300 rounded-lg cursor-pointer bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 file:mr-4 file:rounded-md file:border-0 file:bg-indigo-50 file:px-4 file:py-2 file:text-indigo-700 hover:file:bg-indigo-100">
                @error('image')
                    <div class="text-red-600 text-xs mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="flex gap-3 pt-2">
                <a href="{{ route('warehouse.index') }}"
                    class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">Atšaukti</a>
                <button
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400">Sukurti</button>
            </div>
        </form>
    </div>
@endsection
