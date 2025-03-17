<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\TripController;
use App\Http\Controllers\Web\AuthController;
use App\Models\Bus;
use App\Models\Trip;
use App\Models\Booking;
use App\Models\User;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect()->route('trips.index');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/trips', [TripController::class, 'index'])->name('trips.index');
    Route::get('/trips/{trip}', [TripController::class, 'show'])->name('trips.show');
    Route::post('/trips/{trip}/seats', [TripController::class, 'selectSeats'])->name('trips.select-seats');
    Route::post('/book-seat', [TripController::class, 'bookSeat'])->name('book.seat');
    Route::get('/my-bookings', [TripController::class, 'myBookings'])->name('bookings.index');
    Route::get('/bookings/{booking}', [TripController::class, 'showBooking'])->name('bookings.show');
});

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard route with simple HTML output for debugging
    Route::get('/dashboard', function() {
        // Check if the user is an admin
        if (auth()->user()->email !== 'admin@example.com') {
            return redirect('/')->with('error', 'Unauthorized access. Admin permissions required.');
        }
        
        // Display simple HTML dashboard with data
        $totalBuses = \App\Models\Bus::count();
        $totalTrips = \App\Models\Trip::count();
        $totalBookings = \App\Models\Booking::count();
        $totalUsers = \App\Models\User::count();
        
        // Get all buses with their seats
        $buses = \App\Models\Bus::with('seats')->get();
        
        $busesHtml = '';
        foreach ($buses as $bus) {
            $busesHtml .= "<div style='margin-bottom: 20px; padding: 10px; border: 1px solid #ccc;'>";
            $busesHtml .= "<h3>{$bus->name}</h3>";
            $busesHtml .= "<p>Total Seats: {$bus->seats->count()}</p>";
            
            // Display seats
            $busesHtml .= "<div style='display: flex; flex-wrap: wrap; gap: 10px; margin-top: 10px;'>";
            foreach ($bus->seats as $seat) {
                // Get bookings for this seat
                $bookings = \App\Models\Booking::where('seat_id', $seat->id)->with(['user', 'fromStation.city', 'toStation.city'])->get();
                
                if ($bookings->isEmpty()) {
                    $busesHtml .= "<div style='padding: 5px; background-color: #d1fae5; border-radius: 4px;'>{$seat->seat_number} - Available</div>";
                } else {
                    $busesHtml .= "<div style='padding: 5px; background-color: #fee2e2; border-radius: 4px;'>";
                    $busesHtml .= "{$seat->seat_number} - Booked by: ";
                    foreach ($bookings as $booking) {
                        $busesHtml .= "<p>{$booking->user->name} ({$booking->fromStation->city->name} to {$booking->toStation->city->name})</p>";
                    }
                    $busesHtml .= "</div>";
                }
            }
            $busesHtml .= "</div>";
            $busesHtml .= "</div>";
        }
        
        return "
            <h1 style='margin-bottom: 20px;'>Admin Dashboard</h1>
            
            <div style='display: flex; gap: 20px; margin-bottom: 20px;'>
                <div style='padding: 10px; background-color: #dbeafe; border-radius: 4px;'>
                    <h3>{$totalBuses}</h3>
                    <p>Buses</p>
                </div>
                <div style='padding: 10px; background-color: #d1fae5; border-radius: 4px;'>
                    <h3>{$totalTrips}</h3>
                    <p>Trips</p>
                </div>
                <div style='padding: 10px; background-color: #fef3c7; border-radius: 4px;'>
                    <h3>{$totalBookings}</h3>
                    <p>Bookings</p>
                </div>
                <div style='padding: 10px; background-color: #ede9fe; border-radius: 4px;'>
                    <h3>{$totalUsers}</h3>
                    <p>Users</p>
                </div>
            </div>
            
            <div style='margin-bottom: 20px;'>
                <a href='/admin/buses' style='padding: 8px 16px; background-color: #3b82f6; color: white; text-decoration: none; border-radius: 4px; margin-right: 10px;'>Manage Buses</a>
                <a href='/admin/trips' style='padding: 8px 16px; background-color: #10b981; color: white; text-decoration: none; border-radius: 4px; margin-right: 10px;'>Manage Trips</a>
                <a href='/admin/bookings' style='padding: 8px 16px; background-color: #f59e0b; color: white; text-decoration: none; border-radius: 4px;'>View Bookings</a>
            </div>
            
            <h2 style='margin-bottom: 15px;'>Bus and Seat Information</h2>
            {$busesHtml}
        ";
    })->name('dashboard');
    
    // Buses routes
    Route::get('/buses', function() {
        if (auth()->user()->email !== 'admin@example.com') {
            return redirect('/')->with('error', 'Unauthorized access');
        }
        $buses = Bus::withCount('seats')->get();
        
        $html = "<h1>Manage Buses</h1>";
        $html .= "<p><a href='/admin/dashboard'>Back to Dashboard</a></p>";
        $html .= "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        $html .= "<tr><th>ID</th><th>Name</th><th>Seats</th><th>Actions</th></tr>";
        
        foreach ($buses as $bus) {
            $html .= "<tr>";
            $html .= "<td>{$bus->id}</td>";
            $html .= "<td>{$bus->name}</td>";
            $html .= "<td>{$bus->seats_count}</td>";
            $html .= "<td><a href='/admin/buses/{$bus->id}'>View Details</a></td>";
            $html .= "</tr>";
        }
        
        $html .= "</table>";
        
        return $html;
    })->name('buses.index');
    
    Route::get('/buses/{bus}', function($bus) {
        if (auth()->user()->email !== 'admin@example.com') {
            return redirect('/')->with('error', 'Unauthorized access');
        }
        $bus = Bus::with(['seats', 'trips'])->findOrFail($bus);
        
        $html = "<h1>Bus Details: {$bus->name}</h1>";
        $html .= "<p><a href='/admin/buses'>Back to Buses</a></p>";
        
        $html .= "<h2>Seats</h2>";
        $html .= "<div style='display: flex; flex-wrap: wrap; gap: 10px;'>";
        
        foreach ($bus->seats as $seat) {
            $html .= "<div style='padding: 10px; background-color: #f0f0f0; border-radius: 4px;'>{$seat->seat_number}</div>";
        }
        
        $html .= "</div>";
        
        $html .= "<h2>Trips Using This Bus</h2>";
        if ($bus->trips->isEmpty()) {
            $html .= "<p>No trips are currently using this bus.</p>";
        } else {
            $html .= "<ul>";
            foreach ($bus->trips as $trip) {
                $html .= "<li><a href='/admin/trips/{$trip->id}'>{$trip->name}</a></li>";
            }
            $html .= "</ul>";
        }
        
        return $html;
    })->name('buses.show');
    
    // Trips routes
    Route::get('/trips', function() {
        if (auth()->user()->email !== 'admin@example.com') {
            return redirect('/')->with('error', 'Unauthorized access');
        }
        $trips = Trip::with(['bus'])->get();
        
        $html = "<h1>Manage Trips</h1>";
        $html .= "<p><a href='/admin/dashboard'>Back to Dashboard</a></p>";
        $html .= "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        $html .= "<tr><th>ID</th><th>Name</th><th>Bus</th><th>Actions</th></tr>";
        
        foreach ($trips as $trip) {
            $html .= "<tr>";
            $html .= "<td>{$trip->id}</td>";
            $html .= "<td>{$trip->name}</td>";
            $html .= "<td>{$trip->bus->name}</td>";
            $html .= "<td><a href='/admin/trips/{$trip->id}'>View Details</a></td>";
            $html .= "</tr>";
        }
        
        $html .= "</table>";
        
        return $html;
    })->name('trips.index');
    
    Route::get('/trips/{trip}', function($trip) {
        if (auth()->user()->email !== 'admin@example.com') {
            return redirect('/')->with('error', 'Unauthorized access');
        }
        
        $trip = Trip::with(['bus', 'tripStations.city', 'bookings.user', 'bookings.seat', 'bookings.fromStation.city', 'bookings.toStation.city'])->findOrFail($trip);
        $seats = $trip->bus->seats;
        $bookingsBySeat = $trip->bookings->groupBy('seat_id');
        
        $html = "<h1>Trip Details: {$trip->name}</h1>";
        $html .= "<p><a href='/admin/trips'>Back to Trips</a></p>";
        
        $html .= "<p><strong>Bus:</strong> {$trip->bus->name}</p>";
        
        $html .= "<h2>Route</h2>";
        $html .= "<div style='display: flex; align-items: center;'>";
        foreach ($trip->tripStations as $index => $station) {
            $html .= "<div style='background-color: #3b82f6; color: white; padding: 5px 10px; border-radius: 50%;'>{$station->order}</div>";
            $html .= "<span style='margin: 0 5px;'>{$station->city->name}</span>";
            
            if ($index < $trip->tripStations->count() - 1) {
                $html .= "<span style='margin: 0 5px;'>→</span>";
            }
        }
        $html .= "</div>";
        
        $html .= "<h2>Seat Bookings</h2>";
        $html .= "<div style='display: flex; flex-wrap: wrap; gap: 10px;'>";
        
        foreach ($seats as $seat) {
            $hasBookings = isset($bookingsBySeat[$seat->id]);
            $bgColor = $hasBookings ? '#fee2e2' : '#d1fae5';
            $html .= "<div style='padding: 10px; background-color: {$bgColor}; border-radius: 4px; width: 200px;'>";
            $html .= "<strong>Seat {$seat->seat_number}</strong>";
            
            if ($hasBookings) {
                $html .= "<div>Status: Booked</div>";
                $html .= "<ul>";
                foreach ($bookingsBySeat[$seat->id] as $booking) {
                    $html .= "<li>";
                    $html .= "<strong>User:</strong> {$booking->user->name}<br>";
                    $html .= "<strong>From:</strong> {$booking->fromStation->city->name}<br>";
                    $html .= "<strong>To:</strong> {$booking->toStation->city->name}";
                    $html .= "</li>";
                }
                $html .= "</ul>";
            } else {
                $html .= "<div>Status: Available</div>";
            }
            
            $html .= "</div>";
        }
        
        $html .= "</div>";
        
        return $html;
    })->name('trips.show');
    
    // Bookings route
    Route::get('/bookings', function() {
        if (auth()->user()->email !== 'admin@example.com') {
            return redirect('/')->with('error', 'Unauthorized access');
        }
        
        $bookings = Booking::with(['trip', 'seat', 'user', 'fromStation.city', 'toStation.city'])->get();
        
        $html = "<h1>All Bookings</h1>";
        $html .= "<p><a href='/admin/dashboard'>Back to Dashboard</a></p>";
        $html .= "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
        $html .= "<tr><th>ID</th><th>User</th><th>Trip</th><th>Seat</th><th>From</th><th>To</th><th>Booked At</th></tr>";
        
        foreach ($bookings as $booking) {
            $html .= "<tr>";
            $html .= "<td>{$booking->id}</td>";
            $html .= "<td>{$booking->user->name}</td>";
            $html .= "<td>{$booking->trip->name}</td>";
            $html .= "<td>{$booking->seat->seat_number}</td>";
            $html .= "<td>{$booking->fromStation->city->name}</td>";
            $html .= "<td>{$booking->toStation->city->name}</td>";
            $html .= "<td>{$booking->created_at}</td>";
            $html .= "</tr>";
        }
        
        $html .= "</table>";
        
        return $html;
    })->name('bookings.index');
});