<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReformeNotification extends Model
{
    use HasFactory,Filterable;

    protected $fillable = [
        'reforme_id',
        'notification_type',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];
        private static $whiteListFilter = ['*'];


    public function reforme(): BelongsTo
    {
        return $this->belongsTo(Reforme::class);
    }
}
