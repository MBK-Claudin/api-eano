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
    'activite_ptba',
    'force',
    'site_id',
    'taille',
    'mitigation',
    'programme_id',
    'activite_budget_annuel_id'

];



    public function programme()
    {
        return $this->belongsTo(Programme::class);
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function activiteBudgetAnnuel()
    {
        return $this->belongsTo(ActiviteBudgetAnnuel::class);
    }
}
