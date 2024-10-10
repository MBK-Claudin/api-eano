<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class site extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'province',
        'departement',
        'ville',
        'coordonnees_gps',
        'commentaire',
    ];

    public function activites(): BelongsToMany {
        return $this->belongsToMany(activite::class);
    }

    public function missions () : BelongsToMany {
        return $this->belongsToMany(mission::class);
    }
}
