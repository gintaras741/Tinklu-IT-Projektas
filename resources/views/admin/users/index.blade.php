@extends('layouts.app')

@section('title', 'Valdyti vartotojus')

@section('content')
    @php($status = session('status'))
    @php($error = session('error'))

    <div class="max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-semibold text-gray-900">Valdyti vartotojus</h1>
                <p class="mt-1 text-sm text-gray-600">Peržiūrėti, redaguoti ir valdyti vartotojų paskyras</p>
            </div>
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

        <!-- Search and Filter -->
        <div class="mb-6 rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Ieškoti pagal vardą ar el. paštą..."
                        class="w-full h-10 rounded-lg border border-gray-300 bg-white px-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                </div>
                <div class="w-48">
                    <select name="role"
                        class="w-full h-10 rounded-lg border border-gray-300 bg-white px-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Visos rolės</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->value }}" @selected(request('role') === $role->value)>
                                {{ ucfirst($role->value) }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <button type="submit"
                    class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow hover:bg-indigo-500 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Ieškoti
                </button>
                @if (request('search') || request('role'))
                    <a href="{{ route('admin.users.index') }}"
                        class="inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                        Išvalyti
                    </a>
                @endif
            </form>
        </div>

        @if ($users->isEmpty())
            <div class="rounded-xl border border-gray-200 bg-white p-12 text-center shadow-sm">
                <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">Vartotojų nerasta</h3>
                <p class="text-gray-500">Bandykite pakoreguoti paieškos ar filtravimo kriterijus.</p>
            </div>
        @else
            <div class="mb-4 text-sm text-gray-600">
                Rodoma {{ $users->firstItem() }} iki {{ $users->lastItem() }} iš {{ $users->total() }} vartotojų
            </div>

            <div class="rounded-xl border border-gray-200 bg-white shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">ID</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Vardas</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">El. paštas</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Rolė</th>
                            <th class="px-6 py-3 text-left font-semibold text-gray-700">Prisijungė</th>
                            <th class="px-6 py-3 text-right font-semibold text-gray-700">Veiksmai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($users as $user)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 text-gray-700">{{ $user->id }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-100 text-indigo-600 font-semibold text-sm">
                                            {{ $user->initials() }}
                                        </div>
                                        <span class="font-medium text-gray-900">{{ $user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-700">{{ $user->email }}</td>
                                <td class="px-6 py-4">
                                    @if ($user->role->value === 'admin')
                                        <span
                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-red-100 text-red-800">
                                            Administratorius
                                        </span>
                                    @elseif($user->role->value === 'worker')
                                        <span
                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-blue-100 text-blue-800">
                                            Darbuotojas
                                        </span>
                                    @else
                                        <span
                                            class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium bg-gray-100 text-gray-800">
                                            Vartotojas
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-gray-600">{{ $user->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <div class="flex justify-end items-center gap-3">
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="text-indigo-600 hover:text-indigo-500 font-medium">
                                            Redaguoti
                                        </a>
                                        @if ($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Ar tikrai norite ištrinti {{ $user->name }}? Šio veiksmo negalima atstatyti.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-500 font-medium">
                                                    Ištrinti
                                                </button>
                                            </form>
                                        @else
                                            <span class="text-gray-400 text-sm">(Jūs)</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="mt-6">
                    {{ $users->links() }}
                </div>
            @endif
        @endif
    </div>
@endsection
