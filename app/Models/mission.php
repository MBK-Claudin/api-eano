<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class mission extends Model
{
    use HasFactory;

    protected $fillable =[
        'libelle',
        'description',
        'objectif',
    ];

    public function activite () : BelongsToMany {
        return $this->belongsToMany(activite::class);
    }

    public function users () : BelongsToMany {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    public function sites() : BelongsToMany {
        return $this->belongsToMany(site::class);
    }

    public function livrables() : BelongsToMany {
        return $this->belongsToMany(livrable::class);
    }



}
