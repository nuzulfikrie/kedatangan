<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemindersTemplate extends Model
{
    use HasFactory;

    protected $table = 'reminders_template';

    protected $fillable = [
        'reminder',
        'active',
        'admin_id',
        'school_id',
        'language',
        'channel',
    ];

    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get the admin that created the reminder template.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the school associated with the reminder template.
     */
    public function school()
    {
        return $this->belongsTo(SchoolsInstitutions::class, 'school_id');
    }

    /**
     * Scope a query to only include active templates.
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope a query to filter by channel.
     */
    public function scopeChannel($query, $channel)
    {
        return $query->where('channel', $channel);
    }

    /**
     * Scope a query to filter by language.
     */
    public function scopeLanguage($query, $language)
    {
        return $query->where('language', $language);
    }

    /**
     * Get an active template for a specific channel and language.
     */
    public static function getActiveTemplate($channel, $language)
    {
        return static::active()
            ->channel($channel)
            ->language($language)
            ->inRandomOrder()
            ->first();
    }

    public static function deactivateAllTemplates()
    {
        return static::where('active', true)
            ->update(['active' => false]);
    }

    public static function activateTemplate(int $templateId)
    {
        return static::where('id', $templateId)
            ->update(['active' => true]);
    }

    public static function createTemplate(array $data)
    {
        return static::create($data);
    }

    public static function updateTemplate(int $templateId, array $data)
    {
        return static::where('id', $templateId)
            ->update($data);
    }

    public static function deleteTemplate(int $templateId)
    {
        return static::where('id', $templateId);
    }

    public static function wipeAllTemplates()
    {
        return static::truncate();
    }
}
