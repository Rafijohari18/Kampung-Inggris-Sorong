<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginBackendRequest extends FormRequest
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
        return [
            'username' => 'required|string|min:5
                        |exists:users,'.$this->loginType().',active,1',
            'password' => 'required|string|min:8',
        ];
    }

    public function attributes()
    {
        return [
            'username' => 'Username',
            'password' => 'Password'
        ];
    }

    public function messages()
    {
        return [
            'username.required' => ':attribute is required',
            'username.exists' => 'The Account you are trying to login is not '.
                                'registered or it has been disabled',
            'username.min' => ':attribute minimal :min character',
            'password.required' => ':attribute is required',
            'password.min' => ':attribute minimal :min character',
        ];
    }

    public function forms()
    {
        return [
            $this->loginType() => $this->username,
            'password' => $this->password,
        ];
    }

    private function loginType()
    {

        $loginType = filter_var($this->username, FILTER_VALIDATE_EMAIL) ?
                    'email' : 'username';

        return $loginType;
    }
}
