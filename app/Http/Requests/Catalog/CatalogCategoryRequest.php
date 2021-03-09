<?php

namespace App\Http\Requests\Catalog;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class CatalogCategoryRequest extends FormRequest
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
            'name_'.App::getLocale() => 'required',
            'slug' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'name_'.App::getLocale() => 'Name',
            'slug' => 'Slug',
        ];
    }

    public function messages()
    {
        return [
            'name_'.App::getLocale().'.required' => ':attribute is required',
            'slug.required' => ':attribute is required',
        ];
    }
}
