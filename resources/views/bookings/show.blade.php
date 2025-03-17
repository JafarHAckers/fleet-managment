<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Booking Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-bold mb-2">Booking Information</h3>
                        <p><strong>Booking ID:</strong> #{{ $booking->id }}</p>
                        <p><strong>Trip:</strong> {{ $booking->trip->name }}</p>
                        <p><strong>Bus:</strong> {{ $booking->trip->bus->name }}</p>
                        <p><strong>Seat Number:</strong> {{ $booking->seat->seat_number }}</p>
                        <p><strong>From:</strong> {{ $booking->fromStation->city->name }}</p>
                        <p><strong>To:</strong> {{ $booking->toStation->city->name }}</p>
                        <p><strong>Booking Date:</strong> {{ $booking->created_at->format('M d, Y H:i') }}</p>
                    </div>
                    
                    <div>
                        <a href="{{ route('bookings.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to My Bookings
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>