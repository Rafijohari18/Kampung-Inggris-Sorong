<?php

namespace App\Services\Link;

use App\Models\Link\LinkMedia;
use App\Services\LanguageService;
use Illuminate\Support\Facades\App;

class LinkMediaService
{
    private $model, $lang;

    public function __construct(
        LinkMedia $model,
        LanguageService $lang
    )
    {
        $this->model = $model;
        $this->lang = $lang;
    }

    public function getlinkMediaList($request, $linkId)
    {
        $query = $this->model->query();

        $query->where('link_id', $linkId);
        $query->when($request->q, function ($query, $q) {
            $query->where(function ($query) use ($q) {
                $query->where('title->'.App::getLocale(), 'like', '%'.$q.'%')
                    ->orWhere('description->'.App::getLocale(), 'like', '%'.$q.'%');
            });
        });

        $limit = 20;
        if (!empty($request->l)) {
            $limit = $request->l;
        }

        $result = $query->orderBy('position', 'ASC')->paginate($limit);

        return $result;
    }

    public function getLinkMedia($request = null, $withPaginate = null, $limit = null, $linkId = null)
    {
        $query = $this->model->query();

        if (!empty($linkId)) {
            $query->where('link_id', $linkId);
        }

        if (!empty($request)) {
            $query->when($request->q, function ($query, $q) {
                $query->where(function ($query) use ($q) {
                    $query->where('title->'.App::getLocale(), 'like', '%'.$q.'%')
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

    public function countLinkMedia()
    {
        $query = $this->model->query();

        $result = $query->count();

        return $result;
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function store($request, int $linkId)
    {
        $linkMedia = new LinkMedia;
        $this->setField($request, $linkMedia);
        $linkMedia->link_id = $linkId;
        $linkMedia->position = $this->model->where('link_id', $linkId)->max('position') + 1;
        $linkMedia->created_by = auth()->user()->id;
        $linkMedia->save();

        return $linkMedia;
    }

    public function update($request, int $id)
    {
        $linkMedia = $this->find($id);
        $this->setField($request, $linkMedia);
        if ($linkMedia->field->count() > 0) {
            $field = [];
            foreach ($linkMedia->field as $key) {
                $field[$key->name] = $request->input('field_'.$key->name) ?? null;
            }
            $linkMedia->custom_field = $field;
        }
        $linkMedia->save();

        return $linkMedia;
    }

    public function setField($request, $linkMedia)
    {
        foreach ($this->lang->getAllLang() as $key => $value) {
            $title[$value->iso_codes] = ($request->input('title_'.$value->iso_codes) == null) ?
                $request->input('title_'.App::getLocale()) : $request->input('title_'.$value->iso_codes);
            $description[$value->iso_codes] = ($request->input('description_'.$value->iso_codes) == null) ?
                $request->input('description_'.App::getLocale()) : $request->input('description_'.$value->iso_codes);
        }
        $linkMedia->title = $title;
        $linkMedia->description = $description;
        $linkMedia->url = $request->url;
        $linkMedia->cover = [
            'file_path' => str_replace(url('/storage/filemanager/'), '', $request->cover_file) ?? null,
            'title' => $request->cover_title ?? null,
            'alt' => $request->cover_alt ?? null,
        ];
        $linkMedia->banner = [
            'file_path' => str_replace(url('/storage/filemanager/'), '', $request->banner_file) ?? null,
            'title' => $request->banner_title ?? null,
            'alt' => $request->banner_alt ?? null,
        ];
        $linkMedia->updated_by = auth()->user()->id;

        return $linkMedia;
    }

    public function position(int $id, int $position)
    {
        if ($position >= 1) {

            $linkMedia = $this->find($id);
            $this->model->where('position', $position)->update([
                'position' => $linkMedia->position,
            ]);
            $linkMedia->position = $position;
            $linkMedia->save();

            return $linkMedia;
        }
    }

    public function delete(int $id)
    {
        $linkMedia = $this->find($id);
        $linkMedia->delete();

        return true;
    }
}
