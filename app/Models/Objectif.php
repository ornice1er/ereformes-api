<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objectif extends Model
{
    use HasFactory, Filterable;
    protected $guarded = [];
    public $timestamps = false;
    private static $whiteListFilter = ['*'];

    public function reforme()
    {
       return $this->belongsTo(Reforme::class,"reforme_id");
    }
    public function results()
    {
       return $this->hasMany(Result::class,"objectif_id");
    }


}
