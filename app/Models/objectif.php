<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class objectif extends Model
{
    use HasFactory;

    protected $fillable = [
        'objectif',
        'secteur',
        'date_debut',
        'date_fin',
        'description',
        'echeance',
        'taux_execution_physique',
        'taux_execution_final'
    ];

    public function organisations (){
        return $this->belongsToMany(organisation::class)->withPivot('ancrage');
    }

    public function users () {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    public function programmes(){
        return $this->hasMany(programme::class);
    }
}
