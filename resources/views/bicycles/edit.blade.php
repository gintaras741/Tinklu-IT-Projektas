@extends('layouts.app')

@section('title', 'Redaguoti ' . $bicycle->name)

@section('content')
    <div class="max-w-4xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('bicycles.show', $bicycle) }}"
                class="inline-flex items-center text-sm text-gray-600 hover:text-indigo-600 mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 mr-1">
                    <path fill-rule="evenodd"
                        d="M17 10a.75.75 0 01-.75.75H5.612l4.158 3.96a.75.75 0 11-1.04 1.08l-5.5-5.25a.75.75 0 010-1.08l5.5-5.25a.75.75 0 111.04 1.08L5.612 9.25H16.25A.75.75 0 0117 10z"
                        clip-rule="evenodd" />
                </svg>
                Grįžti į dvirati
            </a>
            <h1 class="text-3xl font-semibold text-gray-900">Redaguoti dvirati</h1>
        </div>

        @if (session('error'))
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-red-800">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('bicycles.update', $bicycle) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Dviračio detalės</h2>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Dviračio pavadinimas</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $bicycle->name) }}"
                        class="block w-full h-11 rounded-lg border border-gray-300 bg-white px-4 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="pvz., Mano kalnų dviratis" required>
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900">Pasirinkti komponentus</h2>
                    <button type="button" onclick="addComponent()"
                        class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-500">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                            <path
                                d="M10.75 4.75a.75.75 0 00-1.5 0v4.5h-4.5a.75.75 0 000 1.5h4.5v4.5a.75.75 0 001.5 0v-4.5h4.5a.75.75 0 000-1.5h-4.5v-4.5z" />
                        </svg>
                        Pridėti komponentą
                    </button>
                </div>

                @error('components')
                    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                        {{ $message }}
                    </div>
                @enderror

                <div id="components-container" class="space-y-4">
                    @php
                        $components = old(
                            'components',
                            $bicycle->components
                                ->map(function ($c) {
                                    return ['bicycle_part_id' => $c->bicycle_part_id, 'quantity' => $c->quantity];
                                })
                                ->toArray(),
                        );
                    @endphp

                    @foreach ($components as $index => $component)
                        <div class="component-row flex gap-3 items-start p-4 bg-gray-50 rounded-lg">
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dalis</label>
                                <div class="flex gap-3">
                                    <select name="components[{{ $index }}][bicycle_part_id]"
                                        class="part-select block w-full h-11 rounded-lg border border-gray-300 bg-white px-4 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                        onchange="updatePartImage(this)" required>
                                        <option value="">Pasirinkite dalį...</option>
                                        @foreach ($types as $type)
                                            @if (isset($partsByType[$type->value]))
                                                <optgroup label="{{ ucfirst(str_replace('_', ' ', $type->value)) }}">
                                                    @foreach ($partsByType[$type->value] as $part)
                                                        <option value="{{ $part->id }}" data-max="{{ $part->amount }}"
                                                            data-image="{{ $part->image ? asset('storage/' . $part->image) : '' }}"
                                                            {{ $component['bicycle_part_id'] == $part->id ? 'selected' : '' }}>
                                                            {{ $part->name }} ({{ $part->amount }} turima)
                                                        </option>
                                                    @endforeach
                                                </optgroup>
                                            @endif
                                        @endforeach
                                    </select>
                                    <div class="part-image-container w-16 h-11 flex-shrink-0">
                                        <img class="part-image hidden w-full h-full object-cover rounded-lg border border-gray-200"
                                            alt="Part image">
                                        <div
                                            class="part-placeholder w-full h-full bg-gray-100 rounded-lg flex items-center justify-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>
                                @error("components.$index.bicycle_part_id")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="w-32">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kiekis</label>
                                <input type="number" name="components[{{ $index }}][quantity]"
                                    value="{{ $component['quantity'] }}" min="1"
                                    class="quantity-input block w-full h-11 rounded-lg border border-gray-300 bg-white px-4 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                    required>
                                @error("components.$index.quantity")
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="pt-7">
                                <button type="button" onclick="removeComponent(this)"
                                    class="text-red-600 hover:text-red-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="w-5 h-5">
                                        <path fill-rule="evenodd"
                                            d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div id="empty-state" class="text-center py-8 text-gray-500" style="display: none;">
                    <p>Dar nepridėta jokių komponentų. Paspauskite "Pridėti komponentą", kad pradėtumėte kurti dvirati.</p>
                </div>
            </div>

            <div class="flex gap-3">
                <button type="submit"
                    class="flex-1 inline-flex justify-center items-center rounded-md bg-indigo-600 px-4 py-2 text-white shadow hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                    Update Bicycle
                </button>
                <a href="{{ route('bicycles.show', $bicycle) }}"
                    class="inline-flex items-center rounded-md bg-white border border-gray-300 px-4 py-2 text-gray-700 shadow-sm hover:bg-gray-50">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script>
        let componentIndex = {{ count($components) }};

        function addComponent() {
            const container = document.getElementById('components-container');
            const emptyState = document.getElementById('empty-state');

            const componentHtml = `
                <div class="component-row flex gap-3 items-start p-4 bg-gray-50 rounded-lg">
                    <div class="flex-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Dalis</label>
                        <div class="flex gap-3">
                            <select name="components[${componentIndex}][bicycle_part_id]"
                                class="part-select block w-full h-11 rounded-lg border border-gray-300 bg-white px-4 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                onchange="updatePartImage(this)" required>
                                <option value="">Pasirinkite dalį...</option>
                                @foreach ($types as $type)
                                    @if (isset($partsByType[$type->value]))
                                        <optgroup label="{{ ucfirst(str_replace('_', ' ', $type->value)) }}">
                                            @foreach ($partsByType[$type->value] as $part)
                                                <option value="{{ $part->id }}" 
                                                    data-max="{{ $part->amount }}"
                                                    data-image="{{ $part->image ? asset('storage/' . $part->image) : '' }}">
                                                    {{ $part->name }} ({{ $part->amount }} available)
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endif
                                @endforeach
                            </select>
                            <div class="part-image-container w-16 h-11 flex-shrink-0">
                                <img class="part-image hidden w-full h-full object-cover rounded-lg border border-gray-200" alt="Part image">
                                <div class="part-placeholder w-full h-full bg-gray-100 rounded-lg flex items-center justify-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 text-gray-400">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="w-32">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kiekis</label>
                        <input type="number" name="components[${componentIndex}][quantity]" value="1" min="1"
                            class="quantity-input block w-full h-11 rounded-lg border border-gray-300 bg-white px-4 text-base shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            required>
                    </div>

                    <div class="pt-7">
                        <button type="button" onclick="removeComponent(this)"
                            class="text-red-600 hover:text-red-500">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                class="w-5 h-5">
                                <path fill-rule="evenodd"
                                    d="M8.75 1A2.75 2.75 0 006 3.75v.443c-.795.077-1.584.176-2.365.298a.75.75 0 10.23 1.482l.149-.022.841 10.518A2.75 2.75 0 007.596 19h4.807a2.75 2.75 0 002.742-2.53l.841-10.52.149.023a.75.75 0 00.23-1.482A41.03 41.03 0 0014 4.193V3.75A2.75 2.75 0 0011.25 1h-2.5zM10 4c.84 0 1.673.025 2.5.075V3.75c0-.69-.56-1.25-1.25-1.25h-2.5c-.69 0-1.25.56-1.25 1.25v.325C8.327 4.025 9.16 4 10 4zM8.58 7.72a.75.75 0 00-1.5.06l.3 7.5a.75.75 0 101.5-.06l-.3-7.5zm4.34.06a.75.75 0 10-1.5-.06l-.3 7.5a.75.75 0 101.5.06l.3-7.5z"
                                    clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', componentHtml);
            componentIndex++;
            emptyState.style.display = 'none';
        }

        function removeComponent(button) {
            const row = button.closest('.component-row');
            row.remove();

            const container = document.getElementById('components-container');
            const emptyState = document.getElementById('empty-state');

            if (container.children.length === 0) {
                emptyState.style.display = 'block';
            }
        }

        function updatePartImage(select) {
            const row = select.closest('.component-row');
            const quantityInput = row.querySelector('.quantity-input');
            const container = row.querySelector('.part-image-container');
            const img = container.querySelector('.part-image');
            const placeholder = container.querySelector('.part-placeholder');
            const selectedOption = select.options[select.selectedIndex];
            const maxQuantity = selectedOption.dataset.max;
            const imageUrl = selectedOption.dataset.image;

            // Update quantity validation
            if (maxQuantity) {
                quantityInput.max = maxQuantity;
                if (parseInt(quantityInput.value) > parseInt(maxQuantity)) {
                    quantityInput.value = maxQuantity;
                }
            }

            // Update image display
            if (imageUrl) {
                img.src = imageUrl;
                img.classList.remove('hidden');
                placeholder.classList.add('hidden');
            } else {
                img.classList.add('hidden');
                placeholder.classList.remove('hidden');
            }
        }

        // Initialize empty state and images on page load
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('components-container');
            const emptyState = document.getElementById('empty-state');

            if (container.children.length === 0) {
                emptyState.style.display = 'block';
            }

            // Initialize images for existing components
            container.querySelectorAll('.part-select').forEach(select => {
                if (select.value) {
                    updatePartImage(select);
                }
            });
        });
    </script>
@endsection
