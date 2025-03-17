<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Trip;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalBuses = Bus::count();
        $totalTrips = Trip::count();
        $totalBookings = Booking::count();
        $totalUsers = User::count();
        
        // Get buses with seats and bookings information
        $buses = Bus::with(['seats', 'trips.bookings.user', 'trips.bookings.seat'])->get();
        
        // For each bus, collect seat booking information
        foreach ($buses as $bus) {
            $bookingInfo = [];
            
            // Get all trips for this bus
            foreach ($bus->trips as $trip) {
                // For each seat, check if it's booked
                foreach ($bus->seats as $seat) {
                    // Find bookings for this seat in this trip
                    $bookings = $trip->bookings->where('seat_id', $seat->id);
                    
                    if (!isset($bookingInfo[$seat->id])) {
                        $bookingInfo[$seat->id] = [
                            'seat_number' => $seat->seat_number,
                            'bookings' => []
                        ];
                    }
                    
                    // Add booking information
                    foreach ($bookings as $booking) {
                        $bookingInfo[$seat->id]['bookings'][] = [
                            'trip_name' => $trip->name,
                            'user_name' => $booking->user->name,
                            'from' => $booking->fromStation->city->name,
                            'to' => $booking->toStation->city->name
                        ];
                    }
                }
            }
            
            $bus->bookingInfo = $bookingInfo;
        }
        
        return view('admin.dashboard', compact('totalBuses', 'totalTrips', 'totalBookings', 'totalUsers', 'buses'));
    }
    
    // Other methods remain the same...
}