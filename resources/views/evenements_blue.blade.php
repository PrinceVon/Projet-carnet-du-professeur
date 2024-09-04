@extends('layouts.user_type.auth')

@section('content')
    <div class="card">
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <a class="nav-link active" id="jour-tab" data-bs-toggle="tab" href="#jour" role="tab" aria-controls="jour" aria-selected="true">Aujourd'hui</a>
            </li>
            <li class="nav-item" role="presentation">
                <a class="nav-link" id="semaine-tab" data-bs-toggle="tab" href="#semaine" role="tab" aria-controls="semaine" aria-selected="false">Cette semaine</a>
            </li>
        </ul>

        <!-- Tab panes -->
        <div class="tab-content">
            <div class="tab-pane fade show active" id="jour" role="tabpanel" aria-labelledby="jour-tab">
                <div class="card-body">
                    <center><h3>Vos énènements d' aujourd'hui à réaliser</h3></center>
                    <br>
                    @if($evenement_jour->isNotEmpty())
                        @foreach ($evenement_jour as $event_jour)
                            <ul type='circle'>
                                <li>
                                    Événement nommé : <b>{{ $event_jour->titre }}</b> à réaliser à l'institution : <b>{{ $event_jour->institution }}</b> de <b><u>{{ $event_jour->heure_debut }}</b> à <b>{{ $event_jour->heure_fin }}</u></b> .<br>
                                    <button class="btn btn-success start-event" id="start_event{{ $event_jour->id }}" data-id="{{ $event_jour->id }}" data-date="{{ $event_jour->date }}" data-heure="{{ $event_jour->heure_debut }}">Commencer</button><br>
                                </li>
                            </ul>
                        @endforeach

                    @else
                        <h4>Pas d'évènement planifié pour aujourd'hui. Merci.🙏</h4>

                    @endif
                </div>
            </div>


            <div class="tab-pane fade" id="semaine" role="tabpanel" aria-labelledby="semaine-tab">
                <div class="card-body">
                    <center><h3>Vos énènements de cette semaine à réaliser</h3></center>
                    <br>
                    @if($evenement_semaine->isNotEmpty())
                        @foreach ($evenement_semaine as $event_semaine)
                            @php
                                $date = \Carbon\Carbon::parse($event_semaine->date);
                                $day = $date->translatedFormat('l'); // 'l' donne le jour complet en français
                            @endphp



                            <ul type='disque'>
                                <li>
                                    Événement nommé : <b>{{ $event_semaine->titre }}</b> à réaliser à l'institution : <b>{{ $event_semaine->institution }}</b> le <b>{{ $event_semaine->date }}</b> de <b>{{ $event_semaine->heure_debut }}</b> à <b>{{ $event_semaine->heure_fin }}</b>.  <u><b>({{ ucfirst($day) }})</b></u>
                                    <br>
                                </li>
                            </ul>
                        @endforeach

                    @else
                        <h4>Pas d'évènement planifié pour cette semaine. Merci.✨</h4>

                    @endif
                </div>
            </div>
        </div>
        <br>
    </div>

@endsection

@push('blue_jour')
<script>

    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // Gestionnaire de clic pour tous les boutons avec la classe "start-event"
        $('.start-event').on('click', function() {
            var eventId = $(this).data('id');
            var event_date = $(this).data('date');
            var eventHeure = $(this).data('heure');
            var now = moment().format('HH:mm:ss');
            var eventDateString = event_date +' '+ eventHeure;
            var eventDate = moment(eventDateString).subtract(5, 'minutes'); // Heure de début moins 5 minutes
            var nowMoment = moment(); // Heure actuelle

            if (eventDate.isAfter(nowMoment)) {

                console.log(eventDate.isAfter(nowMoment));
                console.log(nowMoment);
                console.log(eventDate);
                Swal.fire({
                    icon: 'info',
                    title: 'Information',
                    text: 'Veuillez attendre 5 minutes avant l\'heure du début !!',
                    confirmButtonText: 'OK'
                });
            } else {
                $.ajax({
                    url: '/evenements/' + eventId + '/start',
                    method: 'PATCH',
                    data: { heure_arrivee: now },
                    success: function(response) {
                        if (response.success) {
                            window.location.href = '/commencer-cours/' + eventId; // Rediriger vers la nouvelle page
                        } else {
                            alert('Erreur : ' + response.message);
                        }
                    },
                    error: function(xhr) {
                        alert('Une erreur est survenue : ' + xhr.responseText);
                        console.log(xhr.responseText);
                    }
                });
            }
        });
    });
</script>

@endpush
