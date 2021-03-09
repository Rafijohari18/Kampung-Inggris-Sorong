<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConfigUploadRequest extends FormRequest
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
        if ($this->name == 'logo') {
            return [
                'logo' => 'required|mimes:'.config('custom.mimes.logo.m'),
            ];
        } elseif ($this->name == 'logo_2') {
            return [
                'logo_2' => 'required|mimes:'.config('custom.mimes.logo_2.m'),
            ];
        }  elseif ($this->name == 'logo_small') {
            return [
                'logo_small' => 'required|mimes:'.config('custom.mimes.logo_small.m'),
            ];
        } elseif ($this->name == 'logo_small_2') {
            return [
                'logo_small_2' => 'required|mimes:'.config('custom.mimes.logo_small_2.m'),
            ];
        } elseif ($this->name == 'logo_mail') {
            return [
                'logo_mail' => 'required|mimes:'.config('custom.mimes.logo_mail.m'),
            ];
        } elseif ($this->name == 'open_graph') {
            return [
                'open_graph' => 'required|mimes:'.config('custom.mimes.open_graph.m'),
            ];
        } elseif ($this->name == 'banner_default') {
            return [
                'banner_default' => 'required|mimes:'.config('custom.mimes.banner_default.m'),
            ];
        } elseif ($this->name == 'google_analytics_api') {
            return [
                'google_analytics_api' => 'required',
            ];
        }
    }
}
