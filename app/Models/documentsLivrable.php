<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class documentsLivrable extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'file_name',
        'file_path',
        'file_url',
    ];

    public function livrables () : BelongsToMany {
        return $this->belongsToMany(Livrable::class);
    }
}
