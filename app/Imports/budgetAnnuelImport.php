<?php

namespace App\Imports;

use App\Models\activiteBudgetAnnuel;
use App\Models\composant;
use App\Models\sousComposant;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\ToCollection;

class budgetAnnuelImport implements ToArray
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
        foreach ($array as $data) {

            // Vérifier s'il y a une composante et pas d'activité
            if (!empty($data['composante_sous_composante']) && empty($data['activites'])) {
                $composant = composant::firstOrCreate(['libelle' => $data['composante_sous_composante']]);
                $composant->programme_id = $this->programme_id;
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
                    'date_debut' => $data['date_de_debut'],
                    'date_fin' => $data['date_de_fin'],
                    'budget_fcfa' => $budget_fcfa,
                    'budget_us' => $data['budget_us'],
                ]);

                $activite->sous_composant_id = $souscomposant->id;
                $activite->save();
            }

            if (!empty($data['responsable'])) {
                $responsable = User::firstOrCreate([
                    'nom' => $data['responsable'],
                    'email' => $data['email']
                ]);

                $responsable->activiteBudgetAnnuels->attach($activite->id, ['role' => 'Responsable']);
            }
        }
    }
}
