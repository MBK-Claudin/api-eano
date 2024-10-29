<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class service extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle',
    ];

    public function factures () : BelongsToMany {
        return $this->belongsToMany(facture::class)->withPivot('etape', 'user_id')->withTimestamps();
    }

}
