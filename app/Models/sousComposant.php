<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sousComposant extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle'
    ];

    public function composant () {
        return $this->belongsTo(composant::class);
    }

    public function activitesbudgetannuel () {
        return $this->hasMany(activiteBudgetAnnuel::class);
    }
}
