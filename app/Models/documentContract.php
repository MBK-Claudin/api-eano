<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class documentContract extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'file_name',
        'file_path',
        'file_url',
    ];
    public function contacts () : HasMany {
        return $this->hasMany(contract::class);
    }
}
