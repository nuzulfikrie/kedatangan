<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UserValidation implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        /*
        CREATE TABLE `users` (
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT, `name` varchar(255) NOT NULL, `email` varchar(255) NOT NULL, `email_verified_at` timestamp NULL DEFAULT NULL, `password` varchar(255) NOT NULL, `is_admin` tinyint(1) NOT NULL DEFAULT 0, `role` varchar(255) NOT NULL DEFAULT 'user', `two_factor_secret` text DEFAULT NULL, `two_factor_recovery_codes` text DEFAULT NULL, `two_factor_confirmed_at` timestamp NULL DEFAULT NULL, `remember_token` varchar(100) DEFAULT NULL, `current_team_id` bigint(20) unsigned DEFAULT NULL, `profile_photo_path` varchar(2048) DEFAULT NULL, `created_at` timestamp NULL DEFAULT NULL, `updated_at` timestamp NULL DEFAULT NULL, PRIMARY KEY (`id`), UNIQUE KEY `users_email_unique` (`email`)
) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci
        */

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'is_admin' => ['required', 'boolean'],
            'role' => ['required', 'string', 'max:255', 'options:user,admin,school_admin,teacher,student'],
            'email_verified_at' => ['nullable', 'date'],
            'two_factor_secret' => ['nullable', 'string'],
            'two_factor_recovery_codes' => ['nullable', 'string'],
            'two_factor_confirmed_at' => ['nullable', 'date'],
            'remember_token' => ['nullable', 'string', 'max:100'],
            'current_team_id' => ['nullable', 'integer'],
            'profile_photo_path' => ['nullable', 'string', 'max:2048'],
        ];
    }
}
/**
 * notes, to use this rule, you need to add this to the controller
 * use App\Rules\UserValidation;
 * 
 * then call it in the controller like this
 * 
 * $request->validate([
 *     'name' => ['required', 'string', 'max:255'],
 *    'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
 *   'password' => ['required', 'string', 'min:8'],
 * 'is_admin' => ['required', 'boolean'],
 * 'role' => ['required', 'string', 'max:255', 'options:user,admin,school_admin,teacher,student'],
 * 'email_verified_at' => ['nullable', 'date'],
 * 'two_factor_secret' => ['nullable', 'string'],
 * 'two_factor_recovery_codes' => ['nullable', 'string'],
 * 'two_factor_confirmed_at' => ['nullable', 'date'],
 * 'remember_token' => ['nullable', 'string', 'max:100'],
 * 'current_team_id' => ['nullable', 'integer'],
 * 'profile_photo_path' => ['nullable', 'string', 'max:2048'],
 * ]);
 * 
 */
