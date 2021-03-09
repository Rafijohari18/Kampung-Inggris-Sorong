<?php

namespace App\Services\Catalog;

use App\Models\Catalog\CatalogCategory;
use App\Services\LanguageService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class CatalogCategoryService
{
    private $model, $lang;

    public function __construct(
        CatalogCategory $model,
        LanguageService $lang
    )
    {
        $this->model = $model;
        $this->lang = $lang;

        $this->custom_view = config('custom.resource.path.catalog_categories');
    }

    public function getCatalogCategoryList($request)
    {
        $query = $this->model->query();

        $query->when($request->q, function ($query, $q) {
            $query->where(function ($query) use ($q) {
                $query->where('name->'.App::getLocale(), 'like', '%'.$q.'%');
            });
        });

        $limit = 20;
        if (!empty($request->l)) {
            $limit = $request->l;
        }

        $result = $query->orderBy('position', 'ASC')->paginate($limit);

        return $result;
    }

    public function getCatalogCategory($request = null, $withPaginate = null, $limit = null)
    {
        $query = $this->model->query();

        if (!empty($request)) {
            $query->when($request->keyword, function ($query, $q) {
                $query->where(function ($query) use ($q) {
                    $query->where('name->'.App::getLocale(), 'like', '%'.$q.'%')
                        ->orWhere('meta_data->title', 'like', '%'.$q.'%')
                        ->orWhere('meta_data->description', 'like', '%'.$q.'%')
                        ->orWhere('meta_data->keywords', 'like', '%'.$q.'%');
                });
            });
        }

        if (!empty($withPaginate)) {
            $result = $query->orderBy('position', 'ASC')->paginate($limit);
        } else {
            if (!empty($limit)) {
                $result = $query->orderBy('position', 'ASC')->limit($limit)->get();
            } else {
                $result = $query->get();
            }
        }

        return $result;
    }

    public function countCatalogCategory()
    {
        $query = $this->model->query();

        $result = $query->count();

        return $result;
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function store($request)
    {
        $category = new CatalogCategory;
        //field
        $this->setField($request, $category);
        $category->position = $this->model->max('position') + 1;
        $category->created_by = auth()->user()->id;
        $category->save();

        return $category;
    }

    public function update($request, int $id)
    {
        $category = $this->find($id);
        //field
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
        $category->cover = [
            'file_path' => str_replace(url('/storage/filemanager/'), '', $request->cover_file) ?? null,
            'title' => $request->cover_title ?? null,
            'alt' => $request->cover_alt ?? null,
        ];
        $category->banner = [
            'file_path' => str_replace(url('/storage/filemanager/'), '', $request->banner_file) ?? null,
            'title' => $request->banner_title ?? null,
            'alt' => $request->banner_alt ?? null,
        ];
        $category->is_widget = (bool)$request->is_widget;
        $category->custom_view_id = $request->custom_view_id ?? null;
        $category->list_limit = $request->list_limit ?? null;
        $category->product_selection = $request->product_selection ?? null;
        $category->meta_data = [
            'title' => $request->meta_title ?? null,
            'description' => $request->meta_description ?? null,
            'keywords' => $request->meta_keywords ?? null,
        ];
        $category->updated_by = auth()->user()->id;

        return $category;
    }

    public function position(int $id, int $position)
    {
        if ($position >= 1) {

            $category = $this->find($id);
            $this->model->where('position', $position)->update([
                'position' => $category->position,
            ]);
            $category->position = $position;
            $category->save();

            return $category;
        }
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

        if ($category->product->count() == 0) {

            $category->field()->delete();
            $category->delete();

            return true;

        } else {

            return false;
        }
    }
}
