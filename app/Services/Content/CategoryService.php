<?php

namespace App\Services\Content;

use App\Models\Content\Category;
use App\Services\LanguageService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CategoryService
{
    private $model, $lang;

    public function __construct(
        Category $model,
        LanguageService $lang
    )
    {
        $this->model = $model;
        $this->lang = $lang;

        $this->custom_view = config('custom.resource.path.categories');
    }

    public function getCategoryList($request, int $sectionId)
    {
        $query = $this->model->query();

        $query->where('section_id', $sectionId);
        $query->when($request->p, function ($query, $p) {
            $query->where('public', $p);
        })->when($request->q, function ($query, $q) {
            $query->where(function ($query) use ($q) {
                $query->where('name->'.App::getLocale(), 'like', '%'.$q.'%');
            });
        });

        $limit = 20;
        if (!empty($request->l)) {
            $limit = $request->l;
        }

        $result = $query->paginate($limit);

        return $result;
    }

    public function getCategoryBySection($sectionId)
    {
        $query = $this->model->query();

        if (!empty($sectionId)) {
            $query->where('section_id', $sectionId);
        }

        $result = $query->get();

        return $result;
    }

    public function getCategory($request = null, $withPaginate = null, $limit = null, int $sectionId = null)
    {
        $query = $this->model->query();

        if (!empty($sectionId)) {
            $query->where('section_id', $sectionId);
        }

        if (auth()->guard()->check() == false) {
            $query->public();
        }

        if (!empty($request)) {
            $this->search($query, $request);
        }

        if (!empty($withPaginate)) {
            $result = $query->paginate($limit);
        } else {
            if (!empty($limit)) {
                $result = $query->limit($limit)->get();
            } else {
                $result = $query->get();
            }
        }

        return $result;
    }

    public function search($query, $request)
    {
        $query->when($request->keyword, function ($query, $q) {
            $query->where(function ($query) use ($q) {
                $query->where('name->'.App::getLocale(), 'like', '%'.$q.'%')
                    ->orWhere('description->'.App::getLocale(), 'like', '%'.$q.'%');;
            });
        });
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function store($request, int $sectionId)
    {
        $category = new Category;
        $category->section_id = $sectionId;
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

        $category->slug = str_replace('...', '', Str::limit(Str::slug($request->slug, '-'), 50));
        $category->name = $name;
        $category->description = $description;
        $category->public = (bool)$request->public;
        $category->is_widget = (bool)$request->is_widget;
        $category->list_view_id = $request->list_view_id ?? null;
        $category->detail_view_id = $request->detail_view_id?? null;
        $category->list_limit = $request->list_limit ?? null;
        $category->banner = [
            'file_path' => str_replace(url('/storage/filemanager/'), '', $request->banner_file) ?? null,
            'title' => $request->banner_title ?? null,
            'alt' => $request->banner_alt ?? null,
        ];
        $category->updated_by = auth()->user()->id;

        return $category;
    }

    public function recordViewer(int $id)
    {
        $category = $this->find($id);
        $category->viewer = ($category->viewer+1);
        $category->save();

        return $category;
    }

    public function delete(int $id)
    {
        $category = $this->find($id);

        if ($category->post->count() == 0) {

            $category->field()->delete();
            $category->delete();

            return true;

        } else {
            return false;
        }
    }
}
