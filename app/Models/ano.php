<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ano extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'budget',
        'budget_cntippee',
        'statut',
        'situation_sctuelle',
        'situation_venir',
        'commentaire'
    ];

    public function users(){
        return $this->belongsToMany(User::class)->withPivot('action', 'role');
    }

    public function evenements () {
        return $this->hasMany(evenement::class);
    }

    public function documents() : HasMany {
        return $this->hasMany(documentAno::class);
    }

    public function factures () : HasMany {
        return $this->hasMany(facture::class);
    }

    public function activitebudgetannuel() : BelongsTo {
        return $this->belongsTo(activiteBudgetAnnuel::class);
    }

}
