@extends('layouts.user_type.auth')
@section('content')
    <div class="card">
        <div class="container mt-5">
            <h1 class="text-center mb-4">Ajouter une Filière</h1>
        
            <form id="filiereForm" method="POST" action="{{ route('ajouter.filiere.store') }}" class="form-group">
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
                    <label for="nom" class="form-label">Nom de la Filière :</label>
                    <input type="text" id="nom" name="nom" class="form-control" required>
                </div>
        
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>
        </div>
        
    </div>
@endsection
@push('ajouter_filiere')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('filiereForm');
        if (form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Empêche l'envoi du formulaire pour la validation

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        annee_id: document.getElementById('annee_id').value,
                        nom: document.getElementById('nom').value,
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