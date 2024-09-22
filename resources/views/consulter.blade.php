@extends('layouts.user_type.auth')

@section('content')
    <div class="card">
        <h1><center>Cours nommé : <b>{{ $event->titre }}</b> fait à l'institution : <b>{{ $event->institution }}</b> en filière : {{ $event->filiere}} dans la salle {{ $event->salle }} le <b>{{ $event->date }}</b></center></h1>
        <br/>
        <br/>

        <h3>Heure de début : {{ $event->heure_debut }}</h3>
        <h3>Heure de fin : {{ $event->heure_fin}}</h3>
        <h3>Heure d'arrivée : {{ $event->heure_arrivee}}</h3>
        <h3>Heure de départ : {{ $event->heure_depart}}</h3>
        <br/>
        <br/>

        <h3><u>Liste de présence des étudiants</u></h3>
        <br/>

        <table>
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Matricule</th>
                    <th>Nom</th>
                    <th>Prénom(s)</th>
                    <th>Sexe</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                @foreach($presences as $index => $presence)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $presence->etudiant->matricule }}</td>
                        <td>{{ $presence->etudiant->nom }}</td>
                        <td>{{ $presence->etudiant->prenom }}</td>
                        <td>{{ $presence->etudiant->sexe }}</td>
                        <td>{{ $presence->status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br/>

        @if($notes->isNotEmpty())
            @if($notes->where('categorie', 'plus')->first())
                <h3><u>Notes sur ceux qui ont eu des plus :</u></h3>
                <ul>
                    @foreach(explode("\n", $notes->where('categorie', 'plus')->first()->contenu) as $ligne)
                        @if(trim($ligne) !== '')
                            <li><h4>{!! $ligne !!}</h4></li>
                        @endif
                    @endforeach
                </ul>
            @endif

            @if($notes->where('categorie', 'moins')->first())
                <h3><u>Notes sur ceux qui ont eu des moins :</u></h3>
                <ul>
                    @foreach(explode("\n", $notes->where('categorie', 'moins')->first()->contenu) as $ligne)
                        @if(trim($ligne) !== '')
                            <li><h4>{!! $ligne !!}</h4></li>
                        @endif
                    @endforeach
                </ul>
            @endif

            @if($notes->where('categorie', 'devoir_maison')->first())
                <h3><u>Notes sur les devoirs de maison donnés :</u></h3>
                <ul>
                    @foreach(explode("\n", $notes->where('categorie', 'devoir_maison')->first()->contenu) as $ligne)
                        @if(trim($ligne) !== '')
                            <li><h4>{!! $ligne !!}</h4></li>
                        @endif
                    @endforeach
                </ul>
            @endif

        @endif
        <br/>

        @if($commentaire->isNotEmpty())
            <h3><u>Commentaire :</u></h3>
            <h4>{!! nl2br(e($commentaire->first()->contenu)) !!}
            </h4>
        @endif

        <br/>
        <br/>
    </div>
@endsection
@push('consulter')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table, th, td {
            border: 1px solid black;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        body {
                padding-bottom: 100px; /* Espace pour le footer */
            }
            .footer {
                position: fixed;
                bottom: 0;
                width: 100%;
                background-color: #f1f1f1;
                text-align: center;
                padding: 10px;
            }
</style>
@endpush
