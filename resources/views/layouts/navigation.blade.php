<!-- Navigation Links -->
<div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
    <x-nav-link :href="route('trips.index')" :active="request()->routeIs('trips.index')">
        {{ __('Trips') }}
    </x-nav-link>
    <x-nav-link :href="route('bookings.index')" :active="request()->routeIs('bookings.*')">
        {{ __('My Bookings') }}
    </x-nav-link>
</div>