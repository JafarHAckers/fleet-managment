<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $trip->name }}
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
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-bold mb-2">Trip Information</h3>
                        <p><strong>Bus:</strong> {{ $trip->bus->name }}</p>
                        <p><strong>Stations:</strong> {{ $cities->pluck('name')->implode(' -> ') }}</p>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-bold mb-2">Book a Seat</h3>
                        <form action="{{ route('trips.select-seats', $trip) }}" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label for="from_city_id" class="block text-sm font-medium text-gray-700">From</label>
                                <select id="from_city_id" name="from_city_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="mb-4">
                                <label for="to_city_id" class="block text-sm font-medium text-gray-700">To</label>
                                <select id="to_city_id" name="to_city_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    @foreach ($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Find Available Seats
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>