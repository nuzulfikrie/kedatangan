<?php

declare(strict_types=1);

namespace App\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AttendanceValidator extends Validator
{
  /*
          Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('child_id')->constrained(
                table: 'childs',
                indexName: 'attendance_child_id'
            );
            $table->date('date')->nullable(false);
            $table->string('status')->nullable(false)->length(50);
            $table->timestamps();
        });*/

  public function validate(array $input): void
  {
    Validator::make($input, [
      'child_id' => [
        'required',
        'integer',
        Rule::unique('attendance')->where(function ($query) use ($input) {
          return $query->where('date', $input['date']);
        }),
        Rule::exists('childs', 'id'),
      ],
      'date' => ['required', 'date'],
      'status' => ['required', 'string', 'in:present,absent,late,unknown'],
    ])->validate();
  }
}
