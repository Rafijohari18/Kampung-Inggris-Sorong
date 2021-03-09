<?php

namespace App\Services\Gallery;

use App\Models\Gallery\PlaylistVideo;
use App\Services\LanguageService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VideoService
{
    private $model, $lang;

    public function __construct(
        PlaylistVideo $model,
        LanguageService $lang
    )
    {
        $this->model = $model;
        $this->lang = $lang;
    }

    public function getVideoList($request, $playlistId)
    {
        $query = $this->model->query();

        $query->where('playlist_id', $playlistId);
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

    public function getVideo($request, $withPaginate = null, $limit = null, $playlistId = null)
    {
        $query = $this->model->query();

        if (!empty($playlistId)) {
            $query->where('playlist_id', $playlistId);
        }
        $query->when($request->q, function ($query, $q) {
            $query->where(function ($query) use ($q) {
                $query->where('file', 'like', '%'.$q.'%')
                    ->orWhere('youtube_id', 'like', '%'.$q.'%')
                    ->orWhere('title->'.App::getLocale(), 'like', '%'.$q.'%')
                    ->orWhere('description->'.App::getLocale(), 'like', '%'.$q.'%');
            });
        });

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

    public function countVideo()
    {
        $query = $this->model->query();

        $result = $query->count();

        return $result;
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function store($request, int $playlistId)
    {
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = Str::random(3).'-'.str_replace(' ', '-', $file->getClientOriginalName());

            Storage::put(config('custom.images.path.gallery_video').$playlistId.'/'.$fileName, file_get_contents($file));

            if (!empty($request->thumbnail) && $request->hasFile('thumbnail')) {
                $fileThumb = $request->file('thumbnail');
                $fileNameThumb = Str::random(3).'-'.str_replace(' ', '-', $fileThumb->getClientOriginalName());
            }
        }

        $video = new PlaylistVideo;
        $video->playlist_id = $playlistId;
        if (empty($request->youtube_id)) {
            $video->thumbnail = !empty($request->thumbnail) ? $fileNameThumb : null;
            $video->file = $fileName ?? null;
            $video->file_type = !empty($request->file) ? $file->getClientOriginalExtension() : null;
            $video->file_size = !empty($request->file) ? $file->getSize() : null;
        }
        if (empty($request->file)) {
            $video->youtube_id = $request->youtube_id;
        }
        $this->setField($request, $video);
        $video->position = $this->model->where('playlist_id', $playlistId)->max('position') + 1;
        $video->created_by = auth()->user()->id;
        $video->save();

        if (!empty($request->file)) {
            Storage::put(config('custom.images.path.gallery_video').$playlistId.'/'.$fileName, file_get_contents($file));
            if (!empty($request->thumbnail)) {
                Storage::put(config('custom.images.path.gallery_video').$playlistId.'/thumbnail/'.$fileNameThumb, file_get_contents($fileThumb));
            }
        }

        return $video;
    }

    public function update($request, int $id)
    {
        $video = $this->find($id);
        $this->setField($request, $video);
        if (!empty($request->thumbnail) && $request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $fileName = Str::random(3).'-'.str_replace(' ', '-', $file->getClientOriginalName());
        }

        $video->thumbnail = !empty($request->thumbnail) ? $fileName : $video->thumbnail;
        $video->save();

        if (!empty($request->thumbnail)) {
            Storage::delete(config('custom.images.path.gallery_video').
                $video->playlist_id.'/thumbnail/'.$request->old_thumbnail);
            Storage::put(config('custom.images.path.gallery_video').$video->playlist_id.'/thumbnail/'.
                $fileName, file_get_contents($file));
        }

        return $video;
    }

    public function setField($request, $video)
    {
        foreach ($this->lang->getAllLang() as $key => $value) {
            $title[$value->iso_codes] = ($request->input('title_'.$value->iso_codes) == null) ?
                $request->input('title_'.App::getLocale()) : $request->input('title_'.$value->iso_codes);
            $description[$value->iso_codes] = ($request->input('description_'.$value->iso_codes) == null) ?
                $request->input('description_'.App::getLocale()) : $request->input('description_'.$value->iso_codes);
        }
        $video->title = $title;
        $video->description = $description;
        $video->updated_by = auth()->user()->id;

        return $video;
    }

    public function position(int $id, int $position)
    {
        if ($position >= 1) {

            $video = $this->find($id);
            $this->model->where('position', $position)->update([
                'position' => $video->position,
            ]);
            $video->position = $position;
            $video->save();

            return $video;
        }
    }

    public function sort(int $id, $position)
    {
        $find = $this->find($id);

        $video = $this->model->where('id', $id)
                ->where('playlist_id', $find->playlist_id)->update([
            'position' => $position
        ]);

        return $video;
    }

    public function delete(int $id)
    {
        $video = $this->find($id);
        if (!empty($video->file)) {
            Storage::delete(config('custom.images.path.gallery_video').$video->playlist_id.'/'.$video->file);
            if (!empty($video->thumbnail)) {
                Storage::delete(config('custom.images.path.gallery_video').$video->playlist_id.'/thumbnail/'.$video->thumbnail);
            }
        }
        $video->delete();

        return $video;
    }
}
