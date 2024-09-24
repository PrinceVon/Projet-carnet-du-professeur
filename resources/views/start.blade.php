@extends('layouts.user_type.auth')

@section('content')
    <div class="card">
        <div class="container mt-5">
            <h1 class="text-center"><b>Cours</b></h1>
            <h2>Unité d'enseignement : {{ $evenement->titre }}</h2>
            <h2>Date : {{ $evenement->date }}</h2>
            <h2>Heure de début : {{ $evenement->heure_debut }}</h2>
            <h2>Heure de fin : {{ $evenement->heure_fin }}</h2>
            <h2>Institution : {{ $evenement->institution }}</h2>
            <h2>Salle : {{ $evenement->salle }}</h2>
            <h2>Filière : {{ $evenement->filiere }}</h2>
            <div class="text-center mt-3">
                <button class="btn btn-primary" id='faire-appel'>Faire l'appel</button>
                <a href="{{ route('presence.pdf', ['evenementId' => $evenement->id]) }}" class="btn btn-primary">
                    Générer PDF
                </a>

            </div>

            <!-- Formulaire pour les notes et commentaires -->
            <div class="mt-5">
                <h3>Ajouter des Notes et Commentaires</h3>
                <form action="{{ route('evenement.storeNotesAndComments', $evenement->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="note-plus">Note (Plus) :</label>
                        <textarea id="note-plus" name="note_plus" class="form-control" rows="3"></textarea>
                        <input type="text" id="note-plus-hidden" style="position: absolute; left: -9999px;" />
                    </div>
                    <div class="form-group">
                        <label for="note-moins">Note (Moins) :</label>
                        <textarea id="note-moins" name="note_moins" class="form-control" rows="3"></textarea>
                        <input type="text" id="note-moins-hidden" style="position: absolute; left: -9999px;" />
                    </div>

                    <div class="form-group">
                        <label for="note-devoir-maison">Note (Devoir Maison) :</label>
                        <textarea id="note-devoir-maison" name="note_devoir_maison" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="commentaire">Commentaire :</label>
                        <textarea id="commentaire" name="commentaire" class="form-control" rows="3" oninput="convertToList()"></textarea>
                        <ul id="commentaireListe" class="mt-2"></ul>
                    </div>

                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </form>
            </div>
            <div class="text-center mt-3">
                <button class="btn btn-success" id="terminer" name="terminer">Terminer</button>
            </div>

        </div>

        <!-- Modal pour l'appel -->
        <div class="modal fade" id="appelModal" tabindex="-1" role="dialog" aria-labelledby="appelModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="appelModalLabel">Faire l'appel</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Liste des étudiants -->
                        <div id="students-list">
                            <!-- Les étudiants seront chargés ici dynamiquement -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                        <button type="button" class="btn btn-primary" id="enregistrer-appel">Enregistrer l'appel</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('start')
