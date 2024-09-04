<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use App\Models\Institution;

class SalaryReportController extends Controller
{
    public function index()
    {
        $userId = auth()->id();
        // Requête pour obtenir les données nécessaires, groupées par mois et année
        $data = Evenement::selectRaw('
            YEAR(date) as year,
            MONTH(date) as month,
            institution_id,
            SUM(TIMESTAMPDIFF(MINUTE, heure_debut, heure_fin) / 60) as planned_hours,
            SUM(CASE WHEN color = \'blue\' THEN TIMESTAMPDIFF(MINUTE, heure_debut, heure_fin) / 60 ELSE 0 END) as remaining_hours,
            SUM(CASE WHEN color = \'red\' THEN TIMESTAMPDIFF(MINUTE, heure_debut, heure_fin) / 60 ELSE 0 END) as missed_hours,
            SUM(duree) as done_hours
        ')
            ->where('user_id', $userId)
            ->groupBy('year', 'month', 'institution_id')
            ->orderBy('month', 'asc')  // Tri par mois en ordre croissant
            ->get();

        // Ajouter les données de tarification et calculer le salaire
        foreach ($data as $record) {
            $institution = Institution::find($record->institution_id);
            $record->tariff_per_hour = $institution ? $institution->tarification : 0;
            $record->salary = $record->done_hours * $record->tariff_per_hour;

        }

        // Passer les données à la vue
        return view('salary_report', compact('data'));
    }
}
