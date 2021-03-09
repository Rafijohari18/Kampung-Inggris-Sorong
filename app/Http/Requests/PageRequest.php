<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class PageRequest extends FormRequest
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
            'title_'.App::getLocale() => 'required',
            'slug' => 'required',
        ];
    }

    public function attributes()
    {
        return [
            'title_'.App::getLocale() => 'Title',
            'slug' => 'Slug',
        ];
    }

    public function messages()
    {
        return [
            'title_'.App::getLocale().'.required' => ':attribute is required',
            'slug.required' => ':attribute is required',
        ];
    }
}
