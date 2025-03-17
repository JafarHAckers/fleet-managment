<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">System Overview</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                        <div class="bg-blue-100 rounded-lg p-4">
                            <h4 class="font-bold text-lg">{{ $totalBuses }}</h4>
                            <p class="text-gray-700">Buses</p>
                            <a href="{{ route('admin.buses.index') }}" class="text-blue-600 hover:underline text-sm">View All</a>
                        </div>
                        
                        <div class="bg-green-100 rounded-lg p-4">
                            <h4 class="font-bold text-lg">{{ $totalTrips }}</h4>
                            <p class="text-gray-700">Trips</p>
                            <a href="{{ route('admin.trips.index') }}" class="text-green-600 hover:underline text-sm">View All</a>
                        </div>
                        
                        <div class="bg-yellow-100 rounded-lg p-4">
                            <h4 class="font-bold text-lg">{{ $totalBookings }}</h4>
                            <p class="text-gray-700">Bookings</p>
                            <a href="{{ route('admin.bookings.index') }}" class="text-yellow-600 hover:underline text-sm">View All</a>
                        </div>
                        
                        <div class="bg-purple-100 rounded-lg p-4">
                            <h4 class="font-bold text-lg">{{ $totalUsers }}</h4>
                            <p class="text-gray-700">Users</p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Bus and Seat Information -->
            @foreach($buses as $bus)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-semibold mb-4">{{ $bus->name }} - Seat Status</h3>
                    
                    <div class="mb-4">
                        <p><strong>Total Seats:</strong> {{ $bus->seats->count() }}</p>
                        <p><strong>Used in Trips:</strong> {{ $bus->trips->count() }} trips</p>
                    </div>
                    
                    <div class="mb-4">
                        <h4 class="font-medium mb-2">Seat Booking Status:</h4>
                        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            @foreach($bus->seats as $seat)
                                @php
                                    $hasBookings = isset($bus->bookingInfo[$seat->id]) && count($bus->bookingInfo[$seat->id]['bookings']) > 0;
                                    $bgColor = $hasBookings ? 'bg-red-100' : 'bg-green-100';
                                    $textColor = $hasBookings ? 'text-red-800' : 'text-green-800';
                                @endphp
                                <div class="{{ $bgColor }} p-3 rounded">
                                    <div class="font-bold {{ $textColor }}">{{ $seat->seat_number }}</div>
                                    @if($hasBookings)
                                        <div class="text-xs mt-1">
                                            <span class="block font-semibold">Booked by:</span>
                                            @foreach($bus->bookingInfo[$seat->id]['bookings'] as $booking)
                                                <div class="mt-1 p-1 bg-white rounded text-gray-800">
                                                    <div><strong>User:</strong> {{ $booking['user_name'] }}</div>
                                                    <div><strong>Trip:</strong> {{ $booking['trip_name'] }}</div>
                                                    <div><strong>Route:</strong> {{ $booking['from'] }} to {{ $booking['to'] }}</div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <div class="text-xs mt-1">Available</div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <div>
                        <a href="{{ route('admin.buses.show', $bus) }}" class="text-blue-500 hover:underline">View Complete Details</a>
                    </div>
                </div>
            </div>
            @endforeach
            
            <div class="flex space-x-4">
                <a href="{{ route('admin.buses.index') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Manage Buses
                </a>
                <a href="{{ route('admin.trips.index') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Manage Trips
                </a>
                <a href="{{ route('admin.bookings.index') }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">
                    View Bookings
                </a>
            </div>
        </div>
    </div>
</x-app-layout>