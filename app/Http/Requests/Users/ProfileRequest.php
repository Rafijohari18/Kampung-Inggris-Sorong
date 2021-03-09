<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
        if ($this->password != '') {
            return [
                'name' => 'required',
                'email' => 'required|email|unique:users,email,'.
                            auth()->user()->id,
                'username' => 'required|min:5|unique:users,username,'.
                            auth()->user()->id,
                'current_password' => 'required|min:8',
                'password' => 'nullable|confirmed|min:8',
            ];
        } else {
            return [
                'name' => 'required|min:5',
                'email' => 'required|email|unique:users,email,'.
                            auth()->user()->id,
                'username' => 'required|unique:users,username,'.
                            auth()->user()->id,
            ];
        }
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'username' => 'Username',
            'current_password' => 'Current Password',
            'password' => 'Password',
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
            'current_password.required' => ':attribute is required',
            'current_password.min' => ':attribute minimal :min character',
            'password.confirmed' => ':attribute is not the same as the '.
                                    'confirmation password',
            'password.min' => ':attribute minimal :min character',
        ];
    }
}
