<?php

namespace App\Http\Requests\Users\ACL;

use Illuminate\Foundation\Http\FormRequest;

class PermissionRequest extends FormRequest
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
                'name' => 'required|string|unique:permissions,name',
            ];
        } else {
            return [
                'name' => 'required|string|unique:permissions,name,'.$this->id,
            ];
        }
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute is required',
            'name.string' => ':attribute must be a type string',
            'name.unique' => ':attribute already exists',
        ];
    }
}
