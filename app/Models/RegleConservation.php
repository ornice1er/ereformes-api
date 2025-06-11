<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class RegleConservation extends Model
{

        use Filterable, HasFactory;
    protected $fillable = [
        'nom',
        'description',
        'duree_annees', // en mois ou années selon ta convention
        'action_post_conservation' // ex: destruction, transfert, etc.
    ];

    public function dossiers()
    {
        return $this->hasMany(Dossier::class);
    }
}
