<?php

namespace App\Http\Requests;

use App\Models\Trip;
use App\Models\City;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AvailableSeatsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Allow all authenticated users
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'trip_id' => ['required', 'integer', 'exists:trips,id'],
            'from_city_id' => [
                'required', 
                'integer', 
                'exists:cities,id',
                function ($attribute, $value, $fail) {
                    // Check if the city is part of the trip
                    $trip = Trip::find($this->trip_id);
                    if (!$trip || !$trip->tripStations()->whereHas('city', function ($query) use ($value) {
                        $query->where('id', $value);
                    })->exists()) {
                        $fail('The selected from city is not part of this trip.');
                    }
                }
            ],
            'to_city_id' => [
                'required', 
                'integer', 
                'exists:cities,id',
                'different:from_city_id',
                function ($attribute, $value, $fail) {
                    // Check if the city is part of the trip
                    $trip = Trip::find($this->trip_id);
                    if (!$trip || !$trip->tripStations()->whereHas('city', function ($query) use ($value) {
                        $query->where('id', $value);
                    })->exists()) {
                        $fail('The selected to city is not part of this trip.');
                    }
                    
                    // Check if to_city comes after from_city in the trip
                    if ($trip) {
                        $fromStation = $trip->tripStations()->whereHas('city', function ($query) {
                            $query->where('id', $this->from_city_id);
                        })->first();
                        
                        $toStation = $trip->tripStations()->whereHas('city', function ($query) use ($value) {
                            $query->where('id', $value);
                        })->first();
                        
                        if ($fromStation && $toStation && $fromStation->order >= $toStation->order) {
                            $fail('The destination city must come after the starting city in the trip route.');
                        }
                    }
                }
            ],
        ];
    }
}