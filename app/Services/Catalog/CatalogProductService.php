<?php

namespace App\Services\Catalog;

use App\Models\Catalog\CatalogProduct;
use App\Services\LanguageService;
use App\Services\Master\TagService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CatalogProductService
{
    private $model, $lang, $tags;

    public function __construct(
        CatalogProduct $model,
        LanguageService $lang,
        TagService $tags
    )
    {
        $this->model = $model;
        $this->lang = $lang;
        $this->tags = $tags;

        $this->custom_view = config('custom.resource.path.catalog_products');
    }

    public function getCatalogProductList($request, int $categoryId)
    {
        $query = $this->model->query();

        $query->where('catalog_category_id', $categoryId);
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

        $result = $query->orderBy('position', 'ASC')->paginate($limit);

        return $result;
    }

    public function getCatalogProduct($request = null, $withPaginate = null, $limit = null, $isCategory = null)
    {
        $query = $this->model->query();

        if (!empty($isCategory)) {
            $query->where('catalog_category_id', $isCategory);
        }

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
                $result = $query->get();
            }
        }

        return $result;
    }

    public function latestCatalogProduct(int $id, $limit = 8, $content = null)
    {
        $find = $this->find($id);

        $query = $this->model->query();

        $query->publish();
        if (auth()->guard()->check() == false) {
            $query->public();
        }

        if ($content == 'category') {
            $query->where('catalog_category_id', $find->category_id);
        }

        $query->whereNotIn('id', [$id]);

        $result = $query->inRandomOrder()->limit($limit)->get();

        return $result;
    }

    public function catalogProductPrevNext(int $id, $limit = 1, $type, $isCategory = null)
    {
        $find = $this->find($id);

        $query = $this->model->query();

        $query->publish();
        if (auth()->guard()->check() == false) {
            $query->public();
        }

        if ($isCategory == 'category') {
            $query->where('catalog_category_id', $find->category_id);
        }

        if ($type == 'prev') {
            $query->where('id', '<', $id);
        }

        if ($type == 'next') {
            $query->where('id', '>', $id);
        }

        $query->whereNotIn('id', [$id]);

        $result = $query->inRandomOrder()->limit($limit)->get();

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

    public function countCatalogProduct()
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

    public function store($request, int $categoryId)
    {
        $product = new CatalogProduct;
        $product->catalog_category_id = $categoryId;
        //field
        $this->setField($request, $product);
        $product->position = $this->model->where('catalog_category_id', $categoryId)->max('position') + 1;
        $product->created_by = auth()->user()->id;
        $product->save();

        if (!empty($request->tags)) {
            $this->tags->tagsTypeInput($request, $product);
        }

        return $product;
    }

    public function update($request, int $id)
    {
        $product = $this->find($id);
        //field
        $this->setField($request, $product);
        if ($product->field->count() > 0) {
            $field = [];
            foreach ($product->field as $key) {
                $field[$key->name] = $request->input('field_'.$key->name) ?? null;
            }
            $product->custom_field = $field;
        }
        $product->save();

        if (!empty($request->tags)) {
            $product->tags()->delete();
            $this->tags->tagsTypeInput($request, $product);
        }

        return $product;
    }

    public function setField($request, $product)
    {
        foreach ($this->lang->getAllLang() as $key => $value) {
            $title[$value->iso_codes] = ($request->input('title_'.$value->iso_codes) == null) ?
                $request->input('title_'.App::getLocale()) : $request->input('title_'.$value->iso_codes);
            $intro[$value->iso_codes] = ($request->input('intro_'.$value->iso_codes) == null) ?
                $request->input('intro_'.App::getLocale()) : $request->input('intro_'.$value->iso_codes);
            $content[$value->iso_codes] = ($request->input('content_'.$value->iso_codes) == null) ?
                $request->input('content_'.App::getLocale()) : $request->input('content_'.$value->iso_codes);
        }

        $product->slug = str_replace('...', '', Str::limit(Str::slug($request->slug, '-'), 50));
        $product->title = $title;
        $product->intro = $intro;
        $product->content = $content;
        $product->price = $request->price ?? null;
        $product->quantity = $request->quantity ?? null;
        $product->is_discount = (bool)$request->is_discount;
        if ((bool)$request->is_discount == 1) {
            $product->discount = $request->discount ?? null;
        } else {
            $product->discount = null;
        }
        $product->cover = [
            'file_path' => str_replace(url('/storage/filemanager/'), '', $request->cover_file) ?? null,
            'title' => $request->cover_title ?? null,
            'alt' => $request->cover_alt ?? null,
        ];
        $product->banner = [
            'file_path' => str_replace(url('/storage/filemanager/'), '', $request->banner_file) ?? null,
            'title' => $request->banner_title ?? null,
            'alt' => $request->banner_alt ?? null,
        ];
        $product->created_at = $request->created_at;
        $product->publish = (bool)$request->publish;
        $product->public = (bool)$request->public;
        $product->is_widget = (bool)$request->is_widget;
        $product->custom_view_id = $request->custom_view_id ?? null;
        $product->meta_data = [
            'title' => $request->meta_title ?? null,
            'description' => $request->meta_description ?? null,
            'keywords' => $request->meta_keywords ?? null,
        ];
        $product->updated_by = auth()->user()->id;

        return $product;
    }

    public function publish(int $id)
    {
        $product = $this->find($id);
        $product->publish = !$product->publish;
        $product->save();

        return $product;
    }

    public function selection(int $id)
    {
        $product = $this->find($id);
        $total = $this->model->where('catalog_category_id', $product->catalog_category_id)->selection()->count();
        $select = $product->category->product_selection;

        if ($product->selection == 0) {
            $check = (empty($select) || !empty($select) && $total < $select);
        } else {
            $check = (empty($select) || !empty($select));
        }

        if ($check) {
            $product->selection = !$product->selection;
            $product->save();

            return true;
        } else {
            return false;
        }
    }

    public function position(int $id, int $position)
    {
        if ($position >= 1) {

            $product = $this->find($id);
            $this->model->where('position', $position)->update([
                'position' => $product->position,
            ]);
            $product->position = $position;
            $product->save();

            return $product;
        }
    }

    public function recordViewer(int $id)
    {
        $product = $this->find($id);
        $product->viewer = ($product->viewer+1);
        $product->save();

        return $product;
    }

    public function delete(int $id)
    {
        $product = $this->find($id);

        $product->field()->delete();
        if ($product->images()->count() > 0) {
            foreach ($product->images as $image) {
                Storage::delete(config('custom.images.path.catalog_product').$id.'/'.$image->file);
                if (!empty($image->thumbnail)) {
                    Storage::delete(config('custom.images.path.catalog_product').$id.'/thumbnail/'.$image->thumbnail);
                }
            }
            Storage::deleteDirectory(config('custom.images.path.catalog_product').$id);
        }
        $product->images()->delete();
        $product->delete();

        return $product;
    }
}
