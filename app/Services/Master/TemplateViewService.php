<?php

namespace App\Services\Master;

use App\Models\Catalog\CatalogCategory;
use App\Models\Catalog\CatalogProduct;
use App\Models\Content\Category;
use App\Models\Content\Post\Post;
use App\Models\Content\Section;
use App\Models\Gallery\Album;
use App\Models\Gallery\Playlist;
use App\Models\Link\Link;
use App\Models\Master\TemplateView;
use App\Models\Page;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TemplateViewService
{
    private $model;

    public function __construct(
        TemplateView $model
    )
    {
        $this->model = $model;
    }

    public function getTemplateList($request)
    {
        $query = $this->model->query();

        $query->when($request->m, function ($query, $m) {
            $query->where('module', $m);
        })->when($request->q, function ($query, $q) {
            $query->where(function ($query) use ($q) {
                $query->where('name', 'like', '%'.$q.'%');
            });
        });

        $limit = 20;
        if (!empty($request->l)) {
            $limit = $request->l;
        }

        $result = $query->orderBy('id', 'ASC')->paginate($limit);

        return $result;
    }

    public function getTemplate($module, $type = 0)
    {
        $query = $this->model->query();

        $query->where('module', $module)->where('type', $type);

        $result = $query->get();

        return $result;
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function store($request)
    {
        if ($request->module == 1 || $request->module == 2) {
            $type = ($request->type == 0) ? 1 : $request->type;
        } else {
            $type = ($request->type > 0) ? 0 : $request->type;
        }

        $template = new TemplateView;
        $template->name = $request->name;
        $template->module = $request->module;
        $template->type = $type;
        $template->file_path = str_replace('.blade.php', '', $this->checkModule($request, $type, $request->module));
        $template->content = $request->content ?? null;
        $template->created_by = auth()->user()->id;
        $template->updated_by = auth()->user()->id;
        $template->save();

        return $template;
    }

    public function update($request, int $id)
    {
        $template = $this->find($id);
        $template->name = $request->name;
        $template->content = $request->content ?? null;
        $template->save();

        return $template;
    }

    public function checkModule($request, $type, $module)
    {
        $templateType = config('custom.label.template_type.'.$type);
        $templateModule = config('custom.label.template_module.'.$module);
        $resource = config('custom.resource.path.'.$templateModule);

        $filePath = $resource['full'].$resource[$templateType].'/'.Str::slug($request->filename, '-').'.blade.php';

        if (!file_exists(resource_path($filePath))) {

            File::makeDirectory(resource_path($resource['full'].$resource[$templateType]), $mode = 0777, true, true);
            $path = resource_path($filePath);
            File::put($path, '');
        }

        return $filePath;
    }

    public function delete(int $id)
    {
        $template = $this->find($id);

        $page = Page::where('custom_view_id', $id)->count();
        $section = Section::where('list_view_id', $id)->orWhere('detail_view_id', $id)->count();
        $category = Category::where('list_view_id', $id)->orWhere('detail_view_id', $id)->count();
        $post = Post::where('custom_view_id', $id)->count();
        $catalogCategory = CatalogCategory::where('custom_view_id', $id)->count();
        $catalogProduct = CatalogProduct::where('custom_view_id', $id)->count();
        $album = Album::where('custom_view_id', $id)->count();
        $playlist = Playlist::where('custom_view_id', $id)->count();
        $link = Link::where('custom_view_id', $id)->count();

        if ($page == 0 || $section == 0 || $category == 0 || $post == 0 ||
            $catalogCategory == 0 || $catalogProduct == 0 || $album == 0 ||
            $playlist == 0 || $link == 0) {

            $path = resource_path($template->file_path);
            File::delete($path);

            $template->delete();

            return true;

        } else {
            return false;
        }
    }
}
