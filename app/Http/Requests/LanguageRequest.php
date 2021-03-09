<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LanguageRequest extends FormRequest
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
                'iso_codes' => 'required|max:5|unique:languages,iso_codes',
                'country' => 'required',
            ];
        } else {
            return [
                'iso_codes' => 'required|max:5|unique:languages,iso_codes,'.$this->id,
                'country' => 'required',
            ];
        }
    }

    public function attributes()
    {
        return [
            'iso_codes' => 'Iso Code',
            'country' => 'Country',
        ];
    }

    public function messages()
    {
        return [
            'iso_codes.required' => ':attribute is required',
            'iso_codes.max' => ':attribute maximal :max character',
            'iso_codes.unique' => ':attribute already exists',
            'country.required' => ':attribute is required',
        ];
    }
}
