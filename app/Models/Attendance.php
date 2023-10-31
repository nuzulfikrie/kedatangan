<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $primaryKey = 'id';

    //table relation - to childs table via child_id
    public function childs()
    {
        return $this->belongsTo(Childs::class, 'child_id');
    }

    //list all attendance for a school
    public function listAllAttendanceForSchool(int $schoolId)
    {
        //1 - get all choilds id for a school
        $childs = Childs::where('school_id', $schoolId)->get();

        //2 - get all attendance for a child

        foreach ($childs as $child) {
            $attendance[$child->id] = Attendance::where('child_id', $child->id)->get();
        }

        //3 - return all attendance for a school

        return $attendance;
    }

    //list all attendance for a child by id
    public function listAllAttendanceForChild(int $childId)
    {
        //1 - get all attendance for a child
        $attendance = Attendance::where('child_id', $childId)->get();

        //2 - return all attendance for a child

        return $attendance;
    }

    /**
     * This function will create a record for a child for the day and mark the child as present
     *
     * @param  int $childId
     * @return bool
     * @throws \Throwable - if save fails
     */
    public function createRecordsPresent(int $childId)
    {
        $attendance = new Attendance();
        $attendance->child_id = $childId;
        $attendance->date = date('Y-m-d');
        $attendance->status = 'present';
        return $attendance->saveOrFail();
    }
}
