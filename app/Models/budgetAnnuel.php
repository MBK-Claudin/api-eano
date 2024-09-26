<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class budgetAnnuel extends Model
{
    use HasFactory;

    protected $fillable = [
        'periode',
        'date_debut',
        'date_fin',
        'Budget_planifier',
        'Budget_executer',
        'file_name',
        'file_path',
        'file_url',
    ];

    public function programme () {
        return $this->belongsTo(programme::class);
    }

    public function composants () {
        return $this->hasMany(composant::class);
    }
}
