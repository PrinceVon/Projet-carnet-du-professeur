<!DOCTYPE html>
<html>
<head>
    <title>Présence des étudiants</title>
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
    </style>
</head>
<body>
    <h1><center>Présence pour l'événement : {{ $evenement->titre }} du {{ $evenement->date }} à l' institution : {{ $evenement->institution }}</center></h1>
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
</body>
</html>
