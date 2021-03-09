<?php

namespace App\Http\Requests\Inquiry;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class InquiryRequest extends FormRequest
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
                'name_'.App::getLocale() => 'required',
                'slug' => 'required|unique:inquiries,slug',
                'body_'.App::getLocale() => 'required',
            ];

        } else {
            return [
                'name_'.App::getLocale() => 'required',
                'slug' => 'required|unique:inquiries,slug,'.$this->id,
                'body_'.App::getLocale() => 'required',
            ];
        }
    }

    public function attributes()
    {
        return [
            'name_'.App::getLocale() => 'Title',
            'slug' => 'Slug',
            'body_'.App::getLocale() => 'Body',
        ];
    }

    public function messages()
    {
        return [
            'name_'.App::getLocale().'.required' => ':attribute is required',
            'slug.required' => ':attribute is required',
            'slug.unique' => ':attribute already exists',
            'body_'.App::getLocale().'.required' => ':attribute is required',
        ];
    }
}
