<?php

namespace App\Http\Requests\Banner;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class BannerCategoryRequest extends FormRequest
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
        ];
    }

    public function attributes()
    {
        return [
            'name_'.App::getLocale() => 'Name',
        ];
    }

    public function messages()
    {
        return [
            'name_'.App::getLocale().'.required' => ':attribute is required',
        ];
    }
}
