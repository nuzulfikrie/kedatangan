<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class Childs extends Model
{
    use HasFactory;

    protected $table = 'childs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'school_id',
        'child_name',
        'dob',
        'child_gender',
        'email',
        'picture_path'
    ];

    protected $cast = [];

    protected $timestamp = true;

    public static function createRecord($data)
    {
        return self::create($data);
    }

    public static function updateRecord(int $childId, $data)
    {
        $child = self::find($childId);
        $child->update($data);

        return $child;
    }
    public function childParents()
    {
        return $this->hasMany(ChildParents::class, 'child_id', 'id');
    }



    public function age()
    {
        if (!$this->dob) {
            return 'Date of birth not set';
        }

        $dob = Carbon::createFromFormat('Y-m-d', $this->dob);
        $now = Carbon::now();

        // Get the precise difference in years, months, and days
        $diff = $dob->diff($now);

        $years = $diff->y;
        $months = $diff->m;
        $days = $diff->d;

        return $this->formatAge($years, $months, $days);
    }

    private function formatAge($years, $months, $days)
    {
        $ageParts = [];

        if ($years > 0) {
            $ageParts[] = "$years year" . ($years > 1 ? 's' : '');
        }

        if ($months > 0) {
            $ageParts[] = "$months month" . ($months > 1 ? 's' : '');
        }

        if ($days > 0) {
            $ageParts[] = "$days day" . ($days > 1 ? 's' : '');
        }



        return implode(', ', $ageParts);
    }
    public function school()
    {
        return $this->belongsTo(SchoolsInstitutions::class, 'school_id', 'id');
        //how to access this - $child->school->name
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class, 'child_id');
    }

    public function nonattendace()
    {
        return $this->hasMany(Nonattendance::class, 'child_id');
    }

    public function unknowns()
    {
        return $this->hasMany(Unknowns::class, 'child_id');
    }

    public function emergencyContacts()
    {
        return $this->hasMany(EmergencyContacts::class, 'child_id');
    }

    public static function deleteChild(int $childId)
    {
        try {
            DB::beginTransaction();
            $child = self::find($childId);
            $child->childParents()->delete();
            $child->emergencyContacts()->delete();
            $child->attendance()->delete();
            $child->nonattendace()->delete();
            $child->unknowns()->delete();

            $child->delete();

            DB::commit();

            return [
                'message' => 'delete success',
                'code' => 200,
            ];
        } catch (Exception $e) {
            //throw $th;
            DB::rollBack();


            return [
                'message' => 'delete failed',
                'code' => 500,
            ];
        }
    }

    public static function wipe()
    {
        self::truncate();

        return true;
    }
}
