<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TripStation extends Model
{
    use HasFactory;

    protected $fillable = ['trip_id', 'city_id', 'order'];
    
    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }
    
    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
    
    public function fromBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'from_station_id');
    }
    
    public function toBookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'to_station_id');
    }
}