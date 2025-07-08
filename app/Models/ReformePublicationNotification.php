<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReformePublicationNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'reforme_id',
        'sent_at',
        'recipients',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'recipients' => 'array',
    ];

    public function reforme(): BelongsTo
    {
        return $this->belongsTo(Reforme::class);
    }
}
