<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Bookings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if (session('success'))
                        <div class="mb-4 text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($bookings->isEmpty())
                        <p>You have no bookings yet.</p>
                    @else
                        <div class="space-y-6">
                            @foreach ($bookings as $booking)
                                <div class="border rounded p-4">
                                    <div class="flex justify-between">
                                        <h3 class="text-lg font-bold">{{ $booking->trip->name }}</h3>
                                        <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-sm">Seat {{ $booking->seat->seat_number }}</span>
                                    </div>
                                    <p class="text-gray-600">
                                        From: {{ $booking->fromStation->city->name }} - 
                                        To: {{ $booking->toStation->city->name }}
                                    </p>
                                    <p class="text-gray-500 text-sm">Booked on: {{ $booking->created_at->format('M d, Y') }}</p>
                                    <div class="mt-2">
                                        <a href="{{ route('bookings.show', $booking) }}" class="text-blue-500 hover:underline">View Details</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>