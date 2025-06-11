<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Dossier extends Model
{
        use Filterable, HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'regle_conservation_id',
        'cree_par' // ID de l'utilisateur ou archiviste créateur
    ];

    protected $casts = [
        'types_autorises' => 'array',
    ];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function regleConservation()
    {
        return $this->belongsTo(RegleConservation::class);
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'cree_par');
    }

    public function dossierParent()
    {
        return $this->belongsTo(Dossier::class, 'parent_id');
    }

    public function buildPath()
    {
        $segments = [];
        $current = $this;

        while ($current) {
            $segments[] = $current->nom;
            $current = $current->parent;
        }

        return implode('/', array_reverse($segments));
    }

    public function children()
    {
        return $this->hasMany(Dossier::class, 'parent_id');
    }

    public function updateEmplacementRecursif()
    {
        $this->emplacement = $this->buildPath();
        $this->save();

        foreach ($this->children as $child) {
            $child->updateEmplacementRecursif();
        }
    }
}
