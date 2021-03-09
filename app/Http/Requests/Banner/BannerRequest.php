<?php

namespace App\Http\Requests\Banner;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
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
        if ((bool)$this->from_youtube == 1) {

            return [
                'youtube_id' => 'required',
            ];

        } elseif((bool)$this->is_video == 0 && (bool)$this->from_youtube == 0) {

            return [
                'file' => 'required|mimes:'.config('custom.mimes.banner.m'),
            ];

        } elseif ((bool)$this->is_video == 1 && (bool)$this->from_youtube == 0) {

            return [
                'file' => 'required|mimes:'.config('custom.mimes.banner.mv'),
            ];

        } else {

            return [
                'file' => 'required|mimes:'.config('custom.mimes.banner.m'),
            ];
        }
    }

    public function attributes()
    {
        return [
            'file' => 'File',
            'youtube_id' => 'Youtube ID'
        ];
    }

    public function messages()
    {
        return [
            'file.required' => ':attribute is required',
            'file.mimes' => ':attribute must be a file of type: :values.',
            'youtube_id.required' => ':attribute is required',
        ];
    }
}