<script>
    $(document).ready(function() {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        initializeAutocomplete();
        // Fonction pour convertir le texte du commentaire en liste
        function convertToList() {
            const textarea = document.getElementById('commentaire');
            const list = document.getElementById('commentaireListe');

            const lines = textarea.value.split('\n');

            list.innerHTML = '';

            lines.forEach(line => {
                if (line.trim()) { // Ignore empty lines
                    const listItem = document.createElement('li');
                    listItem.textContent = line.trim();
                    list.appendChild(listItem);
                }
            });
        }

        // Fonction pour récupérer les étudiants et initialiser l'autocomplétion
        function initializeAutocomplete() {
            var eventId = "{{ $evenement->id }}";

            $.ajax({
                url: '/get-students/' + eventId,
                method: 'GET',
                success: function(response) {
                var students = response.students.map(student => ({
                        label: student.nom + ' ' + student.prenom,
                        value: student.nom + ' ' + student.prenom
                    }));
                    // Initialiser l'autocomplétion pour les champs note-plus et note-moins
                    $('#note-plus').autocomplete({
                        source: students
                    });

                    $('#note-moins').autocomplete({
                        source: students
                    });
                },
                error: function(xhr) {
                    alert('Une erreur est survenue : ' + xhr.responseText);
                    console.log(xhr.responseText);
                }
            });
        }


        $('#terminer').on('click', function() {
            Swal.fire({
                icon: 'question',
                title: 'Question',
                text: 'Avez-vous vraiment fini ?',
                showCancelButton: true,
                confirmButtonText: 'Oui',
                cancelButtonText: 'Non'
            }).then((result) => {
                if (result.isConfirmed) {
                    var eventId = "{{ $evenement->id }}"; // Obtenez l'ID de l'événement à partir de la vue
                    var now = new Date(); // Obtenez l'heure actuelle
                    var heureDepart = new Date().toLocaleTimeString('fr-FR', { hour12: false });

                    var heureArrivee = "{{ $evenement->heure_arrivee }}";
                    var format = 'HH:mm:ss'; // Le format attendu pour les heures (24 heures)
                    var arrivee = moment(heureArrivee, format); // Utilisation de Moment.js pour le parsing
                    var depart = moment(heureDepart, format); // Utilisation de Moment.js pour le parsing
                    var duree = depart.diff(arrivee) / (1000 * 60 * 60);

                    $.ajax({
                        url: '/terminer/' + eventId,
                        method: 'PUT',
                        data: {
                            heure_depart: heureDepart,
                            duree: duree
                        },
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Succès 👍!',
                                text: 'Evènement terminé avec succès.',
                                confirmButtonText: 'OK'
                            });
                            window.location.href = '/dashboard-du-prof'; // Rediriger vers l'agenda
                        },
                        error: function(xhr) {
                            alert('Une erreur est survenue : ' + xhr.responseText);
                            console.log(xhr.responseText);
                        }
                    });
                }
            });
        });

        $('#faire-appel').on('click', function() {
            var eventId = "{{ $evenement->id }}";

            $.ajax({
                url: '/get-students/' + eventId,
                method: 'GET',
                success: function(response) {
                    var studentsList = $('#students-list');
                    studentsList.empty();

                    response.students.forEach(function(student) {
                        studentsList.append(
                            '<div class="form-group">' +
                            '<div class="form-check">' +
                            '<input class="form-check-input" type="radio" name="status_' + student.id + '" id="present_' + student.id + '" value="Présent(e)" required>' +
                            '<label class="form-check-label" for="present_' + student.id + '">' +
                            '<strong>' + student.nom + ' ' + student.prenom + '</strong>' +
                            ' - Présent' +
                            '</label>' +
                            '</div>' +
                            '<div class="form-check">' +
                            '<input class="form-check-input" type="radio" name="status_' + student.id + '" id="absent_' + student.id + '" value="Absent(e)" required>' +
                            '<label class="form-check-label" for="absent_' + student.id + '">' +
                            '<strong>' + student.nom + ' ' + student.prenom + '</strong>' +
                            ' - Absent' +
                            '</label>' +
                            '</div>' +
                            '</div>'
                        );
                    });

                    $('#appelModal').modal('show');
                },
                error: function(xhr) {
                    alert('Une erreur est survenue : ' + xhr.responseText);
                    console.log(xhr.responseText);
                }
            });
        });

        $('#enregistrer-appel').on('click', function() {
            var eventId = "{{ $evenement->id }}";
            var statuses = {};

            $('input[type=radio]:checked').each(function() {
                var studentId = $(this).attr('id').split('_')[1];
                var status = $(this).val();
                statuses[studentId] = status;
            });

            $.ajax({
                url: '/save-attendance/' + eventId,
                method: 'POST',
                data: {
                    statuses: statuses
                },
                success: function(response) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Succès !',
                        text: 'Votre enregistrement a été réalisé avec succès.',
                        confirmButtonText: 'OK'
                    });
                    console.log(response);
                    $('#appelModal').modal('hide');
                },
                error: function(xhr) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Echec !',
                        text: 'Veuillez à choisir avant la soumission.',
                        confirmButtonText: 'OK'
                    });
                    console.log(xhr.responseText);
                }
            });
        });
    });
</script>
@endpush

