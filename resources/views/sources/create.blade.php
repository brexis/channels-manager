@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex">
                    Ajouter une source à l'appartement: <strong>{{$listing->name}}</strong>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('listings.sources.store', $listing->id) }}">
                        @csrf

                        <div class="mb-3">
                            <label for="url" class="form-label">URL de la source</label>

                            <input id="url" type="url" class="form-control @error('url') is-invalid @enderror" name="url" value="{{ old('url') }}" required autocomplete="url" autofocus>

                            @error('url')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
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
