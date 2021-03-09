<?php

namespace App\Services;

use App\Models\Page;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class PageService
{
    private $model, $lang;

    public function __construct(
        Page $model,
        LanguageService $lang
    )
    {
        $this->model = $model;
        $this->lang = $lang;

        $this->custom_view = config('custom.resource.path.pages');
    }

    public function getPageList($request)
    {
        $query = $this->model->query();

        $query->when($request->s, function ($query, $s) {
            $query->where('publish', $s);
        })->when($request->p, function ($query, $p) {
            $query->where('public', $p);
        })->when($request->q, function ($query, $q) {
            $query->where(function ($query) use ($q) {
                $query->where('title->'.App::getLocale(), 'like', '%'.$q.'%');
            });
        });

        $limit = 20;
        if (!empty($request->l)) {
            $limit = $request->l;
        }

        $result = $query->where('parent', 0)->orderBy('position', 'ASC')->paginate($limit);

        return $result;
    }

    public function getPage($request = null, $withPaginate = null, $limit = null)
    {
        $query = $this->model->query();

        $query->publish();
        if (auth()->guard()->check() == false) {
            $query->public();
        }

        if (!empty($request)) {
            $this->search($query, $request);
        }

        if (!empty($withPaginate)) {
            $result = $query->orderBy('position', 'ASC')->paginate($limit);
        } else {
            if (!empty($limit)) {
                $result = $query->orderBy('position', 'ASC')->limit($limit)->get();
            } else {
                $result = $query->orderBy('position', 'ASC')->get();
            }
        }

        return $result;
    }

    public function search($query, $request)
    {
        $query->when($request->keyword, function ($query, $q) {
            $query->where(function ($query) use ($q) {
                $query->where('title->'.App::getLocale(), 'like', '%'.$q.'%')
                    ->orWhere('intro->'.App::getLocale(), 'like', '%'.$q.'%')
                    ->orWhere('content->'.App::getLocale(), 'like', '%'.$q.'%')
                    ->orWhere('meta_data->title', 'like', '%'.$q.'%')
                    ->orWhere('meta_data->description', 'like', '%'.$q.'%')
                    ->orWhere('meta_data->keywords', 'like', '%'.$q.'%');
            });
        });
    }

    public function countPage()
    {
        $query = $this->model->query();

        $query->publish();

        $result = $query->count();

        return $result;
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function store($request)
    {
        $parent = $request->parent ?? 0;

        $page = new Page;
        $page->parent = $parent;
        //field
        $this->setField($request, $page);
        $page->position = $this->model->where('parent', (int)$parent)->max('position') + 1;
        $page->created_by = auth()->user()->id;
        $page->save();

        return $page;
    }

    public function update($request, int $id)
    {
        $page = $this->find($id);

        //field
        $this->setField($request, $page);
        if ($page->field->count() > 0) {
            $field = [];
            foreach ($page->field as $key) {
                $field[$key->name] = $request->input('field_'.$key->name) ?? null;
            }
            $page->custom_field = $field;
        }
        $page->save();

        return $page;
    }

    public function setField($request, $page)
    {
        foreach ($this->lang->getAllLang() as $key => $value) {
            $title[$value->iso_codes] = ($request->input('title_'.$value->iso_codes) == null) ?
                $request->input('title_'.App::getLocale()) : $request->input('title_'.$value->iso_codes);
            $intro[$value->iso_codes] = ($request->input('intro_'.$value->iso_codes) == null) ?
                $request->input('intro_'.App::getLocale()) : $request->input('intro_'.$value->iso_codes);
            $content[$value->iso_codes] = ($request->input('content_'.$value->iso_codes) == null) ?
                $request->input('content_'.App::getLocale()) : $request->input('content_'.$value->iso_codes);
        }

        $page->slug = str_replace('...', '', Str::limit(Str::slug($request->slug, '-'), 50));
        $page->title = $title;
        $page->intro = $intro;
        $page->content = $content;
        $page->cover = [
            'file_path' => str_replace(url('/storage/filemanager/'), '', $request->cover_file) ?? null,
            'title' => $request->cover_title ?? null,
            'alt' => $request->cover_alt ?? null,
        ];
        $page->banner = [
            'file_path' => str_replace(url('/storage/filemanager/'), '', $request->banner_file) ?? null,
            'title' => $request->banner_title ?? null,
            'alt' => $request->banner_alt ?? null,
        ];
        $page->publish = (bool)$request->publish;
        $page->public = (bool)$request->public;
        $page->is_widget = (bool)$request->is_widget;
        $page->custom_view_id = $request->custom_view_id ?? null;
        $page->meta_data = [
            'title' => $request->meta_title ?? null,
            'description' => $request->meta_description ?? null,
            'keywords' => $request->meta_keywords ?? null,
        ];
        $page->updated_by = auth()->user()->id;

        return $page;
    }

    public function publish(int $id)
    {
        $page = $this->find($id);
        $page->publish = !$page->publish;
        $page->save();

        return $page;
    }

    public function position(int $id, int $position, int $parent = null)
    {
        if ($position >= 1) {

            $page = $this->find($id);
            if (!empty($parent)) {
                $this->model->where('position', $position)->where('parent', $parent)->update([
                    'position' => $page->position,
                ]);
            } else {
                $this->model->where('position', $position)->update([
                    'position' => $page->position,
                ]);
            }
            $page->position = $position;
            $page->save();

            return $page;
        }
    }

    public function recordViewer(int $id)
    {
        $page = $this->find($id);
        $page->viewer = ($page->viewer+1);
        $page->save();

        return $page;
    }

    public function delete(int $id)
    {
        $page = $this->find($id);

        if ($this->model->where('parent', $id)->count() == 0) {

            foreach ($this->model->where('parent', $id)->get() as $valueA) {

                $valueA->field()->delete();
                $valueA->media()->delete();
                $valueA->delete();

                foreach ($this->model->where('parent', $valueA->id)->get() as $valueB) {

                    $valueB->field()->delete();
                    $valueB->media()->delete();
                    $valueB->delete();
                }
            }

            $page->field()->delete();
            $page->media()->delete();
            $page->delete();

            return true;
        } else {
            return false;
        }
    }
}
