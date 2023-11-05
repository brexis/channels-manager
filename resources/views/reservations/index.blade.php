@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex">
                    Les reservations de l'appartement : <strong>{{ $listing->name }}</strong>
                    <a href="{{ route('listings.reservations.create', $listing->id) }}" class="ms-auto btn btn-primary">
                        <i class="bi bi-bookmark-plus"></i> Ajouter une réservation
                    </a>
                </div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nom</th>
                                <th>Date d'arrivée</th>
                                <th>Date de départ</th>
                                <th  style="width: 8rem">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($reservations as $reservation)
                            <tr>
                                <td>#{{ $reservation->id }}</td>
                                <td>{{ $reservation->name }} ({{ $reservation->nights }} Nuits)</td>
                                <td>{{ $reservation->started_at }}</td>
                                <td>{{ $reservation->ended_at }}</td>
                                <td>
                                    <a title="Modifier" href="{{ route('listings.reservations.edit', [$listing->id, $reservation->id]) }}" class="btn btn-sm btn-secondary">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <form action="{{ route('listings.reservations.destroy', [$listing->id, $reservation->id]) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')" >
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
