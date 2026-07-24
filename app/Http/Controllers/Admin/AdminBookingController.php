<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['property', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(15);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['property.images', 'user', 'payments', 'review']);

        return view('admin.bookings.show', compact('booking'));
    }

    public function updateStatus(Booking $booking, $status)
    {
        if (!in_array($status, ['pending', 'confirmed', 'cancelled', 'completed'])) {
            abort(400);
        }

        $booking->update(['status' => $status]);

        return back()->with('success', 'Statut mis à jour : ' . $status . '.');
    }
}
