<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;

class Couverture extends Model
{
    use Filterable, HasFactory;

    protected $guarded = [];

    // ✅ Propriété correcte pour EloquentFilter
    protected $whiteListFilterKeys = [
        'lib_couvert',
    ];

    public $timestamps = false;

    protected $table = "couverture";
}
