@extends('layouts.user_type.auth')

@section('content')
<div class="container">
    <h1>Recherche d'Événements</h1>

    <form action="{{ route('recherche.results') }}" method="GET">
        @csrf
        <div class="form-group">
            <label for="institution">Institution</label>
            <select id="institution" name="institution" class="form-control">
                <option value="">Sélectionnez une institution</option>
                @foreach($institutions as $institution)
                    <option value="{{ $institution->id }}" >
                        {{ $institution->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="filiere">Filière</label>
            <select id="filiere" name="filiere" class="form-control">
                <option value="">Sélectionnez une filière</option>
                @foreach($filieres as $id => $nom)
                    <option value="{{ $id }}" {{ request('filiere') == $id ? 'selected' : '' }}>
                        {{ $nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="unite">Unité d'Enseignement</label>
            <select id="unite" name="unite" class="form-control">
                <option value="">Sélectionnez une unité d'enseignement</option>
                @foreach($unites as $id => $nom)
                    <option value="{{ $id }}" {{ request('unite') == $id ? 'selected' : '' }}>
                        {{ $nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label for="salle">Salle</label>
            <select id="salle" name="salle" class="form-control">
                <option value="">Sélectionnez une salle</option>
                @foreach($salles as $id => $nom)
                    <option value="{{ $id }}" {{ request('salle') == $id ? 'selected' : '' }}>
                        {{ $nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Rechercher</button>
    </form>

    @if(isset($notes))
        <h2>Résultats de la Recherche</h2>
        <ul class="list-group">
            @foreach($notes as $note)
                <li class="list-group-item">
                    <h5>Événement : {{ $note->evenement->titre }}</h5>
                    <p>Contenu : {{ $note->contenu }}</p>
                </li>
            @endforeach
        </ul>
    @else
        <h6>Pas de résultat</h6>
    @endif
</div>
@endsection
