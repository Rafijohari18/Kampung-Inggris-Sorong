<?php

namespace App\Http\Requests\Catalog;

use Illuminate\Foundation\Http\FormRequest;

class CatalogProductImageRequest extends FormRequest
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
                'file' => 'required|mimes:'.config('custom.mimes.product_image.m').','.config('custom.mimes.product_image.mv'),
                'thumbnail' => 'nullable|mimes:'.config('custom.mimes.product_image.m'),
            ];

        } else {

            return [
                'thumbnail' => 'nullable|mimes:'.config('custom.mimes.product_image.m'),
            ];
        }

    }

    public function attributes()
    {
        return [
            'file' => 'File',
            'thumbnail' => 'Thumbnail'
        ];
    }

    public function messages()
    {
        return [
            'file.required' => ':attribute is required',
            'file.mimes' => ':attribute must be a file of type: :values.',
            'thumbnail.mimes' => ':attribute must be a file of type: :values.',
        ];
    }
}
