<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nature extends Model
{
    use HasFactory, Filterable;
    protected $guarded = [];
    public $timestamps = false;
    private static $whiteListFilter = ['*'];

}
