<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class livrable extends Model
{
    use HasFactory;

    protected $fillable = [
        'livrable',
    ];

    public function documents () : BelongsToMany {
        return $this->belongsToMany(documentsLivrable::class);
    }

    public function users () : BelongsToMany {
        return $this->belongsToMany(User::class)->withPivot('role');
    }

    public function activite() : BelongsTo {
        return $this->belongsTo(activite::class);
    }

    public function missions () : BelongsToMany {
        return $this->belongsToMany(mission::class);
    }
}
