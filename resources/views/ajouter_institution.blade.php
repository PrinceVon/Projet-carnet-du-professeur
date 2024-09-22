@extends('layouts.user_type.auth')
@section('content')
    <div class="card">
        <div class="container mt-5">
            <h1 class="text-center mb-4">Ajouter une Institution</h1>

            <form id="institutionForm" method="POST" action="{{ route('ajouter.institution.store') }}" class="form-group">
                @csrf

                <!-- Champ caché pour l'ID de l'utilisateur -->
                <input type="hidden" id="user_id" name="user_id" value="{{ auth()->user()->id }}">

                <div class="mb-3">
                    <label for="annee_id" class="form-label">Année Scolaire :</label>
                    <select id="annee_id" name="annee_id" class="form-select" required>
                        <option value="">-- Choisissez une année scolaire --</option>
                        @foreach($anneesAcademiques as $annee)
                            <option value="{{ $annee->id }}">{{ $annee->annee_scolaire }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="nom" class="form-label">Nom de l'Institution :</label>
                    <input type="text" id="nom" name="nom" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="tarification" class="form-label">Tarification par Heure :</label>
                    <input type="number" id="tarification" name="tarification" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>
        </div>

    </div>
@endsection
@push('ajouter_institution')
<script>
    document.addEventListener('DOMContentLoaded', function() {

        function initializeAutocomplete() {
            $.ajax({
                url: '/get-universite/',
                method: 'GET',
                success: function(response) {
                    var universites = response.universites.map(universite => ({
                        label: universite.nom,
                        value: universite.nom
                    }));
                    // Initialiser l'autocomplétion pour les champs note-plus et note-moins
                    $('#nom').autocomplete({
                        source: universites
                    });
                },
                error: function(xhr) {
                    alert('Une erreur est survenue : ' + xhr.responseText);
                    console.log(xhr.responseText);
                }
            });
        }

        initializeAutocomplete();

        const form = document.getElementById('institutionForm');
        if (form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                // Récupérer la valeur de tarification
                const tarification = parseFloat(document.getElementById('tarification').value);

                // Vérifier si la valeur de tarification est inférieure à 0
                if (isNaN(tarification) || tarification < 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Erreur',
                        text: 'La valeur de tarification ne peut pas être inférieure à 0.',
                        confirmText : 'OK'
                    });
                    return; 
                }

                // Soumettre le formulaire avec AJAX
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        annee_id: document.getElementById('annee_id').value,
                        nom: document.getElementById('nom').value,
                        tarification: tarification,
                        user_id: document.getElementById('user_id').value,
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
                            form.reset();
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
