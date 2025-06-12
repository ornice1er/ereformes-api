<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class SuiviResult extends Model
{
    use HasFactory, Filterable;
    protected $guarded = [];
    public $timestamps = false;
    protected $table="suivre_result";

    public function resultat(): BelongsTo
    {
        return $this->belongsTo(Result::class, 'result_id');
    }
}
