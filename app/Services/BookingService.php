<?php

namespace App\Services;

use App\Models\Trip;
use App\Models\Seat;
use App\Models\Booking;
use App\Models\TripStation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class BookingService
{
    /**
     * Get available seats for a specific trip between two stations
     * 
     * @param int $tripId The ID of the trip
     * @param int $fromCityId The ID of the starting city
     * @param int $toCityId The ID of the destination city
     * @return Collection Available seats
     */
    public function getAvailableSeats(int $tripId, int $fromCityId, int $toCityId): Collection
    {
        $trip = Trip::findOrFail($tripId);
        
        // Get the trip stations for the from and to cities
        $fromStation = $trip->tripStations()
            ->whereHas('city', function ($query) use ($fromCityId) {
                $query->where('id', $fromCityId);
            })
            ->firstOrFail();
            
        $toStation = $trip->tripStations()
            ->whereHas('city', function ($query) use ($toCityId) {
                $query->where('id', $toCityId);
            })
            ->firstOrFail();
            
        // Ensure that the "from" station comes before the "to" station
        if ($fromStation->order >= $toStation->order) {
            throw new \InvalidArgumentException('The from station must come before the to station');
        }
        
        // Get all seats for the bus
        $allSeats = $trip->bus->seats;
        
        // Get all booked seats that overlap with our route
        $bookedSeatIds = $this->getBookedSeatIds($trip->id, $fromStation->order, $toStation->order);
        
        // Return only the available seats
        return $allSeats->whereNotIn('id', $bookedSeatIds);
    }
    
    /**
     * Book a seat for a user
     * 
     * @param int $tripId The ID of the trip
     * @param int $seatId The ID of the seat
     * @param int $userId The ID of the user
     * @param int $fromCityId The ID of the starting city
     * @param int $toCityId The ID of the destination city
     * @return Booking The booking model
     */
    public function bookSeat(int $tripId, int $seatId, int $userId, int $fromCityId, int $toCityId): Booking
    {
        $trip = Trip::findOrFail($tripId);
        $seat = Seat::findOrFail($seatId);
        
        // Check if seat belongs to the trip's bus
        if ($seat->bus_id !== $trip->bus_id) {
            throw new \InvalidArgumentException('The seat does not belong to the trip\'s bus');
        }
        
        // Get the trip stations for the from and to cities
        $fromStation = $trip->tripStations()
            ->whereHas('city', function ($query) use ($fromCityId) {
                $query->where('id', $fromCityId);
            })
            ->firstOrFail();
            
        $toStation = $trip->tripStations()
            ->whereHas('city', function ($query) use ($toCityId) {
                $query->where('id', $toCityId);
            })
            ->firstOrFail();
            
        // Ensure that the "from" station comes before the "to" station
        if ($fromStation->order >= $toStation->order) {
            throw new \InvalidArgumentException('The from station must come before the to station');
        }
        
        // Check if seat is available for this route
        $bookedSeatIds = $this->getBookedSeatIds($trip->id, $fromStation->order, $toStation->order);
        
        if (in_array($seatId, $bookedSeatIds)) {
            throw new \InvalidArgumentException('The seat is already booked for this route');
        }
        
        // Create the booking
        return DB::transaction(function () use ($trip, $seat, $userId, $fromStation, $toStation) {
            return Booking::create([
                'trip_id' => $trip->id,
                'seat_id' => $seat->id,
                'user_id' => $userId,
                'from_station_id' => $fromStation->id,
                'to_station_id' => $toStation->id,
            ]);
        });
    }
    
    /**
     * Get IDs of seats that are booked for a part of the route
     * 
     * @param int $tripId The ID of the trip
     * @param int $fromOrder The order of the starting station
     * @param int $toOrder The order of the destination station
     * @return array Array of seat IDs
     */
    private function getBookedSeatIds(int $tripId, int $fromOrder, int $toOrder): array
    {
        // Get all bookings for this trip
        $bookings = Booking::where('trip_id', $tripId)->get();
        
        // Filter out bookings that don't overlap with our route
        $overlappingBookings = $bookings->filter(function ($booking) use ($fromOrder, $toOrder) {
            $bookingFromOrder = $booking->fromStation->order;
            $bookingToOrder = $booking->toStation->order;
            
            // Check if the ranges overlap
            // Two ranges [a,b] and [c,d] overlap if a <= d AND c <= b
            return $bookingFromOrder < $toOrder && $fromOrder < $bookingToOrder;
        });
        
        // Return the seat IDs of the overlapping bookings
        return $overlappingBookings->pluck('seat_id')->toArray();
    }
}