<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderSendStatus extends Model
{
    use HasFactory;

    protected $table = 'reminder_send_statuses';
    protected $fillable = ['reminder_id', 'user_id', 'school_id', 'status', 'sent_at'];
    protected $guarded = ['id'];


    public const STATUS_PENDING = 0;
    public const STATUS_SENT = 1;
    public const STATUS_FAILED = 2;
    public const STATUS_DELIVERED = 3;
    public const STATUS_RETRY = 4;

    public function reminder()
    {
        return $this->belongsTo(Reminders::class);
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

    public static function updateRecord(int $id, array $data)
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

    public static function reminderSentSuccessfully(int $reminderId, int $userId, int $schoolId)
    {
        return self::createRecord([
            'reminder_id' => $reminderId,
            'user_id' => $userId,
            'school_id' => $schoolId,
            'status' => self::STATUS_SENT,
            'sent_at' => now(),
        ]);
    }

    public static function updateReminderSentStatus(int $reminderId, int $userId, int $schoolId, int $status)
    {
        return self::updateRecord($reminderId, [
            'status' => $status,
            'sent_at' => now(),
        ]);
    }

    public static function reminderSentFailed(int $reminderId, int $userId, int $schoolId)
    {
        return self::createRecord([
            'reminder_id' => $reminderId,
            'user_id' => $userId,
            'school_id' => $schoolId,
            'status' => self::STATUS_FAILED,
            'sent_at' => now(),
        ]);
    }

    public static function updateReminderSentFailedStatus(int $reminderId, int $userId, int $schoolId)
    {
        return self::updateRecord($reminderId, [
            'status' => self::STATUS_FAILED,
            'sent_at' => now(),
        ]);
    }

    public static function reminderSentDelivered(int $reminderId, int $userId, int $schoolId)
    {
        return self::createRecord([
            'reminder_id' => $reminderId,
            'user_id' => $userId,
            'school_id' => $schoolId,
            'status' => self::STATUS_DELIVERED,
            'sent_at' => now(),
        ]);
    }

    public static function updateReminderSentDeliveredStatus(int $reminderId, int $userId, int $schoolId)
    {
        return self::updateRecord($reminderId, [
            'status' => self::STATUS_DELIVERED,
            'sent_at' => now(),
        ]);
    }

    public static function reminderSentRetry(int $reminderId, int $userId, int $schoolId)
    {
        return self::createRecord([
            'reminder_id' => $reminderId,
            'user_id' => $userId,
            'school_id' => $schoolId,
            'status' => self::STATUS_RETRY,
            'sent_at' => now(),
        ]);
    }

    public static function updateReminderSentRetryStatus(int $reminderId, int $userId, int $schoolId)
    {
        return self::updateRecord($reminderId, [
            'status' => self::STATUS_RETRY,
            'sent_at' => now(),
        ]);
    }

    public static function getReminderStatus(int $reminderId, int $userId, int $schoolId)
    {
        return self::where('reminder_id', $reminderId)
            ->where('user_id', $userId)
            ->where('school_id', $schoolId)
            ->first();
    }

    public static function getReminderStatusBySchool(int $schoolId)
    {
        return self::where('school_id', $schoolId)
            ->get();
    }
}
