<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use ICal\ICal;
use Carbon\Carbon;
use Auth;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $listings = Listing::all();

        if ($id = $request->input('selected')) {
            $selected_listing = Listing::find($id);
        } else {
            $selected_listing = $listings->first();
        }

        $reservations = [];
        if ($selected_listing) {
            $reservations = $selected_listing->reservations->map(function ($r) {
                return [
                    'reservationId' => $r->id,
                    'title' => Auth::check() ? $r->name : 'Occupé',
                    'description' => Auth::check() ? $r->description : 'Appartement non disponible',
                    'start' => $r->started_at,
                    'end' => $r->ended_at,
                    'nights' => $r->nights,
                ];
            });
        }

        $source_events = [];

        if ($selected_listing) {
            $urls = $selected_listing->sources->pluck('url');
            try {
                $ical = new ICal($urls->toArray());
                foreach($ical->events() as $event) {
                    $start = Carbon::parse($event->dtstart);
                    $end = Carbon::parse($event->dtend);

                    // Escape Airbnb today disable
                    if ($event->summary == 'Airbnb (Not available)' && $start->diffInDays($end) == 1) {
                        continue;
                    }

                    $start->setHour(12);
                    $end->setHour(12);

                    $source_events[] = [
                        'title' => Auth::check() ? $event->summary : 'Occupé',
                        'description' => Auth::check() ? $event->description : 'Appartement non disponible',
                        'start' => $start->toDateTimeString(),
                        'end' => $end->toDateTimeString(),
                        'nights' => $start->startOfDay()->diffInDays($end),
                    ];
                }
            } catch (\Exception $e) {
            }
        }

        return view('home', compact('listings', 'selected_listing', 'source_events', 'reservations'));
    }
}
