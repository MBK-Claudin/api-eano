<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;


class User extends Authenticatable implements JWTSubject
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    protected $fillable = [
        'azure_id',
        'photo_url',
        'name',
        'email',
        'azure_token',
        'azure_refresh_token',
        'password',
        'entreprise',
        'statut',
        'mot_de_passe_expire',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function organisations() : BelongsToMany {
        return $this->belongsToMany(organisation::class)->withPivot('poste');
    }

    public function objectifs(): BelongsToMany {
        return $this->belongsToMany(objectif::class)->withPivot('role');
    }

    public function programmes ():BelongsToMany {
        return $this->belongsToMany(programme::class)->withPivot('role');
    }

    public function activiteBudgetAnnuels ():BelongsToMany {
        return $this->belongsToMany(activiteBudgetAnnuel::class)->withPivot('role');
    }


    public function anos () : BelongsToMany {
        return $this->belongsToMany(ano::class)->withPivot('action', 'role');
    }

    public function activites() : BelongsToMany {
        return $this->belongsToMany(activite::class)->withPivot('role');
    }

    public function livrables () : hasMany {
        return $this->hasMany(livrable::class);
    }

    public function missions () : HasMany {
        return $this->hasMany(mission::class);
    }


    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    public function evenements() : HasMany {
        return $this->hasMany(evenement::class);
    }

    public function getJWTIdentifier()
    {
        return $this->getKey(); // Retourne l'identifiant unique de l'utilisateur
    }

    public function getJWTCustomClaims()
    {
        return []; // Tu peux ajouter des claims personnalisés si nécessaire
    }
}
