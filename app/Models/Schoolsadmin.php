<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schoolsadmin extends Model
{
    use HasFactory;

    protected $table = 'schools_admin';

    protected $primaryKey = 'id';

    protected $attributes = [];

    public function schools()
    {
        return $this->hasMany(Schoolsinstitutions::class, 'school_admin_id');
    }

    public function createRecords(array $dataFromRequest)
    {
        $schoolAdmin = new Schoolsadmin();
        $schoolAdmin->school_id = $dataFromRequest['school_id'];
        $schoolAdmin->school_admin_id = $dataFromRequest['school_admin_id'];
        return $schoolAdmin->saveOrFail();
    }

    public function updateRecords(array $dataFromRequest)
    {
        $schoolAdmin = Schoolsadmin::find($dataFromRequest['id']);
        $schoolAdmin->school_id = $dataFromRequest['school_id'];
        $schoolAdmin->school_admin_id = $dataFromRequest['school_admin_id'];
        return $schoolAdmin->saveOrFail();
    }

    public function deleteRecords(int $id)
    {
        $schoolAdmin = Schoolsadmin::find($id);
        return $schoolAdmin->delete();
    }
}
