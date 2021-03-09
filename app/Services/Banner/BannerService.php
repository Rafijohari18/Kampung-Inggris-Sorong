<?php

namespace App\Services\Banner;

use App\Models\Banner\Banner;
use App\Services\LanguageService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerService
{
    private $model, $lang;

    public function __construct(
        Banner $model,
        LanguageService $lang
    )
    {
        $this->model = $model;
        $this->lang = $lang;
    }

    public function getBannerList($request, $categoryId)
    {
        $query = $this->model->query();

        $query->where('banner_category_id', $categoryId);
        $query->when($request->q, function ($query, $q) {
            $query->where(function ($query) use ($q) {
                $query->where('file', 'like', '%'.$q.'%')
                    ->orWhere('title->'.App::getLocale(), 'like', '%'.$q.'%')
                    ->orWhere('description->'.App::getLocale(), 'like', '%'.$q.'%');
            });
        });

        $limit = 6;
        if (!empty($request->l)) {
            $limit = $request->l;
        }

        $result = $query->orderBy('position', 'ASC')->paginate($limit);

        return $result;
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function store($request, int $categoryId)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = Str::random(3).'-'.str_replace(' ', '-', $file->getClientOriginalName());

            Storage::put(config('custom.images.path.banner').$categoryId.'/'.$fileName, file_get_contents($file));
        }

        $banner = new Banner;
        $banner->banner_category_id = $categoryId;
        $banner->is_video = (bool)$request->is_video;
        $banner->file = $fileName ?? null;
        if ((bool)$request->is_video == 1 && empty($request->file)) {
            $banner->youtube_id = $request->youtube_id ?? null;
        }
        $this->setField($request, $banner);
        $banner->position = $this->model->where('banner_category_id', $categoryId)->max('position') + 1;
        $banner->created_by = auth()->user()->id;
        $banner->save();

        return $banner;
    }

    public function update($request, int $id)
    {
        $banner = $this->find($id);
        $this->setField($request, $banner);
        $banner->save();

        return $banner;
    }

    public function setField($request, $banner)
    {
        foreach ($this->lang->getAllLang() as $key => $value) {
            $title[$value->iso_codes] = ($request->input('title_'.$value->iso_codes) == null) ?
                $request->input('title_'.App::getLocale()) : $request->input('title_'.$value->iso_codes);
            $description[$value->iso_codes] = ($request->input('description_'.$value->iso_codes) == null) ?
                $request->input('description_'.App::getLocale()) : $request->input('description_'.$value->iso_codes);
        }
        $banner->title = $title;
        $banner->description = $description;
        $banner->file_alt = $request->file_alt ?? null;
        $banner->url = $request->url ?? null;
        $banner->publish = (bool)$request->publish;
        $banner->updated_by = auth()->user()->id;

        return $banner;
    }

    public function publish(int $id)
    {
        $banner = $this->find($id);
        $banner->publish = !$banner->publish;
        $banner->save();

        return $banner;
    }

    public function position(int $id, int $position)
    {
        if ($position >= 1) {

            $banner = $this->find($id);
            $this->model->where('position', $position)->update([
                'position' => $banner->position,
            ]);
            $banner->position = $position;
            $banner->save();

            return $banner;
        }
    }

    public function sort(int $id, $position)
    {
        $find = $this->find($id);

        $banner = $this->model->where('id', $id)
                ->where('banner_category_id', $find->banner_category_id)->update([
            'position' => $position
        ]);

        return $banner;
    }

    public function delete(int $id)
    {
        $banner = $this->find($id);
        if (empty($banner->youtube_id)) {
            Storage::delete(config('custom.images.path.banner').$banner->banner_category_id.'/'.$banner->file);
        }
        $banner->delete();

        return $banner;
    }
}
