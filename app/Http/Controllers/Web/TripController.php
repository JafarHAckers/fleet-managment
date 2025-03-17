<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\City;
use App\Models\Booking;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TripController extends Controller
{
    protected BookingService $bookingService;
    
    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }
    
    public function index()
    {
        $trips = Trip::with('bus')->get();
        return view('trips.index', compact('trips'));
    }
    
    public function show(Trip $trip)
    {
        $cities = $trip->tripStations->pluck('city');
        return view('trips.show', compact('trip', 'cities'));
    }
    
    public function selectSeats(Request $request, Trip $trip)
    {
        $request->validate([
            'from_city_id' => 'required|exists:cities,id',
            'to_city_id' => 'required|exists:cities,id|different:from_city_id',
        ]);
        
        $fromCity = City::findOrFail($request->from_city_id);
        $toCity = City::findOrFail($request->to_city_id);
        
        try {
            $availableSeats = $this->bookingService->getAvailableSeats(
                $trip->id,
                $request->from_city_id,
                $request->to_city_id
            );
            
            return view('trips.seats', compact('trip', 'fromCity', 'toCity', 'availableSeats'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
    public function bookSeat(Request $request)
    {
        $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'seat_id' => 'required|exists:seats,id',
            'from_city_id' => 'required|exists:cities,id',
            'to_city_id' => 'required|exists:cities,id|different:from_city_id',
        ]);
        
        try {
            $booking = $this->bookingService->bookSeat(
                $request->trip_id,
                $request->seat_id,
                Auth::id(),
                $request->from_city_id,
                $request->to_city_id
            );
            
            return redirect()->route('bookings.show', $booking->id)
                ->with('success', 'Seat booked successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
    
    public function myBookings()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with(['trip', 'seat', 'fromStation.city', 'toStation.city'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('bookings.index', compact('bookings'));
    }
    
    public function showBooking(Booking $booking)
    {
        if ($booking->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('bookings.show', compact('booking'));
    }
}