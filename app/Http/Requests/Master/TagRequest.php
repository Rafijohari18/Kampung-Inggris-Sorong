<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
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
                'name' => 'required|unique:tags,name',
            ];
        } else {
            return [
                'name' => 'required|unique:tags,name,'.$this->id,
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
            'name.unique' => ':attribute already exists',
        ];
    }
}
