<?php

namespace App\Services\Link;

use App\Models\Link\Link;
use App\Services\LanguageService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class LinkService
{
    private $model, $lang;

    public function __construct(
        Link $model,
        LanguageService $lang
    )
    {
        $this->model = $model;
        $this->lang = $lang;

        $this->custom_view = config('custom.resource.path.links');
    }

    public function getLinkList($request)
    {
        $query = $this->model->query();

        $query->when($request->s, function ($query, $s) {
            $query->where('publish', $s);
        })->when($request->q, function ($query, $q) {
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

    public function getLink($request = null, $withPaginate = null, $limit = null)
    {
        $query = $this->model->query();

        $query->publish();

        if (!empty($request)) {
            $query->when($request->q, function ($query, $q) {
                $query->where(function ($query) use ($q) {
                    $query->where('name->'.App::getLocale(), 'like', '%'.$q.'%')
                        ->orWhere('description->'.App::getLocale(), 'like', '%'.$q.'%');
                });
            });
        }

        if (!empty($withPaginate)) {
            $result = $query->orderBy('position', 'ASC')->paginate($limit);
        } else {
            if (!empty($limit)) {
                $result = $query->orderBy('position', 'ASC')->limiy($limit)->get();
            } else {
                $result = $query->orderBy('position', 'ASC')->get();
            }
        }

        return $result;
    }

    public function countLink()
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
        $link = new Link;
        $this->setField($request, $link);
        $link->position = $this->model->max('position') + 1;
        $link->created_by = auth()->user()->id;
        $link->save();

        return $link;
    }

    public function update($request, int $id)
    {
        $link = $this->find($id);
        $this->setField($request, $link);
        if ($link->field->count() > 0) {
            $field = [];
            foreach ($link->field as $key) {
                $field[$key->name] = $request->input('field_'.$key->name) ?? null;
            }
            $link->custom_field = $field;
        }
        $link->save();

        return $link;
    }

    public function setField($request, $link)
    {
        foreach ($this->lang->getAllLang() as $key => $value) {
            $name[$value->iso_codes] = ($request->input('name_'.$value->iso_codes) == null) ?
                $request->input('name_'.App::getLocale()) : $request->input('name_'.$value->iso_codes);
            $description[$value->iso_codes] = ($request->input('description_'.$value->iso_codes) == null) ?
                $request->input('description_'.App::getLocale()) : $request->input('description_'.$value->iso_codes);
        }

        $link->slug = str_replace('...', '', Str::limit(Str::slug($request->slug, '-'), 50));
        $link->name = $name;
        $link->description = $description;
        $link->publish = (bool)$request->publish;
        $link->is_widget = (bool)$request->is_widget;
        $link->custom_view_id = $request->custom_view_id ?? null;
        $link->list_limit = $request->list_limit ?? null;
        $link->cover = [
            'file_path' => str_replace(url('/storage/filemanager/'), '', $request->cover_file) ?? null,
            'title' => $request->cover_title ?? null,
            'alt' => $request->cover_alt ?? null,
        ];
        $link->banner = [
            'file_path' => str_replace(url('/storage/filemanager/'), '', $request->banner_file) ?? null,
            'title' => $request->banner_title ?? null,
            'alt' => $request->banner_alt ?? null,
        ];
        $link->meta_data = [
            'title' => $request->meta_title ?? null,
            'description' => $request->meta_description ?? null,
            'keywords' => $request->meta_keywords ?? null,
        ];
        $link->updated_by = auth()->user()->id;

        return $link;
    }

    public function publish(int $id)
    {
        $link = $this->find($id);
        $link->publish = !$link->publish;
        $link->save();

        return $link;
    }

    public function position(int $id, int $position)
    {
        if ($position >= 1) {

            $link = $this->find($id);
            $this->model->where('position', $position)->update([
                'position' => $link->position,
            ]);
            $link->position = $position;
            $link->save();

            return $link;
        }
    }

    public function recordViewer(int $id)
    {
        $link = $this->find($id);
        $link->viewer = ($link->viewer+1);
        $link->save();

        return $link;
    }

    public function delete(int $id)
    {
        $link = $this->find($id);

        if ($link->media->count() == 0) {

            $link->delete();

            return true;

        } else {
            return false;
        }

    }
}
