<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationsController extends Controller
{
    /**
     * Display a reservation of the rereservation.
     */
    public function index($listing_id)
    {
        $listing = Listing::find($listing_id);
        $reservations = $listing->reservations()->latest()->get();

        return view('reservations.index', compact('listing', 'reservations'));
    }

    /**
     * Show the form for creating a new rereservation.
     */
    public function create($listing_id)
    {
        $listing = Listing::find($listing_id);

        return view('reservations.create', ['listing' => $listing]);
    }

    /**
     * Store a newly created rereservation in storage.
     */
    public function store(Request $request, $listing_id)
    {
        $listing = Listing::find($listing_id);

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'started_at' => 'required|date',
            'ended_at' => 'required|date',
        ]);

        $reservation = $listing->reservations()->create($validatedData);

        if ($request->wantsJson()) {
            return response()->json([
                'title' => $reservation->name,
                'description' => $reservation->description,
                'start' => $reservation->started_at,
                'end' => $reservation->ended_at,
            ]);
        } else {
            return redirect()->route('listings.reservations.index', $listing_id)
                    ->with('success', 'Reservation créée avec succès');
        }
    }

    /**
     * Display the specified rereservation.
     */
    public function show($id)
    {
        $reservation = Reservation::find($id);

        return view('reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified rereservation.
     */
    public function edit($listing_id, $id)
    {
        $listing = Listing::find($listing_id);
        $reservation = $listing->reservations()->find($id);

        return view('reservations.edit', compact('listing', 'reservation'));
    }

    /**
     * Update the specified rereservation in storage.
     */
    public function update(Request $request, $listing_id, $id)
    {
        $listing = Listing::find($listing_id);

        $validatedData = $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'started_at' => 'required|date',
            'ended_at' => 'required|date',
        ]);

        $reservation = $listing->reservations()->find($id);

        $reservation->update($validatedData);

        return redirect()->route('listings.reservations.index', $listing_id)
            ->with('success', 'Reservation mise à jour avec succès.');
    }

    /**
     * Remove the specified rereservation from storage.
     */
    public function destroy($listing_id, $id)
    {
        $listing = Listing::find($listing_id);

        $reservation = $listing->reservations()->find($id);

        $reservation->delete();

        return redirect()->route('listings.reservations.index', $listing_id)
            ->with('success', ' Reservation surpprimée avec succès');
    }
}
