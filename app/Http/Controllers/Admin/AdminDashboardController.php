<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Property;
use App\Models\Booking;
use App\Models\PropertyType;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'users' => User::count(),
            'hosts' => User::where('role', 'host')->count(),
            'guests' => User::where('role', 'guest')->count(),
            'properties' => Property::count(),
            'active_properties' => Property::where('is_active', true)->count(),
            'bookings' => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'total_revenue' => Booking::where('status', '!=', 'cancelled')->sum('total_price'),
            'types' => PropertyType::count(),
        ];

        $recentUsers = User::latest()->limit(5)->get();
        $recentProperties = Property::with(['user', 'images'])->latest()->limit(5)->get();
        $recentBookings = Booking::with(['property', 'user'])->latest()->limit(5)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers', 'recentProperties', 'recentBookings'));
    }
}
