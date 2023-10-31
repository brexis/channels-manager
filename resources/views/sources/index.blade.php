@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex">
                    Les sources de l'appartement : <strong>{{ $listing->name }}</strong>
                    <a href="{{ route('listings.sources.create', $listing->id) }}" class="ms-auto">
                        Ajouter une source
                    </a>
                </div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Url de la source</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($sources as $source)
                            <tr>
                                <td>#{{ $source->id }}</td>
                                <td>{{ $source->url }}</td>
                                <td>
                                    <a href="{{ route('listings.sources.edit', [$listing->id, $source->id]) }}">Mofidier</a> |
                                    <form action="{{ route('listings.sources.destroy', [$listing->id, $source->id]) }}" method="post">
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
