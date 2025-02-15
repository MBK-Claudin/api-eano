<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class enquete extends Model
{
     use HasFactory;

        protected $fillable = [
            'mission_id', 'intitule_projet', 'ministere', 'gabon_province',
            'gabon_departement', 'gabon_adm3', 'observations', 'latitude',
            'longitude', 'altitude', 'precision', 'cout_initial',
            'date_debut', 'date_fin', 'photo_url', 'video_url'
        ];

        public function mission()
        {
            return $this->belongsTo(mission::class);
        }
}

