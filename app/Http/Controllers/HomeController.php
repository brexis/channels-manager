<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use ICal\ICal;
use Carbon\Carbon;

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
                    'title' => $r->name,
                    'description' => $r->description,
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
                    $source_events[] = [
                        'title' => $event->summary,
                        'description' => $event->description,
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
