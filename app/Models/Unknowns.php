<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Unknowns extends Model
{
    use HasFactory;

    protected $table = 'unknowns';

    protected $primaryKey = 'id';

    protected $fillable = [
        'child_id',
        'date',
    ];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $childId = $model->child_id;
            $date = $model->date;

            // Check if a record exists in attendance or nonattendance for the same child on the same day
            $attendanceExists = Attendance::where('child_id', $childId)->where('date', $date)->exists();
            $nonAttendanceExists = Nonattendance::where('child_id', $childId)->where('date', $date)->exists();

            if ($attendanceExists || $nonAttendanceExists) {
                throw new ModelNotFoundException('A record already exists for this child on the same day in either attendance or nonattendance tables.');
            }
        });
    }

    public function child()
    {
        return $this->belongsTo(Childs::class, 'child_id', 'id');
    }

    public static function createRecord($childId, $date)
    {
        $unknown = new Unknowns();
        $unknown->child_id = $childId;
        $unknown->date = $date;
        $unknown->save();
    }

    public static function getUnknownByDate($date)
    {
        return self::where('date', $date)->get();
    }

    public static function deleteRecordByChildByDate($childId, $date)
    {
        $unknown = Unknowns::where('child_id', $childId)->where('date', $date)->first();
        $unknown->delete();
    }

    public static function deleteRecord(int $id)
    {
        $record = self::findOrFail($id);
        $record->delete();
    }

    public static function wipe()
    {
        self::truncate();
        return true;
    }
}
