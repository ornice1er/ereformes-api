<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class Metadonnee extends Model
{
        use Filterable, HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'cle',
        'valeur',
        'document_id'
    ];

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
