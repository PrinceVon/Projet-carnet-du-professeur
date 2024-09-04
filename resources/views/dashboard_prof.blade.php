@extends('layouts.user_type.auth')

@section('content')
    @if(auth()->user()->is_active)
        <div>
            <div class="container mt-5">
                <form action="{{ route('update.annee_academique') }}" method="POST" class="d-flex align-items-center">
                    @csrf

                    <!-- Label avec espacement -->
                    <label for="annee_scolaire" class="form-label me-3 mb-0">Choisissez une année scolaire:</label>

                    <!-- Champ de sélection avec taille réduite -->
                    <select id="annee_scolaire" name="annee_scolaire" class="form-select w-auto" onchange="this.form.submit()">
                        <option value="">Sélectionnez...</option>
                        @foreach($anneesAcademiques as $annee)
                            <option value="{{ $annee->id }}" {{ $annee->is_active ? 'selected' : '' }}>
                                {{ $annee->annee_scolaire }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

        </div>


        <div>
            <div class="container-fluid">
                <div class="page-header min-height-300 border-radius-xl mt-4" style="background-image: url('../assets/img/curved-images/curved14.jpg'); background-position-y: 50%;">
                    <span class="mask bg-gradient-primary opacity-6"></span>
                    @if($anneeActive)
                        <div class="centered-text">
                            <h1 style="color: white;"><u>Année Scolaire</u> : {{ $anneeActive->annee_scolaire }}</h1>
                        </div>
                    @endif
                </div>
                <div class="card card-body blur shadow-blur mx-4 mt-n6">
                    <div class="row gx-4">

                            <div class="card-body p-3">
                                <div class="row">
                                    <div class="col-8">
                                        <div class="numbers">
                                            <h3 class="font-weight-bolder mb-0">
                                            <center>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Soyez la bienvenue</center>

                                            </h3>
                                        </div>
                                    </div>
                                    <div class="col-4 text-end">
                                        <div class="icon icon-shape bg-gradient-primary shadow text-center border-radius-md">
                                            <i class="ni ni-paper-diploma text-lg opacity-10" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>


                    </div>
                </div>
            </div>


            <div class="container-fluid py-4">
                <div class="card">
                    <!-- Modal pour l'ajout d'un événement -->
                    <div class="modal fade" id="eventModal" tabindex="-1" role="dialog" aria-labelledby="eventModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="eventModalLabel">Ajouter un événement</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="eventForm">
                                    {{-- @csrf --}}
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="title">Titre de l'UE</label>
                                            <select class="form-control" id="title" name="titre" required>
                                                @foreach($unitesEnseignement as $unite)
                                                    <option value="{{ $unite->id }}">{{ $unite->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="start_time">Heure du début</label>
                                            <input type="time" class="form-control" id="start_time" name="heure_debut" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="end_time">Heure de fin</label>
                                            <input type="time" class="form-control" id="end_time" name="heure_fin" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="institution">Institution</label>
                                            <select class="form-control" id="institution" name="institution" required>
                                                @foreach($institutions as $institution)
                                                    <option value="{{ $institution->id }}">{{ $institution->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="filiere">Filière</label>
                                            <select class="form-control" id="filiere" name="filiere" required>
                                                @foreach($filieres as $filiere)
                                                    <option value="{{ $filiere->id }}">{{ $filiere->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="salle">Salle</label>
                                            <select class="form-control" id="salle" name="salle" required>
                                                @foreach($salles as $salle)
                                                    <option value="{{ $salle->id }}">{{ $salle->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="reminder">Rappel</label>
                                            <select class="form-control" id="reminder" name="rappel" required>
                                                <option value="10">10 minutes</option>
                                                <option value="30">30 minutes</option>
                                                <option value="60">1 heure</option>
                                                <option value="300">5 heures</option>
                                            </select>
                                        </div>

                                        <!-- Champs cachés -->
                                        <input type="hidden" id="user_id" name="user_id" value="{{ auth()->user()->id }}">
                                        <input type="hidden" id="annee_id" name="annee_id" value="{{ $activeId }}">
                                        <input type="hidden" id="event_date" name="event_date">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary">Ajouter</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>





                    <!-- Modal pour modifier un événement -->
                    <div class="modal fade" id="editEventModal" tabindex="-1" role="dialog" aria-labelledby="editEventModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editEventModalLabel">Gérer l'événement</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <form id="editEventForm">
                                    @csrf
                                    <input type="hidden" id="edit_event_id">
                                    <input type="hidden" id="edit_event_date">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="edit_title">Titre de l'UE</label>
                                            <select class="form-control" id="edit_title" name="titre" required>
                                                @foreach($unitesEnseignement as $unite)
                                                    <option value="{{ $unite->id }}">{{ $unite->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_start_time">Heure du début</label>
                                            <input type="time" class="form-control" id="edit_start_time" name="heure_debut" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_end_time">Heure de fin</label>
                                            <input type="time" class="form-control" id="edit_end_time" name="heure_fin" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_institution">Institution</label>
                                            <select class="form-control" id="edit_institution" name="institution" required>
                                                @foreach($institutions as $institution)
                                                    <option value="{{ $institution->id }}">{{ $institution->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_salle">Filière</label>
                                            <select class="form-control" id="edit_filiere" name="filiere" required>
                                                @foreach($filieres as $filiere)
                                                    <option value="{{ $filiere->id }}">{{ $filiere->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_salle">Salle</label>
                                            <select class="form-control" id="edit_salle" name="salle" required>
                                                @foreach($salles as $salle)
                                                    <option value="{{ $salle->id }}">{{ $salle->nom }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="edit_reminder">Rappel</label>
                                            <select class="form-control" id="edit_reminder" name="rappel" required>
                                                <option value="10">10 minutes</option>
                                                <option value="30">30 minutes</option>
                                                <option value="60">1 heure</option>
                                                <option value="300">5 heures</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                                        <button type="submit" class="btn btn-primary" id="save_changes">Sauvegarder les modifications</button>
                                        <button type="button" class="btn btn-danger" id="delete_event">Supprimer</button>
                                        <a href="#" id="start_event" class="btn btn-success">Commencer</a>
                                        <button type="button" class="btn btn-info" id="view_course" style="display: none;">Consulter cours</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>



                    <div class="container">
                        <div class="row">
                            <div class="col-12">
                                <h2 class="text-center mt-5"><b>Agenda pour vos cours</b></h2>
                                <br>
                                <br>
                                <div >
                                    <div id="calendar"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>

                </div>
            </div>
        </div>
    @else
        <script type="text/javascript">
            window.location.href = "{{ route('get.register') }}";
        </script>
    @endif

@endsection

@push('dashboard_prof')
<style>

    .page-header {
    display: flex;
    justify-content: center;
    align-items: center;
    position: relative;
}

    .centered-text {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 1; /* S'assurer que le texte est au-dessus du masque */
        text-align: center;
        color: white; /* Couleur du texte */
    }

</style>
<script>

    $(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var today = moment().startOf('day');

    // Initialiser le calendrier
    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        locale: 'fr',
        selectable: true,
        selectHelper: true,
        select: function(startDate, endDate) {
            if (startDate.isBefore(today)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Date invalide',
                    text: 'Vous ne pouvez pas sélectionner une date antérieure à aujourd\'hui. Voulez-vous planifier un évènement dans le passé?',
                    confirmButtonText: 'OK'
                });
                $('#calendar').fullCalendar('unselect');
            } else {
                $('#event_date').val(startDate.format());
                $('#eventModal').modal('show');
                $('#calendar').fullCalendar('unselect');
            }
        },
        editable: true,
        events: '/evenements',
        eventClick: function(event, jsEvent, view) {
            loadEventForEdit(event.id);
        }
    });

    // Ajouter un événement
    $('#eventForm').on('submit', function(e) {
        e.preventDefault();

        // Créer un objet FormData
        var formData = {
            date: $('#event_date').val(),
            user_id: $('#user_id').val(),
            annee_id: $('#annee_id').val(),
            titre_id: $('#title').val(),
            heure_debut: $('#start_time').val(),
            heure_fin: $('#end_time').val(),
            institution_id: $('#institution').val(),
            filiere_id: $('#filiere').val(),
            salle_id: $('#salle').val(),
            rappel: $('#reminder').val()
        };

        // Convertir l'objet en JSON
        var jsonData = JSON.stringify(formData);

        // Validation des heures
        var now = moment();
        var eventDebutString = $('#event_date').val() + ' ' + $('#start_time').val();
        var eventFinString = $('#event_date').val() + ' ' + $('#end_time').val();
        var eventDebut = moment(eventDebutString);
        var eventFin = moment(eventFinString);

        if (eventDebut.isAfter(eventFin) || now.isAfter(eventDebut)) {
            // Afficher l'alerte personnalisée avec SweetAlert2
            Swal.fire({
                icon: 'warning',
                title: 'Heures invalides',
                text: 'Revoyez vos heures de planification',
                confirmButtonText: 'OK'
            });
        } else {
            $.ajax({
                url: '/evenements',
                type: 'POST',
                data: jsonData,
                contentType: 'application/json',
                success: function(response) {
                    $('#eventModal').modal('hide');
                    $('#calendar').fullCalendar('refetchEvents');
                    Swal.fire({
                        icon: 'success',
                        title: 'Ajout d\'évènement',
                        text: 'Événement ajouté avec succès. 👌✨',
                        confirmButtonText: 'OK'
                    });
                },
                error: function(xhr) {
                        alert('Une erreur est survenue : ' + xhr.responseText);
                        console.log(xhr.responseText);
                    }
            });
        }
    });



    // Charger les détails d'un événement pour modification
    function loadEventForEdit(eventId) {
        $.get('/evenements/' + eventId, function(data) {
            $('#edit_event_id').val(data.event.id);
            $('#edit_title').val(data.unite.id);
            $('#edit_start_time').val(data.event.heure_debut);
            $('#edit_end_time').val(data.event.heure_fin);
            $('#edit_institution').val(data.event.institution_id);
            $('#edit_salle').val(data.salle.id);
            $('#edit_reminder').val(data.event.rappel);
            $('#edit_filiere').val(data.filiere.id);
            $('#edit_event_date').val(data.event.date);

            $('#editEventModal').modal('show');

            if (data.color === 'red') {
                        $('#save_changes').hide();  // Rend invisible le bouton "Sauvegarder les modifications"
                        $('#start_event').hide();   // Rend invisible le bouton "Commencer"
                        $('#view_course').hide();   // Rend invisible le bouton "Consulter cours"
                    } else if (data.color === 'green') {
                        $('#save_changes').hide();
                        $('#start_event').hide();
                        $('#view_course').show();
                    } else if (data.color === 'blue') {
                        $('#save_changes').show();
                        $('#start_event').show();
                        $('#view_course').hide();
                    }
        });
    }

    // Sauvegarder les modifications d'un événement
    $('#editEventForm').submit(function(e) {
        e.preventDefault();
        var eventId = $('#edit_event_id').val();
        $.ajax({
            url: '/evenements/' + eventId,
            method: 'PUT',
            data: $(this).serialize(),
            success: function(response) {
                $('#editEventModal').modal('hide');
                $('#calendar').fullCalendar('refetchEvents');
            }
        });
    });

    // Supprimer un événement
    $('#delete_event').click(function() {
        var eventId = $('#edit_event_id').val();
        Swal.fire({
            title: 'Êtes-vous sûr?',
            text: "Cette action est irréversible!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, supprimer!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: '/evenements/' + eventId,
                    method: 'DELETE',
                    success: function(response) {
                        $('#editEventModal').modal('hide');
                        $('#calendar').fullCalendar('refetchEvents');
                    }
                });
            }
        });
    });

    // Commencer un événement
    $('#start_event').on('click', function() {
            var eventId = $('#edit_event_id').val();
            var now = moment().format('HH:mm:ss'); // L'heure actuelle
            var eventDateString = $('#edit_event_date').val() + ' ' + $('#edit_start_time').val();
            var eventDate = moment(eventDateString).subtract(5, 'minutes');
            var nowMoment = moment();

            if (eventDate.isAfter(nowMoment)) {
                Swal.fire({
                    icon: 'info',
                    title: 'Information',
                    text: 'Veuillez attendre 5 minutes avant l\'heure du début !!',
                    confirmButtonText: 'OK'
                });
            } else {
                $.ajax({
                    url: '/evenements/' + eventId + '/start', // Créez une nouvelle route pour mettre à jour 'heure_arrivee'
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
    $('#view_course').on('click', function() {
        var eventId = $('#edit_event_id').val();
        window.location.href = '/consulter-cours/' + eventId; // Redirige vers la page de consultation du cours
    });

    $('#editEventForm').on('submit', function(e) {
        e.preventDefault();

        // Créer un objet JavaScript avec les données du formulaire
        var eventData = {
            titre_id: $('#edit_title').val(),
            date: $('#edit_event_date').val(),
            heure_debut: $('#edit_start_time').val(),
            heure_fin: $('#edit_end_time').val(),
            salle_id: $('#edit_salle').val(),
            filiere_id: $('#edit_filiere').val(),
            rappel: $('#edit_reminder').val(),
            institution_id: $('#edit_institution').val(),
        };

        // Convertir l'objet en chaîne JSON
        var jsonData = JSON.stringify(eventData);

        $.ajax({
            url: '/evenements/' + $('#edit_event_id').val(),
            method: 'POST',
            data: jsonData,
            contentType: 'application/json', // Définir le type de contenu comme JSON
            success: function(response) {
                $('#editEventModal').modal('hide');
                $('#calendar').fullCalendar('refetchEvents');
                Swal.fire({
                    icon: 'success',
                    title: 'Succès !',
                    text: 'Événement mis à jour avec succès.',
                    confirmButtonText: 'OK'
                });
            },
            error: function(xhr) {
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: 'Une erreur est survenue : ' + xhr.responseText,
                    confirmButtonText: 'OK'
                });
            }
        });
    });


});

</script>

@endpush

@push('dashboard_prof_top')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.18.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/locale/fr.js"></script>

@endpush
