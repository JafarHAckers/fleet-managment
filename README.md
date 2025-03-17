# Fleet Management System (Bus Booking System)

A Laravel-based bus booking system that allows users to book seats on trips between different cities in Egypt.

## Overview

This project implements a fleet management system (bus-booking system) using Laravel Framework. It enables users to book seats on predefined trips between Egyptian cities, with support for partial trip bookings.

## Features

- **User Features**:
  - Browse available trips between Egyptian cities
  - Select origin and destination cities
  - View available seats for chosen routes
  - Book seats
  - View personal booking history

- **Admin Features**:
  - Dashboard with system overview
  - Manage buses and view seat availability
  - View details of trips and their bookings
  - Monitor all bookings in the system

## System Requirements

- PHP 8.1+
- Composer
- MySQL 5.7+
- Laravel 10.x

## Installation

### Standard Installation

1. Clone the repository:
```
git clone https://github.com/your-username/fleet-management.git
cd fleet-management
```

2. Install dependencies:
```
composer install
```

3. Create a copy of the environment file:
```
cp .env.example .env
```

4. Generate application key:
```
php artisan key:generate
```

5. Configure your database in the `.env` file:
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fleet_management
DB_USERNAME=root
DB_PASSWORD=your_password
```

6. Run migrations and seed the database:
```
php artisan migrate
php artisan db:seed
```

7. Start the development server:
```
php artisan serve
```

8. Visit http://127.0.0.1:8000 in your browser

## Accessing the Application

### User Access
- Register a new account or use an existing user
- Login credentials for test user:
  - Email: user1@example.com
  - Password: password

### Admin Access
- Login credentials for admin:
  - Email: admin@example.com
  - Password: password
- Admin dashboard is available at: http://127.0.0.1:8000/admin/dashboard

## Project Structure

### Database Design
- **Cities**: Egyptian cities that serve as stations
- **Buses**: Each bus has 12 seats available for booking
- **Seats**: Unique seats within each bus
- **Trips**: Predefined routes between two stations, crossing over in-between stations
- **Trip Stations**: The order of stations within a trip
- **Bookings**: Records of seat reservations by users

### API Endpoints

The system provides two main API endpoints:
1. **Get Available Seats**:
   - Users can retrieve available seats by providing start and end stations

2. **Book a Seat**:
   - Users can book an available seat for their chosen route

## Implementation Details

- Users can book a seat for any segment of a trip (e.g., Cairo to AlFayyum, Cairo to AlMinya, etc.)
- If a bus is full for a particular segment, seats become unavailable for that segment
- The system tracks all bookings and ensures no double-booking occurs

## License

This project is open-source and available under the MIT License.