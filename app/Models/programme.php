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
        'date_debut',
        'date_fin',
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

    public function budgetannuels (){
        return $this->hasMany(budgetannuel::class);
    }

    public function contract () : HasMany {
        return $this->hasMany(contract::class);
    }
}
