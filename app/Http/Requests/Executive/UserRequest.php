<?php

namespace App\Http\Requests\Executive;

use Hashids\Hashids;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->option === 'credential') {
            return [
                'code' => 'required|string',
                'email' => 'required|email',
                'mobile' => 'nullable|string',
            ];
        }
        if ($this->option === 'avatar') {
            return [
                'id' => 'required|integer|exists:users,id',
                'image' => 'required|image|mimes:jpeg,png|max:2048',
            ];
        }
        if ($this->option === 'status' || $this->option === 'role') {
            return [
                'code' => 'sometimes|string',
                'id' => 'sometimes|integer|exists:users,id',
            ];
        }
        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'id' => 'required|integer|exists:users,id',
                'firstname' => 'required|string|max:150',
                'middlename' => 'nullable|string|max:150',
                'lastname' => 'required|string|max:150',
                'mobile' => 'nullable|string',
            ];
        }
        return [
            'username' => 'required|string|max:15|unique:users,username',
            'email' => 'required|email',
            'firstname' => 'required|string|max:150',
            'middlename' => 'nullable|string|max:150',
            'lastname' => 'required|string|max:150',
            'mobile' => 'nullable|string',
            'role_id' => 'nullable|exists:list_roles,id',
        ];
    }

    public function withValidator($validator)
    {
        if ($this->option !== 'credential' && $this->option !== 'status') {
            return;
        }

        $validator->after(function ($validator) {
            $hashids = new Hashids('krad', 10);
            $id = $hashids->decode($this->code)[0] ?? null;

            if (!$id) {
                $validator->errors()->add('code', 'Invalid code provided.');
                return;
            }

            $user = \App\Models\User::find($id);
            if (!$user) {
                $validator->errors()->add('code', 'User not found.');
                return;
            }

            if ($this->option === 'credential' && \App\Models\User::where('email', $this->email)->where('id', '<>', $id)->exists()) {
                $validator->errors()->add('email', 'The email has already been taken.');
            }

            $this->merge(['id' => $id]);
        });
    }
}
