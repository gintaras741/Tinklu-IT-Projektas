@extends('layouts.app')

@section('title', 'Sandėlis')

@section('content')
    @php($status = session('status'))
    <div class="max-w-6xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-semibold text-gray-900">Sandėlis</h1>
            <a href="{{ route('warehouse.create') }}"
                class="inline-flex items-center gap-2 rounded-md bg-indigo-600 px-4 py-2 text-white shadow hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                    <path
                        d="M12 4.5a.75.75 0 01.75.75v6h6a.75.75 0 010 1.5h-6v6a.75.75 0 01-1.5 0v-6h-6a.75.75 0 010-1.5h6v-6A.75.75 0 0112 4.5z" />
                </svg>
                Nauja dalis
            </a>
        </div>

        @if ($status)
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-green-800">
                {{ $status }}</div>
        @endif

        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">ID</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Tipas</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Pavadinimas</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Kiekis</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Kaina</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Nuotrauka</th>
                        <th class="px-4 py-3 text-right font-medium text-gray-600">Veiksmai</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($parts as $part)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3 text-gray-700">{{ $part->id }}</td>
                            <td class="px-4 py-3 text-gray-700">
                                {{ ucfirst(str_replace('_', ' ', $part->type->value ?? $part->type)) }}</td>
                            <td class="px-4 py-3 text-gray-900 font-medium">{{ $part->name }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $part->amount }}</td>
                            <td class="px-4 py-3 text-gray-900 font-semibold">€{{ number_format($part->price, 2) }}</td>
                            <td class="px-4 py-3">
                                @if ($part->image)
                                    <img src="{{ asset('storage/' . $part->image) }}" alt="{{ $part->name }}"
                                        class="h-10 w-10 object-cover rounded">
                                @else
                                    <span class="text-gray-400">—</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex justify-end items-center gap-3">
                                    @auth
                                        @if (auth()->user()->isUser())
                                            @if ($part->amount > 0)
                                                <form action="{{ route('cart.addPart', $part) }}" method="POST" class="inline">
                                                    @csrf
                                                    <input type="hidden" name="quantity" value="1">
                                                    <button type="submit"
                                                        class="text-green-600 hover:text-green-500 font-medium">Pridėti į
                                                        krepšelį</button>
                                                </form>
                                            @else
                                                <span class="text-gray-400">Nėra sandėlyje</span>
                                            @endif
                                        @endif
                                        @if (auth()->user()->hasRole([\App\Enums\Role::Admin, \App\Enums\Role::Worker]))
                                            <a href="{{ route('warehouse.edit', $part) }}"
                                                class="text-indigo-600 hover:text-indigo-500">Redaguoti</a>
                                            <form action="{{ route('warehouse.destroy', $part) }}" method="POST"
                                                class="inline" onsubmit="return confirm('Šalinti šią dalį?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-500">Šalinti</button>
                                            </form>
                                        @endif
                                    @endauth
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500">Dalių dar nėra. Spauskite "Nauja dalį" norėdami pridėti.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">{{ $parts->links() }}</div>
    </div>
@endsection
