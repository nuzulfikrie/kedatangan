<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schoolsadmin extends Model
{
    use HasFactory;

    protected $table = 'schools_admin';

    protected $primaryKey = 'id';



    protected $attributes = [];

    public function schools()
    {
        return $this->belongsTo(Schoolsinstitutions::class, 'school_id', 'id');
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

    public function restoreRecords(int $id)
    {
        $schoolAdmin = Schoolsadmin::withTrashed()->find($id);
        return $schoolAdmin->restore();
    }

    public static function isNotEmpty(int $adminId)
    {
        $data = self::where('school_admin_id', '=', $adminId)->get()->toArray();

        return !empty($data);
    }
}
