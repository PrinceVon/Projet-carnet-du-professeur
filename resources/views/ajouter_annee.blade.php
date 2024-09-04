@extends('layouts.user_type.auth')
@section('content')
    <div class="card">
        <div class="container mt-5">
            <h1 class="text-center mb-4">Ajouter une Année Académique</h1>
        
            <form id="anneeForm" method="POST" action="{{ route('ajouter.annee_scolaire.store') }}" class="form-group">
                @csrf
        
                <!-- Champ caché pour l'ID de l'utilisateur -->
                <input type="hidden" id="user_id" name="user_id" value="{{ auth()->user()->id }}">
        
                <div class="mb-3">
                    <label for="annee_scolaire" class="form-label">Année Scolaire :</label>
                    <input type="text" id="annee_scolaire" name="annee_scolaire" class="form-control" placeholder="2022-2023" required>
                </div>
        
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
        
    </div> 
@endsection
@push('ajout_annee')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('anneeForm');
        if (form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Empêche l'envoi du formulaire pour la validation

                const anneeScolaire = document.getElementById('annee_scolaire').value;
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
                            form.reset(); // Optionnel : Réinitialiser le formulaire
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