<?php

namespace App\Services;

use App\Models\Configuration;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ConfigurationService
{
    private $model;

    public function __construct(Configuration $model)
    {
        $this->model = $model;
    }

    public function getConfig($group)
    {
        $query = $this->model->query();

        $query->where('group', $group)->where('is_upload', 0);

        $result = $query->get();

        return $result;
    }

    public function getConfigIsUpload()
    {
        $query = $this->model->query();

        $query->upload();

        $result = $query->get();

        return $result;
    }

    public function getValue($name)
    {
        return $this->model->value($name);
    }

    public function updateConfig($name, $value)
    {
        $config = $this->model->where('name', $name)->first();
        $config->value = $value;
        $config->save();

        return $config;
    }

    public function uploadFile($request, $name)
    {
        if ($request->hasFile($name)) {

            if ($name == 'logo') {
                $file = $request->file('logo');
                $replace = str_replace(' ', '-', $file->getClientOriginalName());
                $fileName = Str::random(5).'-'.$replace;

                Storage::delete('public/logo/'.$request->old_logo);
                Storage::put('public/logo/'.$fileName, file_get_contents($file));
            } elseif ($name == 'logo_2') {
                $file = $request->file('logo_2');
                $replace = str_replace(' ', '-', $file->getClientOriginalName());
                $fileName = Str::random(5).'-'.$replace;

                Storage::delete('public/logo/'.$request->old_logo_2);
                Storage::put('public/logo/'.$fileName, file_get_contents($file));
            } elseif ($name == 'logo_small') {
                $file = $request->file('logo_small');
                $replace = str_replace(' ', '-', $file->getClientOriginalName());
                $fileName = Str::random(5).'-'.$replace;

                Storage::delete('public/logo/'.$request->old_logo_small);
                Storage::put('public/logo/'.$fileName, file_get_contents($file));
            } elseif ($name == 'logo_small_2') {
                $file = $request->file('logo_small_2');
                $replace = str_replace(' ', '-', $file->getClientOriginalName());
                $fileName = Str::random(5).'-'.$replace;

                Storage::delete('public/logo/'.$request->old_logo_small_2);
                Storage::put('public/logo/'.$fileName, file_get_contents($file));
            } elseif ($name == 'logo_mail') {
                $file = $request->file('logo_mail');
                $replace = str_replace(' ', '-', $file->getClientOriginalName());
                $fileName = Str::random(5).'-'.$replace;

                Storage::delete('public/logo/'.$request->old_logo_mail);
                Storage::put('public/logo/'.$fileName, file_get_contents($file));
            } elseif ($name == 'open_graph') {
                $file = $request->file('open_graph');
                $replace = str_replace(' ', '-', $file->getClientOriginalName());
                $fileName = Str::random(5).'-'.$replace;

                Storage::delete('public/logo/'.$request->old_open_graph);
                Storage::put('public/logo/'.$fileName, file_get_contents($file));
            } elseif ($name == 'banner_default') {
                $file = $request->file('banner_default');
                $replace = str_replace(' ', '-', $file->getClientOriginalName());
                $fileName = Str::random(5).'-'.$replace;

                Storage::delete('public/banner/'.$request->old_banner_default);
                Storage::put('public/banner/'.$fileName, file_get_contents($file));
            } elseif ($name == 'google_analytics_api') {

                $fileName = 'service-account-credentials.'.
                $request->google_analytics_api->getClientOriginalExtension();
                $request->file('google_analytics_api')->move(storage_path('app/analytics'), $fileName);
            }

            $config = $this->model->where('name', $name)->first();
            $config->value = $fileName;
            $config->save();

            return $config;
        } else {
            return false;
        }
    }
}
