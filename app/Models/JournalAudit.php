<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'action',
        'utilisateur_id',
        'document_id',
        'date_action',
        'details',
    ];

    public function utilisateur()
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    public function document()
    {
        return $this->belongsTo(Document::class);
    }
}
