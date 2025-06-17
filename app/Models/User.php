<?php

namespace App\Models;

use App\Services\StatutAgentService;
use App\Utilities\Core;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable; // this sould be imported
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable implements JWTSubject
{
    use Filterable,HasApiTokens, HasFactory,Notifiable,SoftDeletes,HasRoles;

    // Liste blanche des attributs pouvant être filtrés
    private static $whiteListFilter = ['*'];

    // Les attributs qui sont mass-assignable
    protected $guarded = [];

    protected $casts = [];

    protected $appends = [];

    protected $dates = ['deleted_at'];

    /**
     * Fonction boot pour générer un code unique avant la création
     */
    public static function boot()
    {
        parent::boot();

        // Cette méthode est exécutée avant la création de chaque enregistrement
        self::creating(function ($model) {
            // Génération du code unique pour chaque utilisateur
            $model->code = (string) Core::generateIncrementUniqueCode('users', 3, 'code', null);

            $model->name = $model->lastname.' '.$model->firstname;
        });
    }


    public function structure()
    {
        return $this->belongsTo(Structure::class,'structure_id');
    }


    public function settings()
    {

        return $this->hasOne(UserSetting::class, 'user_id');
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
        ];
    }



    /**
     * Specifies the user's FCM token
     *
     * @return string|array
     */
    public function routeNotificationForFcm()
    {
        info($this->push_token);

        return $this->push_token;
    }
}
