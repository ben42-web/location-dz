<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $myProperties = $user->properties()->withCount('bookings')->latest()->get();

        $myBookings = $user->bookings()->with('property.images')->latest()->get();

        $pendingBookings = \App\Models\Booking::whereHas('property', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', 'pending')->count();

        $favorites = $user->favorites()->with(['property.images', 'property.reviews'])->get();

        return view('dashboard', compact('myProperties', 'myBookings', 'pendingBookings', 'favorites'));
    }
}
