<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ano extends Model
{
    use HasFactory;

    protected $fillable = [
        'budget',
        'budget_cntippee',
        'statut',
        'situation_sctuelle',
        'situation_venir',
        'commentaire'
    ];

    public function users(){
        return $this->belongsToMany(User::class);
    }

    public function evenements () {
        return $this->hasMany(evenement::class);
    }

    public function documents() {
        return $this->belongsToMany(documentAno::class);
    }
}
