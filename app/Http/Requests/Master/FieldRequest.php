<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class FieldRequest extends FormRequest
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
                'label.*' => 'required',
                'name.*' => 'required',
                'type.*' => 'required',
            ];
        } else {
            return [
                'label' => 'required',
                'name' => 'required',
                'type' => 'required',
            ];
        }
    }

    public function attributes()
    {
        return [
            'label.*' => 'Label',
            'name.*' => 'Name',
            'type.*' => 'type',
            'label' => 'Label',
            'name' => 'Name',
            'type' => 'type',
        ];
    }

    public function messages()
    {
        return [
            'label.*.required' => ':attribute is required',
            'name.*.required' => ':attribute is required',
            'type.*.required' => ':attribute is required',
            'label.required' => ':attribute is required',
            'name.required' => ':attribute is required',
            'type.required' => ':attribute is required',
        ];
    }
}
