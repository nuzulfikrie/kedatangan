<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemindersTemplate extends Model
{
    use HasFactory;

    protected $table = 'reminders_template';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reminder',
        'active',
        'admin_id',
        'school_id',
        'language',
        'channel',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'active' => 'boolean',
    ];

    /**
     * Get the admin user that created the template.
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Get the school institution for the template.
     */
    public function schoolsinstitution()
    {
        return $this->belongsTo(Schoolsinstitutions::class, 'school_id');
    }
}
