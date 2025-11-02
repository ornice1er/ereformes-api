<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Affectation extends Model

{

        use Filterable,HasFactory;

    protected $fillable = [
        'isLast',
        'reforme_id',
        'unite_admin_up',
        'unite_admin_down',
        'sens',
        'instruction',
        'delay'
    ];

    protected $casts = [
        'isLast' => 'boolean',
        'sens' => 'integer',
        'delay' => 'integer',
    ];

        private static $whiteListFilter = ['*'];


    // Relations
    public function reforme(): BelongsTo
    {
        return $this->belongsTo(Reforme::class);
    }

    // public function uniteAdminUp(): BelongsTo
    // {
    //     return $this->belongsTo(UniteAdmin::class, 'unite_admin_up');
    // }

    // public function uniteAdminDown(): BelongsTo
    // {
    //     return $this->belongsTo(UniteAdmin::class, 'unite_admin_down');
    // }

    // Accesseurs
    public function getSensLibelleAttribute(): string
    {
        return $this->sens === 1 ? 'Montant' : 'Descendant';
    }
}
