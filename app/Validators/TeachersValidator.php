<?php

declare(strict_types=1);

namespace App\Validators;

use Illuminate\Support\Facades\Validator;

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
      'school_id' => ['required', 'integer', 'max:255'],
      'user_id' => ['required', 'integer', 'max:255'],
    ])->validate();
  }

  //guide, to use this validator, use this code in controller
  // $validator = new TeachersValidators();
  // $validator->validate($request->all());
  // $teachers = Teachers::create($request->all());
}
// Compare this snippet from app/Actions/Fortify/ResetUserPassword.php: