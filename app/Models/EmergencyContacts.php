<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyContacts extends Model
{
    use HasFactory;

    protected $table = 'emergency_contacts';
    protected $primaryKey = 'id';

    protected $attributes = [
        'picture_path' => 'default.png',
    ];

    /**
     *        Schema::create('emergency_contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained(
                table: 'parents',
                indexName: 'emergency_contact_parent_id'
            );


            $table->foreignId('child_id')->constrained(
                table: 'childs',
                indexName: 'classchild_child_id'
            );
            $table->string('name')->nullable(false)->length(255);
            $table->string('phone_number')->nullable(false)->length(15);
            $table->string('relationship')->nullable(false)->length(50);
            $table->string('picture_path')->nullable(false)->length(255);
            $table->string('email')->nullable(false)->length(255)->unique();
            //address
            $table->string('address')->nullable(false)->length(500);
            $table->timestamps();
        });
     */

    public function childs()
    {
        return $this->belongsTo(Childs::class);
    }

    public function parents()
    {
        return $this->belongsTo(Parents::class);
    }
}
