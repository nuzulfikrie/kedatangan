<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class NotificationSetting extends Model
{
    protected $fillable = [
        'user_id',
        'service_name',
        'settings',
        'is_active',
        'last_notification_at',
        'last_error_at',
        'last_error_message'
    ];

    protected $casts = [
        'settings' => 'array',
        'is_active' => 'boolean',
        'last_notification_at' => 'datetime',
        'last_error_at' => 'datetime'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(NotificationLog::class);
    }
}
