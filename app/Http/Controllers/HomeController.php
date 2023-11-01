<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use ICal\ICal;

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
                    'title' => $r->name,
                    'start' => $r->started_at,
                    'end' => $r->ended_at,
                ];
            });
        }

        $source_events = [];

        if ($selected_listing) {
            $urls = $selected_listing->sources->pluck('url');
            try {
                $ical = new ICal($urls->toArray());
                foreach($ical->events() as $event) {
                    $source_events[] = [
                        'title' => $event->summary,
                        'start' => date("Y-m-d", strtotime($event->dtstart)),
                        'end' => date("Y-m-d", strtotime($event->dtend))
                    ];
                }
            } catch (\Exception $e) {
            }
        }

        return view('home', compact('listings', 'selected_listing', 'source_events', 'reservations'));
    }
}
