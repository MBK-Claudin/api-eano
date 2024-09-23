<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class organisation extends Model
{
    use HasFactory;

    protected $fillable = [
        'libelle'
    ];

    
    public function users(){
        return $this->belongsToMany(User::class)->withPivot('poste');
    }

    public function objectifs(){
        return $this->belongsToMany(Objectif::class)->withPivot('ancrage');
    }
}
