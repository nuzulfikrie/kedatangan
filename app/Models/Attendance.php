<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $table = 'attendance';

    protected $primaryKey = 'id';

    protected $fillable = [
        'child_id',
        'date',
        'status',
    ];

    protected $casts = [
        'child_id' => 'integer',
        'date' => 'date',
        'status' => 'string',
    ];

    // Enables the timestamps for created_at and updated_at columns
    public $timestamps = true;

    // Relationship to the Childs model
    public function childs()
    {
        return $this->belongsTo(Childs::class, 'child_id');
    }

    // List all attendance for a school
    public function listAllAttendanceForSchool(int $schoolId)
    {
        // Get all children IDs for a school
        $childs = Childs::where('school_id', $schoolId)->get();

        // Get all attendance for each child
        $attendance = [];
        foreach ($childs as $child) {
            $attendance[$child->id] = Attendance::where('child_id', $child->id)->get();
        }

        // Return all attendance for the school
        return $attendance;
    }

    // List all attendance for a child by ID
    public function listAllAttendanceForChild(int $childId)
    {
        // Get all attendance for the child
        return Attendance::where('child_id', $childId)->get();
    }

    /**
     * Create a record for a child for the day and mark the child as present
     *
     * @param int $childId
     * @return bool
     * @throws \Throwable
     */
    public static function createRecordsPresent(int $childId)
    {
        $attendance = new Attendance();
        $attendance->child_id = $childId;
        $attendance->date = date('Y-m-d');
        $attendance->status = 'present';
        return $attendance->saveOrFail();
    }

    /**
     * Update a record to mark a child as absent for the day
     *
     * @param int $childId
     * @return bool
     * @throws \Throwable
     */
    public static function updateRecordAbsent(int $childId)
    {
        $attendance = new Attendance();
        $attendance->child_id = $childId;
        $attendance->date = date('Y-m-d');
        $attendance->status = 'absent';
        return $attendance->saveOrFail();
    }

    /**
     * Delete a record for a child by date
     *
     * @param int $childId
     * @param string $date
     * @return bool
     */
    public static function deleteRecordByChildDate(int $childId, string $date)
    {
        $attendance = Attendance::where('child_id', $childId)->where('date', $date)->first();
        if ($attendance) {
            return $attendance->delete();
        }
        return false;
    }

    /**
     * Wipe all records for a specific child
     *
     * @param int $childId
     * @return int
     */
    public static function wipeRecordsByChildId(int $childId)
    {
        return Attendance::where('child_id', $childId)->delete();
    }

    /**
     * Utility method to wipe all attendance records (for admin use only)
     *
     * @return bool|null
     */
    public static function wipe()
    {
        return Attendance::truncate();
    }
}
