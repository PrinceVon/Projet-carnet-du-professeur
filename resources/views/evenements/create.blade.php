@extends('layouts.user_type.auth')

@section('content')
<div class="card">
    <div class="container">
        <h1>Ajouter un Cours</h1>
        <form action="{{ route('evenements.storage') }}" method="POST" id="form">
            @csrf

            <!-- Titre -->
            <div class="form-group">
                <label for="titre">Titre de l'UE</label>
                <select class="form-control" id="titre" name="titre" required>
                    @foreach ($uniteEnseignements as $unite)
                        <option value="{{ $unite->nom }}">{{ $unite->nom }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Date de Début -->
            <div class="form-group">
                <label for="date_debut">Date de Début</label>
                <input type="date" class="form-control" id="date_debut" name="date_debut" required>
            </div>

            <!-- Date de Fin -->
            <div class="form-group">
                <label for="date_fin">Date de Fin</label>
                <input type="date" class="form-control" id="date_fin" name="date_fin" required>
            </div>

            <!-- Jours de la Semaine -->
            <div class="form-group">
                <h6><b>Jours de la Semaine</b></h6>
                @foreach(['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi'] as $jour)
                    <input type="checkbox" id="jour_{{ $jour }}" name="jours[]" value="{{ $jour }}">
                    <label for="jour_{{ $jour }}">{{ ucfirst($jour) }}</label><br>
                @endforeach
            </div>

            <!-- Heure de Début -->
            <div class="form-group">
                <label for="heure_debut">Heure de Début</label>
                <input type="time" class="form-control" id="heure_debut" name="heure_debut" required>
            </div>

            <!-- Heure de Fin -->
            <div class="form-group">
                <label for="heure_fin">Heure de Fin</label>
                <input type="time" class="form-control" id="heure_fin" name="heure_fin" required>
            </div>

            <!-- Institution -->
            <div class="form-group">
                <label for="institution">Institution</label>
                <select class="form-control" id="institution" name="institution" required>
                    @foreach ($institutions as $institution)
                        <option value="{{ $institution->id }}">{{ $institution->nom }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Salle -->
            <!-- Salle -->
            <div class="form-group">
                <label for="salle">Salle</label>
                <select class="form-control" id="salle" name="salle" required>
                    @foreach ($salles as $salle)
                        <option value="{{ $salle->id }}" data-institution="{{ $salle->institution_id }}">{{ $salle->nom }}</option>
                    @endforeach
                </select>
            </div>


            <!-- Rappel -->
            <div class="form-group">
                <label for="rappel">Rappel</label>
                <select class="form-control" id="rappel" name="rappel" required>
                    <option value="10">10 minutes</option>
                    <option value="30">30 minutes</option>
                    <option value="60">1 heure</option>
                    <option value="300">5 heures</option>
                </select>
            </div>

            <!-- Filière -->
            <div class="form-group">
                <label for="filiere">Filière</label>
                <select class="form-control" id="filiere" name="filiere" required>
                    @foreach ($filieres as $filiere)
                        <option value="{{ $filiere->nom }}">{{ $filiere->nom }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Champs cachés -->
            <input type="hidden" name="color" value="blue">
            <input type="hidden" name="fichier" value="">
            <input type="hidden" id="user_id" name="user_id" value="{{ auth()->user()->id }}">
            <input type="hidden" id="annee_id" name="annee_id" value="{{ $activeId }}">

            <button type="submit" class="btn btn-primary">Enregistrer</button>
        </form>
    </div>
</div>
@endsection
@push('create')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function validateHours(startTime, endTime) {
        return startTime < endTime;
    }

    function validateDates(startDate, endDate) {
        return startDate <= endDate;
    }

    // Utiliser l'ID 'form' pour sélectionner le formulaire
    document.getElementById('form').addEventListener('submit', function(e) {
        e.preventDefault();

        // Obtenir les valeurs des champs du formulaire
        var dateDebut = new Date(document.getElementById('date_debut').value);
        var dateFin = new Date(document.getElementById('date_fin').value);
        var heureDebut = document.getElementById('heure_debut').value;
        var heureFin = document.getElementById('heure_fin').value;

        // Convertir heures en format 24h pour comparaison
        var heureDebutTime = new Date('1970-01-01T' + heureDebut + ':00');
        var heureFinTime = new Date('1970-01-01T' + heureFin + ':00');

        // Obtenir la date actuelle
        var today = new Date();
        today.setHours(0, 0, 0, 0); // Réinitialiser les heures pour la comparaison de date uniquement

        // Valider que la date de début n'est pas antérieure à aujourd'hui
        if (dateDebut < today) {
            Swal.fire({
                icon: 'warning',
                title: 'Date invalide',
                text: 'Vous ne pouvez pas sélectionner une date antérieure à aujourd\'hui.',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Valider les heures
        if (!validateHours(heureDebutTime, heureFinTime)) {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'L\'heure de début ne peut pas être après l\'heure de fin.',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Valider les dates
        if (!validateDates(dateDebut, dateFin)) {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'La date de début ne peut pas être après la date de fin.',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Vérifier la correspondance institution/salle
        var selectedInstitutionId = $('#institution').val();
        var selectedSalle = $('#salle').find('option:selected');
        var selectedSalleInstitutionId = selectedSalle.data('institution');

        if (selectedSalleInstitutionId != selectedInstitutionId) {
            Swal.fire({
                title: 'Avertissement',
                text: 'La salle sélectionnée n\'est pas liée à l\'institution choisie.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return;
        }

        // Soumettre le formulaire si toutes les validations passent
        this.submit();
    });
});

</script>
@endpush


