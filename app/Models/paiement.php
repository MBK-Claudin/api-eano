<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_paiement',
        'montant',
        'date_paiement',
    ];

    public function facture () : BelongsTo {
        return $this->belongsTo(facture::class);
    }

    public function documents() : HasMany {
        return $this->hasMany(documentPaiement::class);
    }
}
