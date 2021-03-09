<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        if ($this->method() == 'POST') {
            return [
                'name' => 'required',
                'email' => 'required|email|unique:users,email',
                'username' => 'required|min:5|unique:users,username',
                'roles' => 'required',
                'password' => 'required|confirmed|min:8',
                'photo_file' => 'nullable|mimes:'.config('custom.mimes.photo.m'),
            ];
        } else {
            return [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.
                            $this->id,
                'username' => 'required|min:5|unique:users,username,'.
                            $this->id,
                'roles' => 'required',
                'password' => 'nullable|confirmed|min:8',
                'photo_file' => 'nullable|mimes:'.config('custom.mimes.photo.m'),
            ];
        }

    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'username' => 'Username',
            'roles' => 'Roles',
            'phone' => 'Phone',
            'password' => 'Password',
            'photo_file' => 'Photo',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute is required',
            'email.required' => ':attribute is required',
            'email.email' => ':attribute must be a valid email address',
            'email.unique' => ':attribute already exists',
            'username.required' => ':attribute is required',
            'username.min' => ':attribute minimal :min character',
            'username.unique' => ':attribute already exists',
            'roles.required' => ':attribute is required',
            'phone.numeric' => ':attribute must be type numeric',
            'phone.unique' => ':attribute already exists',
            'password.confirmed' => ':attribute is not the same as the '.
                                    'confirmation password',
            'password.min' => ':attribute minimal :min character',
            'photo_file.mimes' => ':attribute must be a file of type: :values.',
        ];
    }
}
