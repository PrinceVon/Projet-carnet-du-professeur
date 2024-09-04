@extends('layouts.user_type.auth')
@section('content')
    <div class="card">
        <div class="container mt-5">
            <h1 class="text-center mb-4">Ajouter une Salle</h1>
        
            <form id="salleForm" method="POST" action="{{ route('ajouter.salle.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="user_id" name="user_id" value="{{ auth()->user()->id }}">
        
                <div class="mb-3">
                    <label for="institution_id" class="form-label">Institution :</label>
                    <select id="institution_id" name="institution_id" class="form-select" required>
                        <option value="">-- Choisissez une institution --</option>
                        @foreach($institutions as $institution)
                            <option value="{{ $institution->id }}">{{ $institution->nom }}</option>
                        @endforeach
                    </select>
                </div>
        
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
                    <label for="nom" class="form-label">Nom de la Salle :</label>
                    <input type="text" id="nom" name="nom" class="form-control" required>
                </div>
        
                <div class="mb-3">
                    <label for="liste" class="form-label">Liste (Excel ou XML) :</label>
                    <input type="file" id="liste" name="liste" class="form-control" accept=".xls,.xlsx,.xml" required>
                </div>
        
                <button type="submit" class="btn btn-primary">Ajouter</button>
            </form>
        </div>
        

    
    </div> 
@endsection
@push('ajouter_salle')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('salleForm');
        if (form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Empêche l'envoi du formulaire pour la validation

                const formData = new FormData(form);

                fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
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