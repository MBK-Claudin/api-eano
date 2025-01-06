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
        'user_id',
        'activite_id',
        'programme_id'
    ];

    public function documents()
    {
        return $this->hasMany(documentsLivrable::class, 'livrable_id'); // Ajustez 'livrable_id' si votre clé étrangère a un autre nom
    }


    public function user () : belongsTo {
        return $this->belongsTo(User::class);
    }

    public function activite() : BelongsTo {
        return $this->belongsTo(activite::class);
    }

    public function missions () : BelongsToMany {
        return $this->belongsToMany(mission::class);
    }

    public function programme () : belongsTo {
        return $this->belongsTo(programme::class);
    }
}
