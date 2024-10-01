<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class documentAno extends Model
{
    use HasFactory;

    protected $fillable = [
        'titre',
        'file_name',
        'file_path',
        'file_url'
    ];

    public function ano() : BelongsTo {
        return $this->belongsTo(ano::class);
    }
}
