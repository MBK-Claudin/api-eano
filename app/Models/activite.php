<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class activite extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
        'date_debut',
        'date_fin'
    ];

    public function users() : BelongsToMany {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    public function activiteptba() : BelongsTo {
        return $this->belongsTo(activiteBudgetAnnuel::class);
    }

    public function sites() : BelongsToMany {
        return $this->belongsToMany(site::class);
    }
}
