<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Available Seats: {{ $fromCity->name }} to {{ $toCity->name }}
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
                        <p><strong>Trip:</strong> {{ $trip->name }}</p>
                        <p><strong>Bus:</strong> {{ $trip->bus->name }}</p>
                        <p><strong>Route:</strong> {{ $fromCity->name }} to {{ $toCity->name }}</p>
                    </div>
                    
                    <div class="mb-6">
                        <h3 class="text-lg font-bold mb-2">Available Seats ({{ $availableSeats->count() }})</h3>
                        
                        @if ($availableSeats->isEmpty())
                            <p class="text-red-600">No seats available for this route.</p>
                        @else
                            <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                                @foreach ($availableSeats as $seat)
                                    <form action="{{ route('book.seat') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="trip_id" value="{{ $trip->id }}">
                                        <input type="hidden" name="seat_id" value="{{ $seat->id }}">
                                        <input type="hidden" name="from_city_id" value="{{ $fromCity->id }}">
                                        <input type="hidden" name="to_city_id" value="{{ $toCity->id }}">
                                        
                                        <button type="submit" class="w-full p-4 border border-green-500 text-green-500 rounded hover:bg-green-500 hover:text-white">
                                            {{ $seat->seat_number }}
                                        </button>
                                    </form>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    
                    <div>
                        <a href="{{ route('trips.show', $trip) }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Trip Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>