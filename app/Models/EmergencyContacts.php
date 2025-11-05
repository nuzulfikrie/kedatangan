<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyContacts extends Model
{
    use HasFactory;

    protected $table = 'emergency_contacts';
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'child_id',
        'name',
        'phone_number',
        'relationship',
        'picture_path',
        'email',
        'address',
    ];

    /**
     * The attributes that have default values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'picture_path' => 'default.png',
    ];

    /**
     * Get the child that the emergency contact belongs to.
     */
    public function child()
    {
        return $this->belongsTo(Childs::class, 'child_id');
    }

    /**
     * Get the parent that the emergency contact belongs to.
     */
    public function parent()
    {
        return $this->belongsTo(Parents::class, 'parent_id');
    }
}
