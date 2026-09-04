<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'firstname' => 'required|string|max:150',
            'middlename' => 'nullable|string|max:150',
            'lastname' => 'required|string|max:150',
            'mobile' => 'nullable|numeric|digits:11',
        ];
    }
}
