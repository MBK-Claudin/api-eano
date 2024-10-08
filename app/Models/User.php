<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
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

    public function organisations(){
        return $this->belongsToMany(organisation::class)->withPivot('poste');
    }

    public function objectifs(){
        return $this->belongsToMany(objectif::class)->withPivot('role');
    }

    public function programmes () {
        return $this->belongsToMany(programme::class)->withPivot('poste');
    }

    public function activiteBudgetAnnuels () {
        return $this->belongsToMany(activiteBudgetAnnuel::class)->withPivot('role');
    }

    public function evenements () {
        return $this->belongsToMany(evenement::class)->withPivot('role');
    }

    public function anos () {
        return $this->belongsToMany(ano::class);
    }

    public function activites() : BelongsToMany {
        return $this->belongsToMany(activite::class)->withPivot('role');
    }

}
