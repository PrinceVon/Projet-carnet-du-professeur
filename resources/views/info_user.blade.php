@extends('layouts.user_type.auth')
@section('content')
@if($events_blue->isNotEmpty()  || $events_green->isNotEmpty()  || $events_red->isNotEmpty())
<div class="form-group card">
    <div class="card-header pb-0 px-3">
        <h4 class="mb-0"><u>{{ __('Gestion des évènements') }}</u> pour l'utilisateur, {{ ucfirst($user->name) }}</h4>
    </div>
    <br>
    @if($events_blue->isNotEmpty())

        <table  width=100%>

            <thead>
                <tr>
                    <th colspan="2">Événements en attente</th>
                    <th ></th>
                </tr>

            </thead>
            <tbody>
                @foreach ($events_blue as $event_blue)
                    <tr>
                        <td style="padding: 10px;"><input type="text" class="form-control" value="{{ $event_blue->titre }}" title="{{ $event_blue->titre }}" readonly></td>
                        <td style="padding: 10px;">
                            <input type="text" class="form-control"
                                   value="{{ $event_blue->date .' de '. $event_blue->heure_debut.' à '.$event_blue->heure_fin.' | Lieu: '. $event_blue->institution }}"
                                   readonly>
                        </td>
                        <td style="padding: 10px;"><div class="cercle-blue"></div></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <br>
    @endif

    @if($events_green->isNotEmpty())

        <table  width=100%>

            <thead>
                <tr>
                    <th colspan="2">Événements déjà faits</th>
                    <th ></th>
                </tr>

            </thead>
            <tbody>
                @foreach ($events_green as $event_green)
                    <tr>
                        <td><input type="text" class="form-control" value="{{ $event_green->titre }}" title="{{ $event_green->titre }}" readonly></td>
                        <td>
                            <input type="text" class="form-control"
                                   value="{{ $event_green->date .' de '. $event_green->heure_debut.' à '.$event_green->heure_fin.' | Lieu: '. $event_green->institution }}"
                                   title="{{ $event_green->date .' de '. $event_green->heure_debut.' à '.$event_green->heure_fin.' | Lieu: '. $event_green->institution }}"
                                   readonly>
                        </td>
                        <td>
                            <input type="text" class="form-control"
                                   value="{{ 'Heure d\'arrivée : '.$event_green->heure_arrivee.' | Heure de départ : '.$event_green->heure_depart }}"
                                   readonly
                                   title="{{ 'Heure d\'arrivée : '.$event_green->heure_arrivee.' | Heure de départ : '.$event_green->heure_depart }}">
                        </td>


                        <td><center><div
                            class="cercle-green"
                            role="button"
                            tabindex="0"
                            aria-pressed="false"
                            data-id="{{ $event_green->id }}"></div></center>
                            <center><div><small>*</small></div></center></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
        <br>
    @endif

    @if($events_red->isNotEmpty())

        <table  width=100%>

            <thead>
                <tr>
                    <th colspan="2">Événements ratés</th>
                    <th ></th>
                </tr>

            </thead>
            <tbody>
                @foreach ($events_red as $event_red)
                    <tr>
                        <td style="padding: 10px;"><input type="text" class="form-control" readonly value="{{ $event_red->titre }}" title="{{ $event_red->titre }}"></td>
                        <td style="padding: 10px;">
                            <input type="text" class="form-control"
                                   value="{{ $event_red->date .' de '. $event_red->heure_debut.' à '.$event_red->heure_fin.' | Lieu: '. $event_red->institution }}"
                                   readonly>
                        </td>
                        <td style="padding: 10px;"><div class="cercle-red"></div></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <br>
        <h5><u>Information!!!</u></h5>
        <div class="cercle-green"></div>
        <p style = "color : black;">* : Cliquer pour voir le rapport de présence des étudiants lors de cet évènement.</p>
        <br>
    @endif

</div>
@endif
@endsection

@push('user-profile')
<style>
    .cercle-blue {
        width: 30px; /* Largeur du cercle */
            height: 30px; /* Hauteur du cercle */
            background-color: #500ad3; /* Couleur de fond du cercle */
            border-radius: 50%; /* Rend le div circulaire */
            display: inline-block; /* Pour que le cercle ne prenne pas plus d'espace que nécessaire */
    }
    .cercle-green {
        width: 30px; /* Largeur du cercle */
            height: 30px; /* Hauteur du cercle */
            background-color: #008a07; /* Couleur de fond du cercle */
            border-radius: 50%; /* Rend le div circulaire */
            display: inline-block; /* Pour que le cercle ne prenne pas plus d'espace que nécessaire */
            cursor: pointer;

    }
    .cercle-green:hover {
    background-color: rgb(3, 65, 3); /* Change la couleur au survol pour l'effet visuel */
    }

    .cercle-red {
        width: 30px; /* Largeur du cercle */
            height: 30px; /* Hauteur du cercle */
            background-color: #e00000; /* Couleur de fond du cercle */
            border-radius: 50%; /* Rend le div circulaire */
            display: inline-block; /* Pour que le cercle ne prenne pas plus d'espace que nécessaire */
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Obtenir la route à partir de Blade et le stocker dans une variable JavaScript
        const routeUrl = `{{ route('sans.pdf', ['evenementId' => '__id__']) }}`;

        // Sélectionne toutes les divs avec la classe 'cercle-green'
        const buttons = document.querySelectorAll('.cercle-green');

        buttons.forEach(button => {
            button.addEventListener('click', function () {
                // Récupère l'ID depuis l'attribut data-id
                const evenementId = button.getAttribute('data-id');

                // Construire l'URL pour la redirection
                const url = routeUrl.replace('__id__', evenementId);

                // Rediriger vers l'URL
                window.location.href = url;
            });

            button.addEventListener('keydown', function (event) {
                if (event.key === 'Enter' || event.key === ' ') {
                    button.click(); // Simule le clic pour les touches "Enter" ou "Space"
                }
            });
        });
    });
</script>


@endpush
