<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bus Details: {{ $bus->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Bus Information</h3>
                        <a href="{{ route('admin.buses.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Buses
                        </a>
                    </div>
                    
                    <div class="mb-6">
                        <p><strong>ID:</strong> {{ $bus->id }}</p>
                        <p><strong>Name:</strong> {{ $bus->name }}</p>
                        <p><strong>Number of Seats:</strong> {{ $bus->seats->count() }}</p>
                        <p><strong>Created At:</strong> {{ $bus->created_at->format('M d, Y H:i:s') }}</p>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="font-semibold mb-2">Seats</h4>
                        <div class="grid grid-cols-3 md:grid-cols-6 gap-2">
                            @foreach ($bus->seats as $seat)
                                <div class="bg-blue-100 p-3 rounded text-center">
                                    {{ $seat->seat_number }}
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="font-semibold mb-2">Trips Using This Bus</h4>
                        @if ($bus->trips->isEmpty())
                            <p>No trips are currently using this bus.</p>
                        @else
                            <ul class="list-disc pl-5">
                                @foreach ($bus->trips as $trip)
                                    <li>
                                        <a href="{{ route('admin.trips.show', $trip) }}" class="text-blue-500 hover:underline">
                                            {{ $trip->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>