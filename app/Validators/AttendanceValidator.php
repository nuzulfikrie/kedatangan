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
  public function validate(array $data, array $rules, array $messages = [], array $attributes = []): void
  {

    if (empty($rules)) {
      /**
       *     Validator::make($input, [
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

       */

      $rules = [
        'child_id' => [
          'required',
          'integer',
          Rule::unique('attendance')->where(function ($query) use ($data) {
            return $query->where('date', $data['date']);
          }),
          Rule::exists('childs', 'id'),
        ],
        'date' => ['required', 'date'],
        'status' => ['required', 'string', 'in:present,absent,late,unknown'],
      ];
    }

    if (empty($messages)) {

      $messages = [
        'child_id.required' => 'The child id field is required.',
        'child_id.integer' => 'The child id field must be an integer.',
        'child_id.unique' => 'The child id has already been taken for this date.',
        'child_id.exists' => 'The child id does not exist.',
        'date.required' => 'The date field is required.',
        'date.date' => 'The date field must be a date.',
        'status.required' => 'The status field is required.',
        'status.string' => 'The status field must be a string.',
        'status.in' => 'The selected status is invalid.',
      ];
    }
    $validator = Validator::make($data, $rules, $messages, $attributes);
    $validator->validate();
  }
}
