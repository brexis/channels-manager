<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Listing;
use ICal\ICal;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

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

        $reservations = $selected_listing->reservations->map(function ($r) {
            return [
                'title' => $r->name,
                'start' => $r->started_at,
                'end' => $r->ended_at,
            ];
        });

        $urls = $selected_listing->sources->pluck('url');
        $source_events = [];

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

        return view('home', compact('listings', 'selected_listing', 'source_events', 'reservations'));
    }
}
