<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
class Document extends Model
{
    use Filterable, HasFactory,SoftDeletes;

    protected $fillable = [
        'titre',
        'description',
        'fichier_path',
        'type',
        'statut',
        'date_depot',
        'dossier_id',
        'utilisateur_id'
    ];

    protected $dates = ['date_depot'];

    public function dossier()
    {
        return $this->belongsTo(Dossier::class);
    }

    public function metadonnees()
    {
        return $this->hasMany(Metadonnee::class);
    }

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }
    public function validateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->hash_sha256 = hash('sha256', Str::random(20));
            $model->date_depot = now();
            $model->utilisateur_id = auth()->id() ?? 1; // Default to 1 if no user is authenticated
        });
        
    }
}
