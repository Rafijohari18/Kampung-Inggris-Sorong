<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class TemplateViewRequest extends FormRequest
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
                'module' => 'required',
                'type' => 'required',
                'filename' => 'required',
            ];

        } else {

            return [
                'name' => 'required',
            ];

        }
    }

    public function attributes()
    {
        return [
            'name' => 'Name',
            'module' => 'Module',
            'type' => 'Type',
            'filename' => 'Filename',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => ':attribute is required',
            'module.required' => ':attribute is required',
            'type.required' => ':attribute is required',
            'filename.required' => ':attribute is required',
        ];
    }
}
