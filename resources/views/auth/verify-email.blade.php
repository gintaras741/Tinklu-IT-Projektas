<x-layouts.auth :title="__('Verify Email')">
    <!-- Verify Email Card -->
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <div class="text-center mb-6">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Patvirtinkite savo el. pašto adresą
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">
                    Prieš tęsiant, patikrinkite savo el. paštą dėl patvirtinimo nuorodos.<br>
                    Jei negavote el. laiško, galite užsisakyti kitą žemiau.
                </p>
            </div>

            @if (session('status') === 'verification-link-sent')
                <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                    Nauja patvirtinimo nuoroda buvo išsiųsta į jūsų el. pašto adresą.
                </div>
            @endif

            <form method="POST" action="{{ route('verification.store') }}">
                @csrf
                <x-button type="primary" buttonType="submit" class="w-full">
                    Siųsti patvirtinimo el. laišką dar kartą
                </x-button>
            </form>

            <div class="text-center mt-6">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-blue-600 dark:text-blue-400 hover:underline font-medium">
                        Atsijungti
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.auth>

