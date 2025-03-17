<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Trip Details: {{ $trip->name }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Trip Information</h3>
                        <a href="{{ route('admin.trips.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Trips
                        </a>
                    </div>
                    
                    <div class="mb-6">
                        <p><strong>ID:</strong> {{ $trip->id }}</p>
                        <p><strong>Name:</strong> {{ $trip->name }}</p>
                        <p><strong>Bus:</strong> {{ $trip->bus->name }}</p>
                        <p><strong>Created At:</strong> {{ $trip->created_at->format('M d, Y H:i:s') }}</p>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="font-semibold mb-2">Route</h4>
                        <div class="flex items-center flex-wrap mb-4">
                            @foreach ($trip->tripStations as $index => $station)
                                <div class="flex items-center">
                                    <div class="rounded-full bg-blue-500 text-white w-8 h-8 flex items-center justify-center">
                                        {{ $station->order }}
                                    </div>
                                    <span class="mx-2">{{ $station->city->name }}</span>
                                </div>
                                @if ($index < $trip->tripStations->count() - 1)
                                    <div class="mx-2 text-gray-400">→</div>
                                @endif
                            @endforeach
                        </div>
                    </div>
                    
                    <div class="mb-6">
                        <h4 class="font-semibold mb-2">Seat Bookings</h4>
                        <div class="grid grid-cols-3 md:grid-cols-6 gap-3">
                            @foreach ($seats as $seat)
                                @php
                                    $hasBookings = isset($bookingsBySeat[$seat->id]);
                                    $bgColor = $hasBookings ? 'bg-red-100' : 'bg-green-100';
                                    $textColor = $hasBookings ? 'text-red-800' : 'text-green-800';
                                @endphp
                                <div class="{{ $bgColor }} p-3 rounded">
                                    <div class="font-bold {{ $textColor }}">{{ $seat->seat_number }}</div>
                                    @if ($hasBookings)
                                        <div class="text-xs mt-1">
                                            <span class="block">Booked</span>
                                            @foreach ($bookingsBySeat[$seat->id] as $booking)
                                                <span class="block text-xs text-gray-600">
                                                    {{ $booking->fromStation->city->name }} to {{ $booking->toStation->city->name }}
                                                </span>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-xs mt-1">Available</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>