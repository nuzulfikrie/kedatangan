<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Childs extends Model
{
    use HasFactory;

    protected $table = 'childs';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'school_id',
        'child_name',
        'child_gender',
        'email',
        'picture_path',
    ];

    /**
     * Get the school institution that the child belongs to.
     */
    public function schoolsinstitution()
    {
        return $this->belongsTo(Schoolsinstitutions::class, 'school_id');
    }

    /**
     * The parents that belong to the child.
     */
    public function parents()
    {
        return $this->belongsToMany(Parents::class, 'child_parents', 'child_id', 'parent_id')
                    ->withPivot('active')
                    ->withTimestamps();
    }

    /**
     * Get the attendance records for the child.
     */
    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'child_id');
    }

    /**
     * Get the non-attendance records for the child.
     */
    public function nonattendance()
    {
        return $this->hasMany(Nonattendance::class, 'child_id');
    }

    /**
     * Get the reminders for the child.
     */
    public function reminders()
    {
        return $this->hasMany(Reminders::class, 'child_id');
    }

    /**
     * Get the emergency contacts for the child.
     */
    public function emergencyContacts()
    {
        return $this->hasMany(EmergencyContacts::class, 'child_id');
    }

    /**
     * Get the classes for the child.
     */
    public function classes()
    {
        return $this->hasMany(Classes::class, 'child_id');
    }

    /**
     * Get the unknown attendance records for the child.
     */
    public function unknowns()
    {
        return $this->hasMany(Unknowns::class, 'child_id');
    }
}
