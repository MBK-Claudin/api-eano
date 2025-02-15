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
        'objectif',
        'description',
        'statut',
        'date_debut',
         'programme_id',
        'activite_id',
        'site_id',
        'user_id',

    ];



    public function user()
    {
        return $this->belongsTo(User::class); // ou une autre relation appropriée
    }

    public function programme()
    {
        return $this->belongsTo(programme::class);
    }

    public function site()
    {
        return $this->belongsTo(site::class);
    }

    public function activite()
    {
        return $this->belongsTo(activite::class);
    }

    public function collects()
    {
        return $this->hasMany(Collect::class);
    }

}
