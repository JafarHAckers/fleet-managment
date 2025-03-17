<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Available Trips') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('error'))
                        <div class="mb-4 text-red-600">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="space-y-6">
                        @foreach ($trips as $trip)
                            <div class="border rounded p-4 flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-bold">{{ $trip->name }}</h3>
                                    <p class="text-gray-600">Bus: {{ $trip->bus->name }}</p>
                                </div>
                                <a href="{{ route('trips.show', $trip) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    View Details
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>