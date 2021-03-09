<?php

namespace App\Services\Master;

use App\Models\Content\Post\Post;
use App\Models\Master\Media;
use App\Models\Page;
use Illuminate\Support\Str;

class MediaService
{
    private $model;

    public function __construct(
        Media $model
    )
    {
        $this->model = $model;
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function store($request, int $id, $model, $module)
    {
        if ($module == 'page') {
            $classes = Page::class;
        }

        if ($module == 'post') {
            $classes = Post::class;
        }

        $media = new Media;
        if ((bool)$request->is_youtube == 0) {
            $media->file_path = [
                'filename' => str_replace(url('/storage/filemanager/'), '', $request->filename),
                'thumbnail' => str_replace(url('/storage/filemanager/'), '', $request->thumbnail),
            ];
        } else {
            $media->file_path = [
                'filename' => null,
                'thumbnail' => null,
            ];
        }

        if ((bool)$request->is_youtube == 1) {
            $media->youtube_id = $request->youtube_id;
        } else {
            $media->youtube_id = null;
        }
        $media->caption = [
            'title' => $request->title ?? null,
            'description' => $request->description ?? null,
        ];
        $media->position = $this->model->where('mediable_id', $id)
            ->where('mediable_type', $classes)->max('position') + 1;
        $media->is_youtube = (bool)$request->is_youtube;
        $media->created_by = auth()->user()->id;
        $media->updated_by = auth()->user()->id;
        $media->mediable()->associate($model);
        $media->save();

        return $media;
    }

    public function update($request, int $id)
    {
        $media = $this->find($id);
        if ((bool)$request->is_youtube == 0) {
            $media->file_path = [
                'filename' => str_replace(url('/storage/filemanager/'), '', $request->filename),
                'thumbnail' => str_replace(url('/storage/filemanager/'), '', $request->thumbnail),
            ];
        } else {
            $media->file_path = [
                'filename' => null,
                'thumbnail' => null,
            ];
        }

        if ((bool)$request->is_youtube == 1) {
            $media->youtube_id = $request->youtube_id;
        } else {
            $media->youtube_id = null;
        }
        $media->caption = [
            'title' => $request->title ?? null,
            'description' => $request->description ?? null,
        ];
        $media->is_youtube = (bool)$request->is_youtube;
        $media->updated_by = auth()->user()->id;
        $media->save();

        return $media;
    }

    public function sort(int $id, $position)
    {
        $find = $this->find($id);

        $media = $this->model->where('id', $id)
                ->where('mediable_id', $find->mediable_id)
                ->where('mediable_type', $find->mediable_type)->update([
            'position' => $position
        ]);

        return $media;
    }

    public function delete(int $id)
    {
        $media = $this->find($id);
        $media->delete();

        return $media;
    }
}
