<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Collect extends Model
{
    use HasFactory;
    protected $fillable = [
        'mission_id','start', 'end', 'intitule_du_projet', 'secteur',
        'gabon_province', 'gabon_departement', 'gabon_adm3',
        'documentations_liees', 'coordonnees_geographiques',
        '_coordonnees_geographiques_latitude', '_coordonnees_geographiques_longitude',
        '_coordonnees_geographiques_altitude', '_coordonnees_geographiques_precision',
        'cout_initial_du_projet', 'date_de_debut', 'date_de_fin',
        'programme_strategique_du_projet', 'ancrage', 'ancrage_strategique',
        'ancrage_operationnel', 'mentionnez_encrage_strategique',
        'maitre_ouvrage', 'maitre_ouvrage_delegue', 'maitre_oeuvre',
        'objectifs_general', 'objectifs_specifiques', 'resultats_attendus',
        'unite_gestion_du_projet', 'parties_prenantes'
    ];

    public function mission()
    {
        return $this->belongsTo(mission::class, 'mission_id');
    }
}
