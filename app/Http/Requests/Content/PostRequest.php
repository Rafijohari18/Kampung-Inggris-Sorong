<?php

namespace App\Http\Requests\Content;

use App\Models\Content\Post\Post;
use App\Models\Content\Section;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\App;

class PostRequest extends FormRequest
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
        if ($this->method() == 'PUT') {
            $post = Post::findOrFail($this->id);
            $section = Section::findOrFail($post->section_id);
        } else {
            $section = Section::findOrFail($this->sectionId);
        }

        $default = [
            'title_'.App::getLocale() => 'required',
            'slug' => 'required',
            'category_id' => 'required',
        ];

        if ($section->extra == 0 && $section->cover_required == 0) {

            return $default;

        } elseif ($section->extra == 0 && $section->cover_required == 1) {

            $cover = [
                'cover_file' => 'required',
            ];

            return array_merge($default, $cover);

        } elseif ($section->extra == 1 && $section->cover_required == 0) {

            if ($this->method() == 'POST') {
                $file = [
                    'files' => 'nullable|array',
                    'files.*' => 'nullable|max:'.config('custom.mimes.extra_file.s').'|distinct|mimes:'.config('custom.mimes.extra_file.m'),
                ];
            } else {
                if ($post->files->count() == 0) {
                    $file = [
                        'files' => 'nullable|array',
                        'files.*' => 'nullable|max:'.config('custom.mimes.extra_file.s').'|distinct|mimes:'.config('custom.mimes.extra_file.m'),
                    ];
                } else {
                    $file = [
                        'files' => 'nullable|array',
                        'files.*' => 'nullable|max:'.config('custom.mimes.extra_file.s').'|distinct|mimes:'.config('custom.mimes.extra_file.m'),
                    ];
                }
            }

            return array_merge($default, $file);

        } elseif ($section->extra == 1 && $section->cover_required == 1) {

            if ($this->method() == 'POST') {
                $coverNFile = [
                    'cover_file' => 'required',
                    'files' => 'nullable|array',
                    'files.*' => 'nullable|max:'.config('custom.mimes.extra_file.s').'|distinct|mimes:'.config('custom.mimes.extra_file.m'),
                ];
            } else {
                if ($post->files->count() == 0) {
                    $coverNFile = [
                        'cover_file' => 'required',
                        'files' => 'nullable|array',
                        'files.*' => 'nullable|max:'.config('custom.mimes.extra_file.s').'|distinct|mimes:'.config('custom.mimes.extra_file.m'),
                    ];
                } else {
                    $coverNFile = [
                        'cover_file' => 'required',
                        'files' => 'nullable|array',
                        'files.*' => 'nullable|max:'.config('custom.mimes.extra_file.s').'|distinct|mimes:'.config('custom.mimes.extra_file.m'),
                    ];
                }
            }

            return array_merge($default, $coverNFile);

        } elseif ($section->extra == 2 && $section->cover_required == 0) {

            $profile = [
                'profile_name' => 'required',
                'profile_position' => 'required',
            ];

            return array_merge($default, $profile);

        } elseif ($section->extra == 2 && $section->cover_required == 1) {

            $coverNProfile = [
                'cover_file' => 'required',
                'profile_name' => 'required',
                'profile_position' => 'required',
            ];

            return array_merge($default, $coverNProfile);
        }
    }

    public function attributes()
    {
        return [
            'title_'.App::getLocale() => 'Title',
            'slug' => 'Slug',
            'category_id' => 'Category',
            'cover_file' => 'Cover',
            'files' => 'File',
            'profile_name' => 'Name',
            'profile_position' => 'Position',
        ];
    }

    public function messages()
    {
        return [
            'title_'.App::getLocale().'.required' => ':attribute is required',
            'slug.required' => ':attribute is required',
            'category_id.required' => ':attribute is required',
            'cover_file.required' => ':attribute is required',
            'files.required' => ':attribute is required',
            'files.mimes' => 'Format file not match',
            'profile_name.required' => ':attribute is required',
            'profile_position.required' => ':attribute is required',
        ];
    }
}
