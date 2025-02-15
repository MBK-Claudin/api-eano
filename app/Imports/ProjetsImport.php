<?php
namespace App\Imports;

use App\Models\Collect;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProjetImport implements ToModel, WithHeadingRow
{
    protected $missionId;

    public function __construct($missionId)
    {
        $this->missionId = $missionId;
    }

    public function model(array $row)
    {
        return new Collect([
            'mission_id' => $this->missionId,
            'start' => $row['start'] ?? null,
            'end' => $row['end'] ?? null,
            'intitule_du_projet' => $row['intitule_du_projet'] ?? null,
            'secteur' => $row['secteur'] ?? null,
            'gabon_province' => $row['gabon_province'] ?? null,
            'gabon_departement' => $row['gabon_departement'] ?? null,
            'gabon_adm3' => $row['gabon_adm3'] ?? null,
            'documentations_liees_au_projet' => $row['documentations_liees_au_projet'] ?? null,
            'cordonnees_geographiques' => $row['cordonnees_geographiques'] ?? null,
            'cordonnees_geographiques_latitude' => $row['cordonnees_geographiques_latitude'] ?? null,
            'cordonnees_geographiques_longitude' => $row['cordonnees_geographiques_longitude'] ?? null,
            'cordonnees_geographiques_altitude' => $row['cordonnees_geographiques_altitude'] ?? null,
            'cordonnees_geographiques_precision' => $row['cordonnees_geographiques_precision'] ?? null,
            'cout_initial_du_projet' => $row['cout_initial_du_projet'] ?? null,
            'date_de_debut' => $row['date_de_debut'] ?? null,
            'date_de_fin' => $row['date_de_fin'] ?? null,
            'programme_strategique_du_projet' => $row['programme_strategique_du_projet'] ?? null,
            'ancrage' => $row['ancrage'] ?? null,
            'ancrage_strategique' => $row['ancrage_strategique'] ?? null,
            'ancrage_operationnel' => $row['ancrage_operationnel'] ?? null,
            'maitre_d_ouvrage' => $row['maitre_d_ouvrage'] ?? null,
            'maitre_d_ouvrage_delegue' => $row['maitre_d_ouvrage_delegue'] ?? null,
            'maitre_d_oeuvre' => $row['maitre_d_oeuvre'] ?? null,
            'objectifs_general' => $row['objectifs_general'] ?? null,
            'objectifs_specifiques' => $row['objectifs_specifiques'] ?? null,
            'resultats_attendus' => $row['resultats_attendus'] ?? null,
            'unite_de_gestion_du_projet' => $row['unite_de_gestion_du_projet'] ?? null,
            'parties_prenantes' => $row['parties_prenantes'] ?? null,
        ]);
    }
}
