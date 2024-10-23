<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ParentCreateChildRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        //if user role is father or mother or admin , then he can create child

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'school_id' => 'required|exists:schools_institutions,id',
            'parent_id' => 'required|exists:parents,id',
            'child_name' => 'required|string|max:255',
            'dob' => 'required|date|before:today',
            'child_gender' => 'required|in:male,female',
            'email' => 'required|email|max:255',
        ];

        // Check if the file is present in the request
        if ($this->hasFile('picture')) {
            $rules['picture'] = 'required|image|mimes:jpeg,png,jpg,gif|max:2048'; // max file size 2MB
        }

        return $rules;
    }
}
