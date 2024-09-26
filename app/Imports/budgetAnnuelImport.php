<?php

namespace App\Imports;

use App\Models\activiteBudgetAnnuel;
use App\Models\composant;
use App\Models\sousComposant;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class budgetAnnuelImport implements ToArray, WithHeadingRow
{
    /**
    * @param Collection $collection
    */

    private $programme_id;

    public function __construct($programme_id)
    {
        $this->programme_id = $programme_id;
    }

    public function array(array $array)
    {
        //dd($array);
        foreach ($array as $data) {
            $date_debut = $this->transformExcelDate($data['date_de_debut']);
            $date_fin = $this->transformExcelDate($data['date_de_fin']);

            // Vérifier s'il y a une composante et pas d'activité
            if (!empty($data['composante_sous_composante']) && empty($data['activites'])) {
                $composant = composant::firstOrCreate(['libelle' => $data['composante_sous_composante']]);
                $composant->budget_annuel_id = $this->programme_id;
                $composant->save();
            }

            // Si à la fois une composante et une activité sont présentes
            if (!empty($data['composante_sous_composante']) && !empty($data['activites'])) {

                $souscomposant = sousComposant::firstOrCreate([
                    'libelle' => $data['composante_sous_composante'],
                ]);

                $souscomposant->composant_id = $composant->id;
                $souscomposant->save();
            }
    
            // Créer l'activité si elle est présente
            if (!empty($data['activites'])) {
                $budget_fcfa = $data['budget_us'] * 600;
                $activite = activiteBudgetAnnuel::create([
                    'libelle' => $data['activites'],
                    'date_debut' => $date_debut,
                    'date_fin' => $date_fin,
                    'budget_fcfa' => $budget_fcfa,
                    'budget_us' => $data['budget_us'],
                ]);

                $activite->sous_composant_id = $souscomposant->id;
                $activite->save();
            }

            if (!empty($data['responsable'])) {
                $responsable = User::firstOrCreate([
                    'name' => $data['responsable'],
                    'email' => $data['email']
                ]);

                $responsable->activiteBudgetAnnuels()->attach($activite->id, ['role' => 'Responsable']);
                //$activite->users->attach($responsable->id, ['role' => 'Responsable']);
            }
        }


    }

    // Fonction pour convertir une date Excel en format MySQL
    private function transformExcelDate($excelDate)
    {
        if (is_numeric($excelDate)) {
            // Convertir la date Excel en date réelle en ajoutant le nombre de jours à 1900-01-01
            return Carbon::createFromDate(1900, 1, 1)->addDays($excelDate - 2)->format('Y-m-d');
        }
        return null; // Gérer les valeurs non valides si besoin
    }
}
