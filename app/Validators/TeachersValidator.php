<?php

declare(strict_types=1);

namespace App\Validators;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class TeachersValidators extends Validator
{
  /**
   * Validate the input.
   *
   * @param  array<string, string>  $input
   */
  public function validate(array $input): void
  {
    Validator::make($input, [
      'name' => ['required', 'string', 'max:255'],
      'school_id' => [
        'required',
        'integer',
        //It is bigint,
        'max:20',

        //school id must be valid school id from school_institution table
        Rule::exists('schools_institutions', 'id'),
      ],
      'picture_path' => ['required', 'string', 'max:255'],
      'specialization' => ['required', 'string', 'max:255'],
      'user_id' => ['required', 'integer', 'max:20', Rule::exists('users', 'id')],
    ])->validate();
  }

  //guide, to use this validator, use this code in controller
  // $validator = new TeachersValidators();
  // $validator->validate($request->all());
  // $teachers = Teachers::create($request->all());
}
// Compare this snippet from app/Actions/Fortify/ResetUserPassword.php: