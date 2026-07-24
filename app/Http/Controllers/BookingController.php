<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'check_in' => 'required|date|after_or_equal:today',
            'check_out' => 'required|date|after:check_in',
            'guests' => 'required|integer|min:1',
        ]);

        $property = Property::findOrFail($validated['property_id']);

        $nights = Carbon::parse($validated['check_in'])->diffInDays($validated['check_out']);

        $conflict = Booking::where('property_id', $property->id)
            ->where('status', '!=', 'cancelled')
            ->where(function ($q) use ($validated) {
                $q->whereBetween('check_in', [$validated['check_in'], $validated['check_out']])
                  ->orWhereBetween('check_out', [$validated['check_in'], $validated['check_out']])
                  ->orWhere(function ($q2) use ($validated) {
                      $q2->where('check_in', '<=', $validated['check_in'])
                         ->where('check_out', '>=', $validated['check_out']);
                  });
            })
            ->exists();

        if ($conflict) {
            return back()->withErrors(['check_in' => 'Ces dates ne sont pas disponibles.'])
                         ->withInput();
        }

        $booking = Booking::create([
            'property_id' => $property->id,
            'user_id' => auth()->id(),
            'check_in' => $validated['check_in'],
            'check_out' => $validated['check_out'],
            'guests' => $validated['guests'],
            'total_price' => $nights * $property->price_per_night,
            'status' => 'pending',
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Réservation envoyée ! En attente de confirmation.');
    }

    public function show(Booking $booking)
    {
        if ($booking->user_id !== auth()->id() && $booking->property->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['property.images', 'user', 'payments', 'review']);

        return view('bookings.show', compact('booking'));
    }

    public function index()
    {
        $bookings = Booking::with(['property.images'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function cancel(Booking $booking)
    {
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return back()->withErrors(['status' => 'Impossible d\'annuler cette réservation.']);
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'Réservation annulée.');
    }

    public function confirm(Booking $booking)
    {
        if ($booking->property->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->update(['status' => 'confirmed']);

        return back()->with('success', 'Réservation confirmée.');
    }

    public function complete(Booking $booking)
    {
        if ($booking->property->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->update(['status' => 'completed']);

        return back()->with('success', 'Séjour marqué comme terminé.');
    }
}
