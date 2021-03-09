<?php

namespace App\Services\Gallery;

use App\Models\Gallery\Playlist;
use App\Services\LanguageService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PlaylistService
{
    private $model, $lang;

    public function __construct(
        Playlist $model,
        LanguageService $lang
    )
    {
        $this->model = $model;
        $this->lang = $lang;

        $this->custom_view = config('custom.resource.path.playlists');
    }

    public function getPlaylistList($request)
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

    public function getPlaylist($request = null, $withPaginate = null, $limit = null)
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

    public function countPlaylist()
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
        $playlist = new Playlist;
        $this->setField($request, $playlist);
        $playlist->position = $this->model->max('position') + 1;
        $playlist->created_by = auth()->user()->id;
        $playlist->save();

        return $playlist;
    }

    public function update($request, int $id)
    {
        $playlist = $this->find($id);
        $this->setField($request, $playlist);
        $playlist->save();

        return $playlist;
    }

    public function setField($request, $playlist)
    {
        foreach ($this->lang->getAllLang() as $key => $value) {
            $name[$value->iso_codes] = ($request->input('name_'.$value->iso_codes) == null) ?
                $request->input('name_'.App::getLocale()) : $request->input('name_'.$value->iso_codes);
            $description[$value->iso_codes] = ($request->input('description_'.$value->iso_codes) == null) ?
                $request->input('description_'.App::getLocale()) : $request->input('description_'.$value->iso_codes);
        }

        $playlist->name = $name;
        $playlist->description = $description;
        $playlist->publish = (bool)$request->publish;
        $playlist->is_widget = (bool)$request->is_widget;
        $playlist->custom_view_id = $request->custom_view_id ?? null;
        $playlist->list_limit = $request->list_limit ?? null;
        $playlist->banner = [
            'file_path' => str_replace(url('/storage/filemanager/'), '', $request->banner_file) ?? null,
            'title' => $request->banner_title ?? null,
            'alt' => $request->banner_alt ?? null,
        ];
        $playlist->updated_by = auth()->user()->id;

        return $playlist;
    }

    public function publish(int $id)
    {
        $playlist = $this->find($id);
        $playlist->publish = !$playlist->publish;
        $playlist->save();

        return $playlist;
    }

    public function position(int $id, int $position)
    {
        if ($position >= 1) {

            $playlist = $this->find($id);
            $this->model->where('position', $position)->update([
                'position' => $playlist->position,
            ]);
            $playlist->position = $position;
            $playlist->save();

            return $playlist;
        }
    }

    public function recordViewer(int $id)
    {
        $playlist = $this->find($id);
        $playlist->viewer = ($playlist->viewer+1);
        $playlist->save();

        return $playlist;
    }

    public function delete(int $id)
    {
        $playlist = $this->find($id);

        if ($playlist->video->count() == 0) {

            Storage::deleteDirectory(config('custom.images.path.gallery_video').$id);
            $playlist->delete();

            return true;

        } else {
            return false;
        }

    }
}
