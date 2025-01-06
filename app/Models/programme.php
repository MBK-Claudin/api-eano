<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class programme extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'objectif_specifique',
        'description_objectif_specifique',
        'date_debut',
        'date_fin',
        'Budget_planifier_fcfa',
        'Budget_planifier_us',
        'Budget_planifier_us',
        'Budget_planifier_fcfa',
        'statut',
        'echeance',
        'taux_execution_physique',
        'taux_execution_financier'
    ];

    public function objectif() {
        return $this->belongsTo(objectif::class);
    }
    public function users(){
        return $this->belongsToMany(User::class)->withPivot('role');
    }
    public function organisations(){
        return $this->belongsToMany(organisation::class)->withPivot('ancrage');
    }
    public function budgetAnnuels(){
        return $this->hasMany(budgetAnnuel::class);
    }
    public function missions(){
        return $this->hasMany(mission::class);
    }
    public function financements(){
        return $this->hasMany(Financement::class);
    }
    public function livrables(){
        return $this->hasMany(livrable::class);
    }
}
