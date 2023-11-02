@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <form>
                <div class="mb-3">
                    <label for="listing-switch" class="form-label">Choisissez un appartement</label>
                    <select class="form-select" id="listing-switch" name="listing-switch">
                        @foreach($listings as $listing)
                        <option value="{{ route('home', ['selected' => $listing->id]) }}" @if($selected_listing->id == $listing->id) selected @endif>{{ $listing->name }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    var source_events = @json($source_events);
    var reservations = @json($reservations);
    var listing = @json($selected_listing);
    var authenticated = {{ Auth::check() ? 'true' : 'false' }};
</script>
<div class="container" id="calendar"></div>
@endsection
