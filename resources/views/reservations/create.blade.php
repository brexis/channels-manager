@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex">
                    Ajouter une reservation à l'appartement : <strong>{{$listing->name}}</strong>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('listings.reservations.store', $listing->id) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom de la reservation</label>

                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="started_at" class="form-label">Date d'arrivée</label>

                                <input id="started_at" type="date" class="form-control @error('started_at') is-invalid @enderror" name="started_at" value="{{ old('started_at') }}" required autocomplete="started_at">

                                @error('started_at')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="ended_at" class="form-label">Date de sortie</label>

                                <input id="ended_at" type="date" class="form-control @error('ended_at') is-invalid @enderror" name="ended_at" value="{{ old('ended_at') }}" required autocomplete="ended_at">

                                @error('ended_at')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-0">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
