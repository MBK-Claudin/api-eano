<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_contract',
        'libelle',
        'description',
        'montant',
        'montant_decaisse',
        'montant_restant',
        'programme_id'
    ];

    public function factures () : HasMany {
        return $this->hasMany(facture::class);
    }

    public function activitebudgetannuel () : BelongsTo {
        return $this->belongsTo(activiteBudgetAnnuel::class);
    }

    public function programme()
{
    return $this->belongsTo(Programme::class);
}

}
