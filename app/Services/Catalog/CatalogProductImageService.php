<?php

namespace App\Services\Catalog;

use App\Models\Catalog\CatalogProductImage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CatalogProductImageService
{
    private $model;

    public function __construct(CatalogProductImage $model)
    {
        $this->model = $model;
    }

    public function getImageList($request, int $productId)
    {
        $query = $this->model->query();

        $query->where('catalog_product_id', $productId);
        $query->when($request->q, function ($query, $q) {
            $query->where(function ($query) use ($q) {
                $query->where('file', 'like', '%'.$q.'%')
                    ->orWhere('meta_file->title', 'like', '%'.$q.'%')
                    ->orWhere('meta_file->description', 'like', '%'.$q.'%');
            });
        });

        $limit = 6;
        if (!empty($request->l)) {
            $limit = $request->l;
        }

        $result = $query->orderBy('position', 'ASC')->paginate($limit);

        return $result;
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function store($request, int $productId)
    {
        if ($request->hasFile('file')) {

            $file = $request->file('file');
            $fileName = Str::random(3).'-'.str_replace(' ', '-', $file->getClientOriginalName());
            $extesion = $file->getClientOriginalExtension();

            if (!empty($request->thumbnail) && $request->hasFile('thumbnail') && $extesion == 'webm' || $extesion == 'mp4') {
                $fileThumb = $request->file('thumbnail');
                $fileNameThumb = Str::random(3).'-'.str_replace(' ', '-', $fileThumb->getClientOriginalName());
            }

            $image = new CatalogProductImage;
            $image->catalog_product_id = $productId;
            if ($extesion == 'webm' || $extesion == 'mp4') {
                $image->is_video = 1;
                $image->thumbnail = !empty($request->thumbnail) ? $fileNameThumb : null;
            }
            $image->file = $fileName;
            $image->meta_file = [
                'title' => $request->title ?? null,
                'description' => $request->description ?? null,
                'alt' => $request->alt ?? null,
            ];
            $image->position = $this->model->where('catalog_product_id', $productId)->max('position') + 1;
            $image->created_by = auth()->user()->id;
            $image->updated_by = auth()->user()->id;
            $image->save();

            Storage::put(config('custom.images.path.catalog_product').$productId.'/'.$fileName, file_get_contents($file));
            if (!empty($request->thumbnail) && $extesion == 'webm' || $extesion == 'mp4') {
                Storage::put(config('custom.images.path.catalog_product').$productId.'/thumbnail/'.$fileNameThumb, file_get_contents($fileThumb));
            }

            return $image;

        } else {
            return false;
        }
    }

    public function update($request, int $id)
    {
        $image = $this->find($id);

        if (!empty($request->thumbnail) && $request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $fileName = Str::random(3).'-'.str_replace(' ', '-', $file->getClientOriginalName());
        }

        $image->thumbnail = !empty($request->thumbnail) ? $fileName : $image->thumbnail;
        $image->meta_file = [
            'title' => $request->title ?? null,
            'description' => $request->description ?? null,
            'alt' => $request->alt ?? null,
        ];
        $image->save();

        if (!empty($request->thumbnail)) {
            Storage::delete(config('custom.images.path.catalog_product').
                $image->catalog_product_id.'/thumbnail/'.$request->old_thumbnail);
            Storage::put(config('custom.images.path.catalog_product').$image->catalog_product_id.'/thumbnail/'.
                $fileName, file_get_contents($file));
        }

        return $image;
    }

    public function position(int $id, int $position)
    {
        if ($position >= 1) {

            $image = $this->find($id);
            $this->model->where('position', $position)->update([
                'position' => $image->position,
            ]);
            $image->position = $position;
            $image->save();

            return $image;
        }
    }

    public function sort(int $id, $position)
    {
        $find = $this->find($id);

        $image = $this->model->where('id', $id)
            ->where('catalog_product_id', $find->catalog_product_id)->update([
            'position' => $position
        ]);

        return $image;
    }

    public function delete(int $id)
    {
        $image = $this->find($id);

        Storage::delete(config('custom.images.path.catalog_product').
            $image->catalog_product_id.'/'.$image->file);
        if ($image->thumbnail != null) {
            Storage::delete(config('custom.images.path.catalog_product').
                $image->catalog_product_id.'/thumbnail/'.$image->thumbnail);
        }
        $image->delete();

        return true;
    }
}
