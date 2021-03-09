<?php

namespace App\Services\Gallery;

use App\Models\Gallery\Album;
use App\Services\LanguageService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AlbumService
{
    private $model, $lang;

    public function __construct(
        Album $model,
        LanguageService $lang
    )
    {
        $this->model = $model;
        $this->lang = $lang;

        $this->custom_view = config('custom.resource.path.albums');
    }

    public function getAlbumList($request)
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

    public function getAlbum($request = null, $withPaginate = null, $limit = null)
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
                $result = $query->orderBy('position', 'ASC')->limit($limit)->get();
            } else {
                $result = $query->orderBy('position', 'ASC')->get();
            }
        }

        return $result;
    }

    public function countAlbum()
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
        $album = new Album;
        $this->setField($request, $album);
        $album->position = $this->model->max('position') + 1;
        $album->created_by = auth()->user()->id;
        $album->save();

        return $album;
    }

    public function update($request, int $id)
    {
        $album = $this->find($id);
        $this->setField($request, $album);
        $album->save();

        return $album;
    }

    public function setField($request, $album)
    {
        foreach ($this->lang->getAllLang() as $key => $value) {
            $name[$value->iso_codes] = ($request->input('name_'.$value->iso_codes) == null) ?
                $request->input('name_'.App::getLocale()) : $request->input('name_'.$value->iso_codes);
            $description[$value->iso_codes] = ($request->input('description_'.$value->iso_codes) == null) ?
                $request->input('description_'.App::getLocale()) : $request->input('description_'.$value->iso_codes);
        }

        $album->name = $name;
        $album->description = $description;
        $album->publish = (bool)$request->publish;
        $album->is_widget = (bool)$request->is_widget;
        $album->custom_view_id = $request->custom_view_id ?? null;
        $album->list_limit = $request->list_limit ?? null;
        $album->banner = [
            'file_path' => str_replace(url('/storage/filemanager/'), '', $request->banner_file) ?? null,
            'title' => $request->banner_title ?? null,
            'alt' => $request->banner_alt ?? null,
        ];
        $album->updated_by = auth()->user()->id;

        return $album;
    }

    public function publish(int $id)
    {
        $album = $this->find($id);
        $album->publish = !$album->publish;
        $album->save();

        return $album;
    }

    public function position(int $id, int $position)
    {
        if ($position >= 1) {

            $album = $this->find($id);
            $this->model->where('position', $position)->update([
                'position' => $album->position,
            ]);
            $album->position = $position;
            $album->save();

            return $album;
        }
    }

    public function recordViewer(int $id)
    {
        $album = $this->find($id);
        $album->viewer = ($album->viewer+1);
        $album->save();

        return $album;
    }

    public function delete(int $id)
    {
        $album = $this->find($id);

        if ($album->photo->count() == 0) {
            Storage::deleteDirectory(config('custom.images.path.gallery_photo').$id);
            $album->delete();

            return true;

        } else {
            return false;
        }

    }
}
