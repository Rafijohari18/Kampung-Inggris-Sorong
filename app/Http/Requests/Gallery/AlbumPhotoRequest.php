<?php

namespace App\Http\Requests\Gallery;

use Illuminate\Foundation\Http\FormRequest;

class AlbumPhotoRequest extends FormRequest
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
            'file' => 'required|mimes:'.config('custom.mimes.gallery_photo.m'),
        ];
    }

    public function attributes()
    {
        return [
            'file' => 'File',
        ];
    }

    public function messages()
    {
        return [
            'file.required' => ':attribute is required',
            'file.mimes' => ':attribute must be a file of type: :values.',
        ];
    }
}
