<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id', 
        'seat_id', 
        'user_id', 
        'from_station_id', 
        'to_station_id'
    ];
    
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
    
    public function seat(): BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    public function fromStation(): BelongsTo
    {
        return $this->belongsTo(TripStation::class, 'from_station_id');
    }
    
    public function toStation(): BelongsTo
    {
        return $this->belongsTo(TripStation::class, 'to_station_id');
    }
}