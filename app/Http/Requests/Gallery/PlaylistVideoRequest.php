<?php

namespace App\Http\Requests\Gallery;

use Illuminate\Foundation\Http\FormRequest;

class PlaylistVideoRequest extends FormRequest
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

            if ((bool)$this->from_youtube == 1) {

                return [
                    'youtube_id' => 'required',
                ];

            } elseif ((bool)$this->from_youtube == 0) {

                return [
                    'file' => 'required|mimes:'.config('custom.mimes.gallery_video.m'),
                    'thumbnail' => 'nullable|mimes:'.config('custom.mimes.gallery_video.mt'),
                ];

            } else {

                return [
                    'file' => 'required|mimes:'.config('custom.mimes.gallery_video.m'),
                    'thumbnail' => 'nullable|mimes:'.config('custom.mimes.gallery_video.mt'),
                ];

            }
        } else {
            return [
                'thumbnail' => 'nullable|mimes:'.config('custom.mimes.gallery_video.mt'),
            ];
        }
    }

    public function attributes()
    {
        return [
            'file' => 'File',
            'thumbnail' => 'Thumbnail',
            'youtube_id' => 'Youtube ID'
        ];
    }

    public function messages()
    {
        return [
            'file.required' => ':attribute is required',
            'file.mimes' => ':attribute must be a file of type: :values.',
            'thumbnail.mimes' => ':attribute must be a file of type: :values.',
            'youtube_id.required' => ':attribute is required',
        ];
    }
}
