<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;
    protected $guarded = [];
    public $timestamps = false;
    protected $table="resultat";

    public function getLastSuiviResult()
    {
       
       return $this->hasOne(SuiviResult::class,'resultat_id');

    }

    public function suiviResults()
    {
       
       return $this->hasMany(SuiviResult::class,'resultat_id');

    }

    public function objectif()
    {
       return $this->belongsTo(Objectif::class,"objectif_id");
    }


}
