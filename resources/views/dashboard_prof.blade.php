@extends('layouts.user_type.auth')

@section('content')
    @if(auth()->user()->is_active)
        @if($anneeActive != null)
            <div>
                <div class="container mt-5">
                    <form action="{{ route('update.annee_academique') }}" method="POST" class="d-flex align-items-center">
                        @csrf

                        <!-- Label avec espacement -->
                        <label for="annee_scolaire" class="form-label me-3 mb-0">Année scolaire activée :</label>

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
                                            <div >
                                                <img src="{{ asset(Auth::user()->photo) }}" alt="Photo de profil" class="w-25 border-radius-lg shadow-sm">
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
                                        <h5 class="modal-title" id="eventModalLabel">Ajouter un cours</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <form id="eventForm">
                                        {{-- @csrf --}}
                                        <div class="modal-body">
                                            <!-- Form fields -->
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
                                                        <option value="{{ $salle->id }}" data-institution="{{ $salle->institution_id }}">{{ $salle->nom }}</option>
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
                                            <button type="submit" class="btn btn-primary" id="submitButton">Ajouter</button>
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
                                        <h5 class="modal-title" id="editEventModalLabel">Gérer le cours</h5>
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
                                                <label for="edit_filiere">Filière</label>
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
                                                        <option value="{{ $salle->id }}" data-institution="{{ $salle->institution_id }}">{{ $salle->nom }}</option>
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
            <div class='card'>
                <div class="container mt-5">
                    <h1 class="text-center mb-4">Configurer avant tout votre année academique</h1>

                    <form id="anneeForm2" method="POST" action="{{ route('ajouter.annee_scolaire.store2') }}" class="form-group">
                        @csrf

                        <!-- Champ caché pour l'ID de l'utilisateur -->
                        <input type="hidden" id="user_id2" name="user_id" value="{{ auth()->user()->id }}">

                        <div class="mb-3">
                            <label for="annee_scolaire" class="form-label">Année Scolaire :</label>
                            <input type="text" id="annee_scolaire2" name="annee_scolaire" class="form-control" placeholder="2022-2023" required>
                        </div>

                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>
        @endif
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
        font-family: 'Times New Roman', Times, serif;
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
    const anneeActiveJson = @json($anneeActive);

    $('#calendar').fullCalendar({
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        locale: 'fr',
        selectable: true,
        selectHelper: true,
        editable: true,
        events: '/evenements',
        select: anneeActiveJson ? function(startDate, endDate) {
            const annee = startDate.year();
            const anneeScolaire = anneeActiveJson.annee_scolaire;

            // Utilisation d'une expression régulière pour extraire les années
            const regex = /^(\d{4})-(\d{4})$/;
            const match = anneeScolaire.match(regex);

            if (!match) {
                Swal.fire({
                    icon: 'error',
                    title: 'Format invalide',
                    text: 'Le format de l\'année scolaire est invalide.',
                    confirmButtonText: 'OK'
                });
                $('#calendar').fullCalendar('unselect');
                return;
            }

            const [_, debut, fin] = match;
            const debutYear = parseInt(debut, 10);
            const finYear = parseInt(fin, 10);

            if (annee < debutYear || annee > finYear) {
                Swal.fire({
                    icon: 'error',
                    title: 'Année non valide',
                    text: 'Veuillez activer l\'année scolaire correspondante.',
                    confirmButtonText: 'OK'
                });
                $('#calendar').fullCalendar('unselect');
                return;
            }

            if (startDate.isBefore(today)) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Date invalide',
                    text: 'Vous ne pouvez pas sélectionner une date antérieure à aujourd\'hui.',
                    confirmButtonText: 'OK'
                });
                $('#calendar').fullCalendar('unselect');
                return;
            }

            $('#event_date').val(startDate.format());
            $('#eventModal').modal('show');
            $('#calendar').fullCalendar('unselect');
        } : undefined,
        eventClick: function(event, jsEvent, view) {
            loadEventForEdit(event.id);
        }
    });

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
                $('#save_changes').hide();
                $('#start_event').hide();
                $('#view_course').hide();
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

    $('#eventForm').on('submit', function(e) {
        e.preventDefault();

        var selectedInstitutionId = $('#institution').val();
        var selectedSalleId = $('#salle').val();
        var selectedSalleInstitutionId = $('#salle option:selected').data('institution');

        if (selectedSalleInstitutionId != selectedInstitutionId) {
            Swal.fire({
                title: 'Avertissement',
                text: 'La salle sélectionnée n\'est pas liée à l\'institution choisie.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

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

        var jsonData = JSON.stringify(formData);

        var now = moment();
        var eventDebutString = $('#event_date').val() + ' ' + $('#start_time').val();
        var eventFinString = $('#event_date').val() + ' ' + $('#end_time').val();
        var eventDebut = moment(eventDebutString);
        var eventFin = moment(eventFinString);

        if (eventDebut.isAfter(eventFin) || now.isAfter(eventDebut)) {
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
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Une erreur est survenue : ' + xhr.responseText,
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });

    $('#editEventForm').submit(function(e) {
        e.preventDefault();

        var selectedInstitutionId = $('#edit_institution').val();
        var selectedSalleId = $('#edit_salle').val();
        var selectedSalleInstitutionId = $('#edit_salle option:selected').data('institution');

        if (selectedSalleInstitutionId != selectedInstitutionId) {
            Swal.fire({
                title: 'Avertissement',
                text: 'La salle sélectionnée n\'est pas liée à l\'institution choisie.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }
        var eventId = $('#edit_event_id').val();
        var eventData = {
            titre_id: $('#edit_title').val(),
            date: $('#edit_event_date').val(),
            heure_debut: $('#edit_start_time').val(),
            heure_fin: $('#edit_end_time').val(),
            salle_id: $('#edit_salle').val(),
            filiere_id: $('#edit_filiere').val(),
            rappel: $('#edit_reminder').val(),
            institution_id: $('#edit_institution').val()
        };

        var jsonData = JSON.stringify(eventData);

        $.ajax({
            url: '/evenements/' + eventId,
            method: 'POST',
            data: jsonData,
            contentType: 'application/json',
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

    $('#start_event').on('click', function() {
        var eventId = $('#edit_event_id').val();
        var now = moment().format('HH:mm:ss');
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
                url: '/evenements/' + eventId + '/start',
                method: 'PATCH',
                data: { heure_arrivee: now },
                success: function(response) {
                    if (response.success) {
                        window.location.href = '/commencer-cours/' + eventId;
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: response.message,
                            confirmButtonText: 'OK'
                        });
                    }
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
        }
    });

    $('#view_course').on('click', function() {
        var eventId = $('#edit_event_id').val();
        window.location.href = '/commencer-cours/' + eventId;
    });
});

</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('anneeForm2');
        if (form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Empêche l'envoi du formulaire pour la validation

                const anneeScolaire = document.getElementById('annee_scolaire2').value;
                const regex = /^(\d{4})-(\d{4})$/;
                const match = anneeScolaire.match(regex);

                if (!match) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Format invalide',
                        text: 'Le format de l\'année scolaire doit être "YYYY-YYYY" (ex : 2023-2024).',
                    });
                    return;
                }

                const [_, debut, fin] = match;
                const debutYear = parseInt(debut, 10);
                const finYear = parseInt(fin, 10);

                if (finYear !== debutYear + 1) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Années non consécutives',
                        text: 'Les années doivent être consécutives (ex : 2022-2023).',
                    });
                    return;
                }

                if (debutYear >= finYear) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Année invalide',
                        text: 'L\'année de début doit être inférieure à l\'année de fin.',
                    });
                    return;
                }

                // Si tout est valide, soumettre le formulaire avec AJAX
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        annee_scolaire: anneeScolaire,
                        user_id: document.getElementById('user_id2').value,
                    }),
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Succès',
                            text: data.message,
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erreur',
                            text: data.message,
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'Une erreur est survenue.',
                    });
                });
            });
        }
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
