<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Parents extends Model
{
    use HasFactory;

    protected $table = 'parents';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'parent_name',
        'phone_number',
        'email',
        'picture_path'
    ];

    public $timestamps = true;

    public function childs()
    {
        return $this->hasMany(
            ChildParents::class,
            'parent_id',
            'id'
        );
    }

    public function childrensData()
    {
        return $this->hasManyThrough(
            Childs::class,
            ChildParents::class,
            'parent_id',
            'id',
            'id',
            'child_id'
        );
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function emergencyContacts()
    {
        return $this->hasManyThrough(EmergencyContacts::class, Childs::class, 'parent_id', 'child_id', 'id', 'id');
    }

    // Finder methods
    public static function getByChildId(int $childId)
    {
        return self::whereHas('childs', function ($query) use ($childId) {
            $query->where('id', $childId);
        })->get();
    }

    public static function createParent(array $data)
    {
        return self::create($data);
    }

    public static function updateParent(int $parentId, array $data)
    {
        $parent = self::findOrFail($parentId);
        $parent->update($data);
        return $parent;
    }

    public static function deleteParent(int $parentId)
    {
        //Delete parent will delete child and emergency contact
        try {
            //code...
            DB::beginTransaction();

            $childs = Childs::where('parent_id', $parentId)->get();

            foreach ($childs as $c) {
                Childs::deleteChild($c->id);
            }

            $emergencyContacts = EmergencyContacts::where('parent_id', $parentId)->get();

            foreach ($emergencyContacts as $ec) {
                EmergencyContacts::deleteRecord($ec->id);
            }

            self::where('id', $parentId)->delete();

            DB::commit();

            return [
                'message' => 'Success delete parent',
                'code' => 200
            ];
        } catch (Exception $e) {
            //throw $th;

            DB::rollBack();

            Log::error(
                'Error deleting parent ' . $parentId  . ' message ' . $e->getMessage()
            );

            return [
                'message' =>  'Error deleting parent',
                'code' => 500,
            ];
        }
    }
}
