@extends('layouts.user_type.auth')

@section('content')
    <div class="card">
        @php
            function moisEnFrancais($mois) {
                $moisFr = [
                    1 => 'Janvier',
                    2 => 'Février',
                    3 => 'Mars',
                    4 => 'Avril',
                    5 => 'Mai',
                    6 => 'Juin',
                    7 => 'Juillet',
                    8 => 'Août',
                    9 => 'Septembre',
                    10 => 'Octobre',
                    11 => 'Novembre',
                    12 => 'Décembre'
                ];

                return $moisFr[$mois] ?? 'Mois inconnu';
            }

        @endphp


        <div class="container">
            <h1><center>Tableau de salaire</center></h1>
            <table border = '2' >
                <thead>
                    <tr>
                        <th style="padding: 13px;">MOIS - ANNEE</th>
                        <th style="padding: 13px;">INSTITUTION</th>
                        <th style="padding: 13px;">NOMBRE D’HEURES PLANIFIÉ</th>
                        <th style="padding: 13px;">NOMBRE D’HEURES FAIT</th>
                        <th style="padding: 13px;">NOMBRE D’HEURES RATÉ</th>
                        <th style="padding: 13px;">NOMBRE D’HEURES RESTANT À FAIRE</th>
                        <th style="padding: 13px;">TARIFICATION PAR HEURE</th>
                        <th style="padding: 13px;">SALAIRE</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $record)
                        <tr>
                            <td style="border: 3px solid black; padding: 10px;">{{ moisEnFrancais($record->month) }} - {{ $record->year }}</td>
                            <td style="border: 3px solid black; padding: 10px;">{{ $record->institution->nom ?? 'N/A' }}</td>
                            <td style="border: 3px solid black; padding: 10px;">{{ number_format($record->planned_hours, 2) }}</td>
                            <td style="border: 3px solid black; padding: 10px;">{{ number_format($record->done_hours, 2) }}</td>
                            <td style="border: 3px solid black; padding: 10px;">{{ number_format($record->missed_hours, 2) }}</td>
                            <td style="border: 3px solid black; padding: 10px;">{{ number_format($record->remaining_hours, 2) }}</td>
                            <td style="border: 3px solid black; padding: 10px;">{{ number_format($record->tariff_per_hour) }}</td>
                            <td style="border: 3px solid black; padding: 10px;">{{ number_format($record->salary, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <br>
            <br>
        </div>
    </div>
@endsection
