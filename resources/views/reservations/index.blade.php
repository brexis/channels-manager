@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex">
                    Les reservations de l'appartement : <strong>{{ $listing->name }}</strong>
                    <a href="{{ route('listings.reservations.create', $listing->id) }}" class="ms-auto">
                        Ajouter une reservation
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
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($reservations as $reservation)
                            <tr>
                                <td>#{{ $reservation->id }}</td>
                                <td>{{ $reservation->name }}</td>
                                <td>{{ $reservation->started_at }}</td>
                                <td>{{ $reservation->ended_at }}</td>
                                <td>
                                    <a href="{{ route('listings.reservations.edit', [$listing->id, $reservation->id]) }}">Mofidier</a> |
                                    <form action="{{ route('listings.reservations.destroy', [$listing->id, $reservation->id]) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-link text-danger" onclick="return confirm('Are you sure?')" >Supprimer</button>
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
