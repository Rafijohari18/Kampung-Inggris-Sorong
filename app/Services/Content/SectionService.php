<?php

namespace App\Services\Content;

use App\Models\Content\Section;
use App\Services\LanguageService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class SectionService
{
    private $model, $lang;

    public function __construct(
        Section $model,
        LanguageService $lang
    )
    {
        $this->model = $model;
        $this->lang = $lang;

        $this->custom_view = config('custom.resource.path.sections');
    }

    public function getSectionList($request)
    {
        $query = $this->model->query();

        $query->when($request->e, function ($query, $e) {
            $query->where('extra', $e);
        })->when($request->p, function ($query, $p) {
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

    public function getSection($request = null, $withPaginate = null, $limit = null)
    {
        $query = $this->model->query();

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
                    ->orWhere('description->'.App::getLocale(), 'like', '%'.$q.'%');
            });
        });
    }

    public function countSection()
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
        $section = new Section;
        $this->setField($request, $section);
        $section->created_by = auth()->user()->id;
        $section->save();

        return $section;
    }

    public function update($request, int $id)
    {
        $section = $this->find($id);
        $this->setField($request, $section);
        if ($section->field->count() > 0) {
            $field = [];
            foreach ($section->field as $key) {
                $field[$key->name] = $request->input('field_'.$key->name) ?? null;
            }
            $section->custom_field = $field;
        }
        $section->save();

        return $section;
    }

    public function setField($request, $section)
    {
        foreach ($this->lang->getAllLang() as $key => $value) {
            $name[$value->iso_codes] = ($request->input('name_'.$value->iso_codes) == null) ?
                $request->input('name_'.App::getLocale()) : $request->input('name_'.$value->iso_codes);
            $description[$value->iso_codes] = ($request->input('description_'.$value->iso_codes) == null) ?
                $request->input('description_'.App::getLocale()) : $request->input('description_'.$value->iso_codes);
        }

        $section->slug = str_replace('...', '', Str::limit(Str::slug($request->slug, '-'), 50));
        $section->name = $name;
        $section->description = $description;
        $section->public = (bool)$request->public;
        $section->is_widget = (bool)$request->is_widget;
        $section->cover_required = (bool)$request->cover_required;
        $section->order_field = $request->order_field ?? 4;
        $section->order_by = $request->order_by ?? 'DESC';
        $section->extra = $request->extra ?? null;
        $section->list_view_id = $request->list_view_id ?? null;
        $section->detail_view_id = $request->detail_view_id ?? null;
        $section->limit_category = $request->limit_category ?? null;
        $section->post_selection = $request->post_selection ?? null;
        $section->limit_post = $request->limit_post ?? null;
        $section->banner = [
            'file_path' => str_replace(url('/storage/filemanager/'), '', $request->banner_file) ?? null,
            'title' => $request->banner_title ?? null,
            'alt' => $request->banner_alt ?? null,
        ];
        $section->updated_by = auth()->user()->id;

        return $section;
    }

    public function recordViewer(int $id)
    {
        $section = $this->find($id);
        $section->viewer = ($section->viewer+1);
        $section->save();

        return $section;
    }

    public function delete(int $id)
    {
        $section = $this->find($id);

        if ($section->category->count() == 0 || $section->post->count() == 0) {

            $section->field()->delete();
            $section->delete();

            return true;

        } else {
            return false;
        }

    }
}
