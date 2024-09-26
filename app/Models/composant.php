<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class composant extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle'
    ];

    public function budgetannul () {
        return $this->belongsTo(budgetAnnuel::class);
    }

    public function souscomposants () {
        return $this->hasMany(sousComposant::class);
    }
}
