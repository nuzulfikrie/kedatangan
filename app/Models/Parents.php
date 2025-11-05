<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parents extends Model
{
    use HasFactory;

    protected $table = 'parents';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'parent_name',
        'phone_number',
        'email',
        'picture_path',
    ];

    /**
     * Get the user that owns the parent profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The children that belong to the parent.
     */
    public function childs()
    {
        return $this->belongsToMany(Childs::class, 'child_parents', 'parent_id', 'child_id')
                    ->withPivot('active')
                    ->withTimestamps();
    }

    /**
     * Get the emergency contacts for the parent.
     */
    public function emergencyContacts()
    {
        return $this->hasMany(EmergencyContacts::class, 'parent_id');
    }
}
