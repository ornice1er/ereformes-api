<?php

namespace App\Models;

use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reforme extends Model
{
    use HasFactory, Filterable;
    protected $guarded = [];
    public $timestamps = false;
        private static $whiteListFilter = ['*'];


    public function affectations()
    {
       return $this->hasMany(Affectation::class,"reforme_id");
    }
    public function affectation()
    {
       return $this->hasOne(Affectation::class,"reforme_id")->orderBy('id','desc')->where("isLast",true)->where("instruction","!=",null)->take(1);
    }
    public function nature()
    {
       return $this->belongsTo(Nature::class,"nature_id");
    }

    public function structure()
    {
       return $this->belongsTo(Structure::class,"structure_id");
    }

    public function objectifs()
    {
       return $this->hasMany(Objectif::class,"reforme_id");
    }

    public function files()
    {
       return $this->hasMany(Media::class,"projets_media_id");
    }

    public function notifications()
    {
        return $this->hasMany(ReformeNotification::class);
    }

    public function publicationNotifications()
    {
        return $this->hasMany(ReformePublicationNotification::class);
    }

}
