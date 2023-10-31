<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Source;
use Illuminate\Http\Request;

class SourcesController extends Controller
{
    /**
     * Display a source of the resource.
     */
    public function index(Request $request, $listing_id)
    {
        $listing = Listing::find($listing_id);
        $sources = $listing->sources()->latest()->get();

        if ($request->wantsJson()) {
            return response()->json($sources->toJson());
        } else {
            return view('sources.index', compact('listing', 'sources'));
        }

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create($listing_id)
    {
        $listing = Listing::find($listing_id);

        return view('sources.create', ['listing' => $listing]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $listing_id)
    {
        $listing = Listing::find($listing_id);

        $validatedData = $request->validate([
            'url' => 'required|url'
        ]);

        $listing->sources()->create($validatedData);

        return redirect()->route('listings.sources.index', $listing_id)
                ->with('success', 'Source créée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $source = Source::find($id);

        return view('sources.show', compact('source'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($listing_id, $id)
    {
        $listing = Listing::find($listing_id);
        $source = $listing->sources()->find($id);

        return view('sources.edit', compact('listing', 'source'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $listing_id, $id)
    {
        $listing = Listing::find($listing_id);

        $validatedData = $request->validate([
            'url' => 'required|url'
        ]);

        $source = $listing->sources()->find($id);

        $source->update($validatedData);

        return redirect()->route('listings.sources.index', $listing_id)
            ->with('success', 'Source mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($listing_id, $id)
    {
        $listing = Listing::find($listing_id);

        $source = $listing->sources()->find($id);

        $source->delete();

        return redirect()->route('listings.sources.index', $listing_id)
            ->with('success', ' Source surpprimée avec succès');
    }
}
