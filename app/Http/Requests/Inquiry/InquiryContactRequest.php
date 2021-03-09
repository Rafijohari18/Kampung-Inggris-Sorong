<?php

namespace App\Http\Requests\Inquiry;

use Illuminate\Foundation\Http\FormRequest;

class InquiryContactRequest extends FormRequest
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
            'name' => 'required',
            'email' => 'required|email',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'Title',
            'email' => 'Slug',
        ];
    }

    public function messages()
    {
        return [
            'name'.'.required' => ':attribute is required',
            'email.required' => ':attribute is required',
        ];
    }
}
