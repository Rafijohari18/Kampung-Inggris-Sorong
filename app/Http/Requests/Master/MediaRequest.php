<?php

namespace App\Http\Requests\Master;

use Illuminate\Foundation\Http\FormRequest;

class MediaRequest extends FormRequest
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
        if ((bool)$this->is_youtube == 1) {

            return [
                'youtube_id' => 'required',
            ];

        } else {
            return [
                'filename' => 'required',
            ];
        }
    }

    public function attributes()
    {
        return [
            'filename' => 'File',
            'youtube_id' => 'Youtube ID',
        ];
    }

    public function messages()
    {
        return [
            'filename.required' => ':attribute is required',
            'youtube_id.required' => ':attribute is required',
        ];
    }
}
