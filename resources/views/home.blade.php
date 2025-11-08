@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="relative isolate overflow-hidden rounded-2xl bg-white p-8 shadow-sm border border-gray-200">
        <div class="mx-auto max-w-2xl text-center">
            <h1 class="text-4xl font-bold tracking-tight text-gray-900 sm:text-5xl">Welcome to the Bicycle shop</h1>
            <p class="mt-4 text-lg leading-8 text-gray-600">Manage your bicycles, order parts, and keep your warehouse
                organized.</p>
            <div class="mt-8 flex items-center justify-center gap-x-4">
                <a href="#"
                    class="rounded-md bg-indigo-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2">Order
                    Parts</a>
                <a href="#" class="text-sm font-semibold leading-6 text-gray-900">My Bicycles</a>
                @auth
                    @if (auth()->user()->isAdmin() || auth()->user()->isWorker())
                        <a href="{{ route('warehouse.index') }}"
                            class="text-sm font-semibold leading-6 text-gray-900">Warehouse</a>
                    @endif
                @endauth
            </div>
        </div>
    </div>
@endsection
