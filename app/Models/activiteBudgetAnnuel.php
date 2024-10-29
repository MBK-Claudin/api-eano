<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class activiteBudgetAnnuel extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'budget_fcfa',
        'budget_us',
        'montant_decaisser',
        'montant_restant',
        'date_debut',
        'date_fin',
        'taux_execution_physique',
        'taux_execution_financier',
    ];

    public function soussomposant () {
        return $this->belongsTo(sousComposant::class);
    }

    public function users () {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    public function evenements () : HasMany {
        return $this->hasMany(evenement::class);
    }

    public function activites() : HasMany {
        return $this->hasMany(activite::class);
    }

    public function anos() : HasMany {
        return $this->hasMany(ano::class);
    }

    public function contracts () : HasMany {
        return $this->hasMany(contract::class);
    }

}
