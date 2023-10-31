<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teachers extends Model
{
    use HasFactory;

    protected $table = 'teachers';
    protected $primaryKey = 'id';

    public function schoolsinstitutions()
    {
        return $this->belongsTo(Schoolsinstitutions::class);
    }

    public function users()
    {
        return $this->belongsTo(User::class);
    }
}
