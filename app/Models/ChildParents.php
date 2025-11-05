<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ChildParents extends Pivot
{
    use HasFactory;

    protected $table = 'child_parents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'child_id',
        'parent_id',
        'active',
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
     * Get the child for the pivot.
     */
    public function child()
    {
        return $this->belongsTo(Childs::class, 'child_id');
    }

    /**
     * Get the parent for the pivot.
     */
    public function parent()
    {
        return $this->belongsTo(Parents::class, 'parent_id');
    }
}
