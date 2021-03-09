<?php

namespace App\Services\Content;

use App\Models\Content\Post\Post;
use App\Models\Content\Post\PostFile;
use App\Models\Content\Post\PostProfile;
use App\Services\LanguageService;
use App\Services\Master\TagService;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostService
{
    private $model, $modelFile, $modelProfile, $lang, $section, $tags;

    public function __construct(
        Post $model,
        LanguageService $lang,
        PostFile $modelFile,
        PostProfile $modelProfile,
        SectionService $section,
        TagService $tags
    )
    {
        $this->model = $model;
        $this->lang = $lang;
        $this->modelFile = $modelFile;
        $this->modelProfile = $modelProfile;
        $this->section = $section;
        $this->tags = $tags;

        $this->custom_view = config('custom.resource.path.posts');
    }

    public function getPostList($request, int $sectionId)
    {
        $section = $this->section->find($sectionId);

        $query = $this->model->query();

        $query->where('section_id', $sectionId);
        $query->when($request->c, function ($query, $c) {
            $query->where('category_id', $c);
        })->when($request->s, function ($query, $s) {
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

        $result = $query->orderBy($section->order_field, $section->order_by)->paginate($limit);

        return $result;
    }

    public function getPost($request = null, $withPaginate = null, $limit = null , $type = null, $idType = null)
    {
        $orderField = 'created_at';
        $orderBy = 'DESC';

        $query = $this->model->query();

        if ($type == 'section') {
            $section = $this->section->find($idType);
            $query->where('section_id', $idType);

            $orderField = $section->order_field;
            $orderBy = $section->order_by;
        }

        if ($type == 'category') {
            $query->where('category_id', $idType);
        }

        $query->publish();
        if (auth()->guard()->check() == false) {
            $query->public();
        }

        if (!empty($request)) {
            $this->search($query, $request);
        }

        if (!empty($withPaginate)) {
            $result = $query->orderBy($orderField, $orderBy)->paginate($limit);
        } else {
            if (!empty($limit)) {
                $result = $query->orderBy($orderField, $orderBy)->limit($limit)->get();
            } else {
                $result = $query->orderBy($orderField, $orderBy)->get();
            }

        }

        return $result;
    }

    public function latestPost(int $id, $limit = 8, $content = null)
    {
        $find = $this->find($id);
     
        $query = $this->model->query();
       
        $query->publish();

        if (auth()->guard()->check() == false) {
            $query->public();
        }
        
        if ($content == 'all') {
            $query->where('section_id', $find->section_id)->where('category_id', $find->category_id);
        }

        if ($content == 'section') {
            $query->where('section_id', $find->section_id);
            
        }

        if ($content == 'category') {
            $query->where('category_id', $find->category_id);
        }

        
        $query->whereNotIn('id', [$id]);
        $result = $query->inRandomOrder()->limit($limit)->get();
        
        return $result;
    }

    public function postPrevNext(int $id, $limit = 1, $type, $content = null)
    {
        $find = $this->find($id);

        $query = $this->model->query();

        $query->publish();
        if (auth()->guard()->check() == false) {
            $query->public();
        }

        if ($content == 'all') {
            $query->where('section_id', $find->section_id)->where('category_id', $find->category_id);
        }

        if ($content == 'section') {
            $query->where('section_id', $find->section_id);
        }

        if ($content == 'category') {
            $query->where('category_id', $find->category_id);
        }

        if ($type == 'prev') {
            $query->where('position', '<', $id);
        }

        if ($type == 'next') {
            $query->where('position', '>', $id);
        }

        $query->whereNotIn('id', [$id]);

        $result = $query->inRandomOrder()->limit($limit)->get();

        return $result;
    }

    public function search($query, $request)
    {
        $query->when($request->section_id, function ($query, $section_id) {
            $query->where('section_id', $section_id);
        })->when($request->category_id, function ($query, $category_id) {
            $query->where('category_id', $category_id);
        })->when($request->keyword, function ($query, $q) {
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

    public function countPost()
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

    public function store($request, int $sectionId)
    {
        $section = $this->section->find($sectionId);

        $post = new Post;
        $post->section_id = $sectionId;
        //field
        $this->setField($request, $post);
        $post->position = $this->model->where('section_id', $sectionId)->max('position') + 1;
        $post->created_by = auth()->user()->id;
        $post->save();

        if ($section->extra == 1) {
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $value) {

                    $file = new PostFile;
                    $file->post_id = $post->id;
                    $file->file = Str::random(3).'-'.str_replace(' ', '-', $value->getClientOriginalName());
                    $file->file_type = $value->getClientOriginalExtension();
                    $file->file_size = $value->getSize();
                    $file->save();

                    Storage::put(config('custom.images.path.extra_file').$post->id.'/'.$value->getClientOriginalName(), file_get_contents($value));
                }
            }
        }

        if ($section->extra == 2) {
            $profile = new PostProfile;
            $profile->post_id = $post->id;
            $field = [];
            foreach (config('custom.columns.columns_profile_for_post') as $key => $value) {
                $field[$value['name']] = $request->input('profile_'.$value['name']) ?? null;
            }
            $profile->field = $field;
            $profile->save();
        }

        if (!empty($request->tags)) {
            $this->tags->tagsTypeInput($request, $post);
        }

        return $post;
    }

    public function update($request, int $id)
    {
        $post = $this->find($id);
        //field
        $this->setField($request, $post);
        if ($post->field->count() > 0) {
            $field = [];
            foreach ($post->field as $key) {
                $field[$key->name] = $request->input('field_'.$key->name) ?? null;
            }
            $post->custom_field = $field;
        }
        $post->save();

        if ($post->section->extra == 1) {
            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $value) {
                    $file = new PostFile;
                    $file->post_id = $post->id;
                    $file->file = $value->getClientOriginalName();
                    $file->file_type = $value->getClientOriginalExtension();
                    $file->file_size = $value->getSize();
                    $file->save();

                    Storage::put(config('custom.images.path.extra_file').$post->id.'/'.$value->getClientOriginalName(), file_get_contents($value));
                }
            }
        }

        if ($post->section->extra == 2) {
            $profile = $post->profile;
            $field2 = [];
            foreach (config('custom.columns.columns_profile_for_post') as $key => $value) {
                $field2[$value['name']] = $request->input('profile_'.$value['name']) ?? null;
            }
            $profile->field = $field2;
            $profile->save();
        }

        if (!empty($request->tags)) {
            $post->tags()->delete();
            $this->tags->tagsTypeInput($request, $post);
        }

        return $post;
    }

    public function setField($request, $post)
    {
        foreach ($this->lang->getAllLang() as $key => $value) {
            $title[$value->iso_codes] = ($request->input('title_'.$value->iso_codes) == null) ?
                $request->input('title_'.App::getLocale()) : $request->input('title_'.$value->iso_codes);
            $intro[$value->iso_codes] = ($request->input('intro_'.$value->iso_codes) == null) ?
                $request->input('intro_'.App::getLocale()) : $request->input('intro_'.$value->iso_codes);
            $content[$value->iso_codes] = ($request->input('content_'.$value->iso_codes) == null) ?
                $request->input('content_'.App::getLocale()) : $request->input('content_'.$value->iso_codes);
        }

        $post->category_id = $request->category_id;
        $post->slug = str_replace('...', '', Str::limit(Str::slug($request->slug, '-'), 50));
        $post->title = $title;
        $post->intro = $intro;
        $post->content = $content;
        $post->cover = [
            'file_path' => str_replace(url('/storage/filemanager/'), '', $request->cover_file) ?? null,
            'title' => $request->cover_title ?? null,
            'alt' => $request->cover_alt ?? null,
        ];
        $post->banner = [
            'file_path' => str_replace(url('/storage/filemanager/'), '', $request->banner_file) ?? null,
            'title' => $request->banner_title ?? null,
            'alt' => $request->banner_alt ?? null,
        ];
        $post->created_at = $request->created_at;
        $post->publish_year = Carbon::parse($request->created_at)->format('Y');
        $post->publish_month = Carbon::parse($request->created_at)->format('m');
        $post->publish = (bool)$request->publish;
        $post->public = (bool)$request->public;
        $post->is_widget = (bool)$request->is_widget;
        $post->custom_view_id = $request->custom_view_id ?? null;
        $post->meta_data = [
            'title' => $request->meta_title ?? null,
            'description' => $request->meta_description ?? null,
            'keywords' => $request->meta_keywords ?? null,
        ];
        $post->updated_by = auth()->user()->id;

        return $post;
    }

    public function publish(int $id)
    {
        $post = $this->find($id);
        $post->publish = !$post->publish;
        $post->save();

        return $post;
    }

    public function selection(int $id)
    {
        $post = $this->find($id);
        $total = $this->model->where('section_id', $post->section_id)->selection()->count();
        $select = $post->section->post_selection;

        if ($post->selection == 0) {
            $check = (empty($select) || !empty($select) && $total < $select);
        } else {
            $check = (empty($select) || !empty($select));
        }

        if ($check) {
            $post->selection = !$post->selection;
            $post->save();

            return true;
        } else {
            return false;
        }
    }

    public function position(int $id, int $position)
    {
        if ($position >= 1) {

            $post = $this->find($id);
            $this->model->where('position', $position)->update([
                'position' => $post->position,
            ]);
            $post->position = $position;
            $post->save();

            return $post;
        }
    }

    public function recordViewer(int $id)
    {
        $post = $this->find($id);
        $post->viewer = ($post->viewer+1);
        $post->save();

        return $post;
    }

    public function delete(int $id)
    {
        $post = $this->find($id);

        $post->field()->delete();
        $post->media()->delete();
        if ($post->files()->count() > 0) {
            foreach ($post->files as $file) {
                Storage::delete(config('custom.images.path.extra_file').$id.'/'.$file->file);
            }
        }
        Storage::deleteDirectory(config('custom.images.path.extra_file').$id);
        $post->files()->delete();
        $post->profile()->delete();
        $post->delete();

        return $post;
    }

    public function deleteFile(int $id)
    {
        $file = $this->modelFile->findOrFail($id);
        Storage::delete(config('custom.images.path.extra_file').$file->post_id.'/'.$file->file);
        $file->delete();

        return $file;
    }
}
