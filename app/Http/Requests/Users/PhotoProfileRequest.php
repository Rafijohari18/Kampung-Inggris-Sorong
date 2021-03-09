<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class PhotoProfileRequest extends FormRequest
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
            'photo' => 'nullable|mimes:'.config('custom.mimes.photo.m'),
        ];
    }

    public function attributes()
    {
        return [
            'photo' => 'Photo',
        ];
    }

    public function messages()
    {
        return [
            'photo.mimes' => ':attribute must be a file of type: :values.',
        ];
    }
}
