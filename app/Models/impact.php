<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class impact extends Model
{
    use HasFactory;

protected $fillable=[
    'type_impact',
    'libelle_impact',
    'force',
    'site_id',
    'taille',
    'mitigation',
    'programme_id',
    'activite_id'

];



    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function activite()
    {
        return $this->belongsTo(activite::class);
    }
}
