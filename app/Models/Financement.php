<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Financement extends Model
{
    use HasFactory;
protected $fillable = [
    'type_financement',
    'montant',
    'principale',
    'budgetAnnuel',
    'montant_usd',
    'organisation_id',
    'programme_id'

];

// public function budgetAnuel()
// {
//     return $this->belongsTo(budgetAnnuel::class);
// }

public function organisation()
{
    return $this->belongsTo(organisation::class);
}

public function programme()
{
    return $this->belongsTo(Programme::class); // Financement appartient à Programme
}

public function budgetAnnuel()
{
    return $this->belongsTo(budgetAnnuel::class); // Financement appartient à Programme
}
}
