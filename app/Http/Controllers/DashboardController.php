<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Booking;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $myProperties = $user->properties()->withCount('bookings')->latest()->get();

        // Réservations reçues sur mes annonces (rôles hôte)
        $receivedBookings = Booking::with(['property', 'user'])
            ->whereHas('property', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->latest()
            ->get();

        // Réservations que j'ai faites (rôles locataire)
        $myBookings = $user->bookings()->with('property.images')->latest()->get();

        $pendingBookings = Booking::whereHas('property', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->where('status', 'pending')->count();

        $favorites = $user->favorites()->with(['property.images', 'property.reviews'])->get();

        return view('dashboard', compact('myProperties', 'receivedBookings', 'myBookings', 'pendingBookings', 'favorites'));
    }
}
