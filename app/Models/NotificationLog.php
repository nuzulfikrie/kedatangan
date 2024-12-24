<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationLog extends Model
{
    protected $fillable = [
        'notification_setting_id',
        'event_type',
        'payload',
        'status',
        'error_message'
    ];

    protected $casts = [
        'payload' => 'array'
    ];

    public function setting(): BelongsTo
    {
        return $this->belongsTo(NotificationSetting::class, 'notification_setting_id');
    }
}
