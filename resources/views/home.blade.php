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

<div class="modal fade" id="show-reservation" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Détail de la reservation</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
            <div class="mb-3">
                <h4><em id="reservation-title"></em></h4>
            </div>
            <div class="mb-3">
                <label class="form-label">Réservé du</label>
                <kbd id="reservation-start"></kbd> Au <kbd id="reservation-end"></kbd>
            </div>
            <div class="mb-3">
                <div id="reservation-description" class="border rounded p-3" style="white-space: pre-wrap"></div>
            </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
      </div>
    </div>
  </div>
</div>
@endsection
