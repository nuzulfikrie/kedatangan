<?php

namespace App\Models;

use Database\Seeders\SchoolsInstitutions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RemindersSendStatus extends Model
{
    use HasFactory;

    public static const STATUS_NOT_SEND =  'not_send';
    public static const STATUS_SEND = 'sent';
    public static const STATUS_PENDING  = 'pending';

    public static const STATUS_FAILURE = 'failure';


    protected $table = 'reminders';

    protected $fillable = [
        'child_id',
        'date',
        'reminder',
        'status',
    ];

    protected $primaryKey = 'id';

    protected $timestamps = true;

    public function reminder()
    {
        return $this->belongsTo(Reminders::class, 'reminder_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function school()
    {
        return $this->belongsTo(SchoolsInstitutions::class, 'school_id', 'id');
    }

    public static function createRecord(array $data)
    {
        return self::create($data);
    }

    public static function updateRecord(int $id, $data)
    {
        $record = self::find($id);
        $record->update($data);
        return $record;
    }

    public static function deleteRecord(int $id)
    {
        $record = self::find($id);
        $record->delete();
    }
}
