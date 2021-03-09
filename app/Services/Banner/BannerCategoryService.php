<?php

namespace App\Services\Banner;

use App\Models\Banner\BannerCategory;
use App\Services\LanguageService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerCategoryService
{
    private $model, $lang;

    public function __construct(
        BannerCategory $model,
        LanguageService $lang
    )
    {
        $this->model = $model;
        $this->lang = $lang;
    }

    public function getBannerCategoryList($request)
    {
        $query = $this->model->query();

        $query->when($request->q, function ($query, $q) {
            $query->where(function ($query) use ($q) {
                $query->where('name->'.App::getLocale(), 'like', '%'.$q.'%')
                    ->orWhere('description->'.App::getLocale(), 'like', '%'.$q.'%');
            });
        });

        $limit = 20;
        if (!empty($request->l)) {
            $limit = $request->l;
        }

        $result = $query->paginate($limit);

        return $result;
    }

    public function getBannerCategory($id)
    {
        $query = $this->model->query();

        $query->where('id', $id);

        $result = $query->get();

        return $result;
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function store($request)
    {
        $category = new BannerCategory;
        $this->setField($request, $category);
        $category->created_by = auth()->user()->id;
        $category->save();

        return $category;
    }

    public function update($request, int $id)
    {
        $category = $this->find($id);
        $this->setField($request, $category);
        if ($category->field->count() > 0) {
            $field = [];
            foreach ($category->field as $key) {
                $field[$key->name] = $request->input('field_'.$key->name) ?? null;
            }
            $category->custom_field = $field;
        }
        $category->save();

        return $category;
    }

    public function setField($request, $category)
    {
        foreach ($this->lang->getAllLang() as $key => $value) {
            $name[$value->iso_codes] = ($request->input('name_'.$value->iso_codes) == null) ?
                $request->input('name_'.App::getLocale()) : $request->input('name_'.$value->iso_codes);
            $description[$value->iso_codes] = ($request->input('description_'.$value->iso_codes) == null) ?
                $request->input('description_'.App::getLocale()) : $request->input('description_'.$value->iso_codes);
        }

        $category->name = $name;
        $category->description = $description;
        $category->list_limit = $request->list_limit ?? null;
        $category->updated_by = auth()->user()->id;

        return $category;
    }

    public function delete(int $id)
    {
        $category = $this->find($id);

        if ($category->banner->count() == 0) {

            Storage::deleteDirectory(config('custom.images.path.banner').$id);
            $category->field()->delete();
            $category->delete();

            return true;

        } else {
            return false;
        }

    }
}
