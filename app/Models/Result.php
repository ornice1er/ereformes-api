<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory, Filterable;
    protected $guarded = [];
    public $timestamps = false;
    private static $whiteListFilter = ['*'];

    public function getLastSuiviResult()
    {

       return $this->hasOne(SuiviResult::class,'result_id');

    }

    public function suiviResults()
    {

       return $this->hasMany(SuiviResult::class,'result_id');

    }

    public function objectif()
    {
       return $this->belongsTo(Objectif::class,"objectif_id");
    }


}
