<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use Spatie\IcalendarGenerator\Components\Calendar;
use Spatie\IcalendarGenerator\Components\Event;
use ICal\ICal;

class ListingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $listings = Listing::all();

        return view('listings.index', ['listings' => $listings]);
    }

    public function ical(Request $request, $id)
    {
        $listing = Listing::find($id);

        $calendar = Calendar::create('Channel Manager');
        $included_sources = explode(',', $request->input('without'));

        Listing::find($id)->reservations->each(function ($r) use($calendar) {
            $calendar->event(
                Event::create($r->name)
                    ->description($r->description)
                    ->startsAt($r->started_at->toDateTime())
                    ->endsAt($r->ended_at->toDateTime())
            );
        });

        $sources_url = $listing->sources()->whereNotIn('id', $included_sources)->pluck('url');
        try {
            $ical = new ICal($sources_url->toArray());
            foreach($ical->events() as $event) {
                $calendar->event(
                    Event::create($event->summary)
                        ->description($event->description ?? '')
                        ->startsAt(new \DateTime($event->dtstart))
                        ->endsAt(new \DateTime($event->dtend))
                );
            }
        } catch (\Exception $e) {
        }

        return response($calendar->get())
            ->header('Content-Type', 'text/calendar; charset=utf-8');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('listings.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255'
        ]);

        Listing::create($validatedData);

        return redirect()->route('listings.index')
                ->with('success', 'Appartement créé avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $listing = Listing::find($id);

        return view('listings.show', compact('listing'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $listing = Listing::find($id);
        return view('listings.edit', compact('listing'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:255'
        ]);

        $listing = Listing::find($id);

        $listing->update($validatedData);

        return redirect()->route('listings.index')
            ->with('success', 'Listing mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $listing = Listing::find($id);

        $listing->delete();

        return redirect()->route('listings.index')
            ->with('success', ' deleted successfully');
    }
}
