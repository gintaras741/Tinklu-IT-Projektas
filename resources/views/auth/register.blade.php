<x-layouts.auth>
    <div
        class="bg-white dark:bg-gray-800 rounded-lg shadow-md border border-gray-200 dark:border-gray-700 overflow-hidden">
        <div class="p-6">
            <div class="mb-3">
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Registruoti paskyrą</h1>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-3">
                @csrf
                <!-- Full Name Input -->
                <div>
                    <x-forms.input label="Vardas ir pavardė" name="name" type="text" placeholder="Vardas ir pavardė"
                        autofocus />
                </div>

                <!-- Email Input -->
                <div>
                    <x-forms.input label="El. paštas" name="email" type="email" placeholder="jusu@pastas.lt" />
                </div>

                <!-- Role Input -->
                <div>
                    <label for="role"
                        class="block ml-1 text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Rolė</label>
                    <select id="role" name="role"
                        class="w-full px-4 py-1.5 rounded-lg text-gray-700 dark:text-gray-300 bg-gray-50 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="user" {{ old('role', 'user') === 'user' ? 'selected' : '' }}>Naudotojas</option>
                        <option value="worker" {{ old('role') === 'worker' ? 'selected' : '' }}>Darbuotojas</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administratorius</option>
                    </select>
                    @error('role')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Password Input -->
                <div>
                    <x-forms.input label="Slaptažodis" name="password" type="password" placeholder="••••••••" />
                </div>

                <!-- Confirm Password Input -->
                <div>
                    <x-forms.input label="Patvirtinkite slaptažodį" name="password_confirmation" type="password"
                        placeholder="••••••••" />
                </div>

                <!-- Register Button -->
                <x-button type="primary" class="w-full">Sukurti paskyrą</x-button>
            </form>

            <!-- Login Link -->
            <div class="text-center mt-6">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Jau turite paskyrą?
                    <a href="{{ route('login') }}"
                        class="text-blue-600 dark:text-blue-400 hover:underline font-medium">Prisijungti</a>
                </p>
            </div>
        </div>
    </div>
</x-layouts.auth>

