<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class evenement extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'date_debut',
        'date_fin'
    ];

    public function users () {
        return $this->belongsToMany(User::class);
    }

    public function ano() {
        return $this->belongsTo(ano::class);
    }

    public function activites () : BelongsTo {
        return $this->belongsTo(activiteBudgetAnnuel::class);
    }
}
