<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Property;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'property_id' => 'required|exists:properties,id',
            'booking_id' => 'required|exists:bookings,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $booking = \App\Models\Booking::findOrFail($validated['booking_id']);

        if ($booking->user_id !== auth()->id() || $booking->property_id !== $validated['property_id']) {
            abort(403);
        }

        if ($booking->status !== 'completed') {
            return redirect()->route('bookings.show', $booking->id)
                ->withErrors(['rating' => 'Vous ne pouvez laisser un avis qu\'après un séjour.']);
        }

        $existing = Review::where('user_id', auth()->id())
            ->where('booking_id', $validated['booking_id'])
            ->first();

        if ($existing) {
            return redirect()->route('bookings.show', $booking->id)
                ->withErrors(['rating' => 'Vous avez déjà laissé un avis pour ce séjour.']);
        }

        Review::create([
            'property_id' => $validated['property_id'],
            'user_id' => auth()->id(),
            'booking_id' => $validated['booking_id'],
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return redirect()->route('bookings.show', $validated['booking_id'])
            ->with('success', 'Merci pour votre avis !');
    }

    public function destroy(Review $review)
    {
        if ($review->user_id !== auth()->id()) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Avis supprimé.');
    }
}
