@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header d-flex">
                    Ajouter un appartement
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('listings.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom de l'appartement</label>

                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="nigth_price" class="form-label">Prix par nuitée</label>

                                <input id="nigth_price" type="number" class="form-control @error('nigth_price') is-invalid @enderror" name="nigth_price" value="{{ old('nigth_price') }}" required autocomplete="nigth_price">

                                @error('nigth_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="week_price" class="form-label">Prix par semaine</label>

                                <input id="week_price" type="number" class="form-control @error('week_price') is-invalid @enderror" name="week_price" value="{{ old('week_price') }}" required autocomplete="week_price">

                                @error('week_price')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="month_price" class="form-label">Prix par mois</label>

                            <input id="month_price" type="text" class="form-control @error('month_price') is-invalid @enderror" name="month_price" value="{{ old('month_price') }}" required autocomplete="month_price">

                            @error('month_price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>

                            <textarea id="description" type="text" class="form-control @error('description') is-invalid @enderror" name="description" required autocomplete="description">{{ old('description') }}</textarea>

                            @error('description')
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
