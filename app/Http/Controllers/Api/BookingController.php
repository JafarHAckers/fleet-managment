<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AvailableSeatsRequest;
use App\Http\Requests\BookSeatRequest;
use App\Http\Resources\SeatResource;
use App\Http\Resources\BookingResource;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookingController extends Controller
{
    protected BookingService $bookingService;
    
    public function __construct(BookingService $bookingService)
    {
        $this->bookingService = $bookingService;
    }
    
    /**
     * Get available seats for a trip between two stations
     * 
     * @param AvailableSeatsRequest $request
     * @return AnonymousResourceCollection
     */
    public function getAvailableSeats(AvailableSeatsRequest $request): AnonymousResourceCollection
    {
        $availableSeats = $this->bookingService->getAvailableSeats(
            $request->trip_id,
            $request->from_city_id,
            $request->to_city_id
        );
        
        return SeatResource::collection($availableSeats);
    }
    
    /**
     * Book a seat for a user
     * 
     * @param BookSeatRequest $request
     * @return BookingResource
     */
    public function bookSeat(BookSeatRequest $request): BookingResource
    {
        $booking = $this->bookingService->bookSeat(
            $request->trip_id,
            $request->seat_id,
            auth()->id(),
            $request->from_city_id,
            $request->to_city_id
        );
        
        return new BookingResource($booking);
    }
}