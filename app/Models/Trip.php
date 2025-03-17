<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'bus_id'];
    
    public function bus(): BelongsTo
    {
        return $this->belongsTo(Bus::class);
    }
    
    public function tripStations(): HasMany
    {
        return $this->hasMany(TripStation::class)->orderBy('order');
    }
    
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }
}