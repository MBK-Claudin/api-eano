<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_facture',
        'type_facture',
        'montant',
        'date_reception',
        'couverture'
    ];

    public function ano () : BelongsTo {
        return $this->belongsTo(ano::class);
    }

    public function contract () : BelongsTo {
        return $this->belongsTo(contract::class);
    }

    public function documents () : HasMany {
        return $this->hasMany(documentFacture::class);
    }

    public function paiements () : HasMany {
        return $this->hasMany(paiement::class);
    }

    public function services() : BelongsToMany {
        return $this->belongsToMany(service::class)->withPivot('etape', 'user_id')->withTimestamps();
    }

    public function users () : BelongsToMany {
        return $this->belongsToMany(User::class)->withPivot('etape', 'service_id')->withTimestamps();
    }
}
