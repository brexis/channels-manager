@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex">
                    Mes appartements
                    <a href="{{ route('listings.create') }}" class="ms-auto btn btn-primary">
                        <i class="bi bi-house-add"></i> Ajouter
                    </a>
                </div>

                <div class="card-body">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Nom de l'appartement</th>
                                <th  style="width: 15rem">Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($listings as $listing)
                            <tr>
                                <td>#{{ $listing->id }}</td>
                                <td>{{ $listing->name }}</td>
                                <td>
                                    <a title="Modifier" href="{{ route('listings.edit', $listing->id) }}" class="btn btn-sm btn-secondary">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <a title="Autre sources" href="{{ route('listings.sources.index', $listing->id) }}" class="btn btn-sm btn-secondary">
                                        <i class="bi bi-link-45deg"></i>
                                    </a>
                                    <a title="Mes réservations" href="{{ route('listings.reservations.index', $listing->id) }}" class="btn btn-sm btn-secondary">
                                        <i class="bi bi-bookmarks"></i>
                                    </a>
                                    <a href="#" title="Exporter le calendrier" data-listing-id="{{ $listing->id }}" data-bs-toggle="modal" data-bs-target="#export-listing" class="btn btn-sm btn-secondary">
                                        <i class="bi bi-calendar2-week"></i>
                                    </a>
                                    <form action="{{ route('listings.destroy', $listing->id) }}" method="post" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button title="Supprimer" type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')" >
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

<div class="modal fade" id="export-listing" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel">Exporter le calendrier des reservations</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form>
            <div class="mb-3">
                <label for="listing-sources" class="form-label">Exporter sans la source</label>
                <select class="form-select" id="listing-sources" name="listing-sources"></select>
            </div>
            <div class="input-group mb-3">
                <input type="text" readonly id="listing-url-input" class="form-control" placeholder="" aria-label="Example text with button addon" aria-describedby="button-addon1">
                <button class="btn btn-outline-primary btn-copy" type="button" data-clipboard-target="#listing-url-input">Copier</button>
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
