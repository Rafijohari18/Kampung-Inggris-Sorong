<?php

namespace App\Http\Controllers\Admin\Content;

use App\Http\Controllers\Controller;
use App\Http\Requests\Content\PostRequest;
use App\Services\Content\CategoryService;
use App\Services\Content\PostService;
use App\Services\Content\SectionService;
use App\Services\LanguageService;
use App\Services\Master\TagService;
use App\Services\Master\TemplateViewService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    private $service, $serviceSection, $serviceCategory, $serviceLang, $serviceTags,
        $serviceTemplate;

    public function __construct(
        PostService $service,
        SectionService $serviceSection,
        CategoryService $serviceCategory,
        LanguageService $serviceLang,
        TagService $serviceTags,
        TemplateViewService $serviceTemplate
    )
    {
        $this->service = $service;
        $this->serviceSection = $serviceSection;
        $this->serviceCategory = $serviceCategory;
        $this->serviceLang = $serviceLang;
        $this->serviceTags = $serviceTags;
        $this->serviceTemplate = $serviceTemplate;
    }

    public function index(Request $request, $sectionId)
    {
        $l = '';
        $c = '';
        $s = '';
        $p = '';
        $q = '';
        if (isset($request->l) || isset($request->c) || isset($request->s) || isset($request->p) || isset($request->q)) {
            $l = '?l='.$request->l;
            $c = '&c='.$request->c;
            $s = '&s='.$request->s;
            $p = '&p='.$request->p;
            $q = '&q='.$request->q;
        }

        $data['posts'] = $this->service->getPostList($request, $sectionId);
        $data['no'] = $data['posts']->firstItem();
        $data['posts']->withPath(url()->current().$l.$p.$q);
        $data['section'] = $this->serviceSection->find($sectionId);
        $data['categories'] = $this->serviceCategory->getCategoryBySection($sectionId);

        return view('backend.content.posts.index', compact('data'), [
            'title' => 'Content - Posts',
            'breadcrumbs' => [
                'Content' => '',
                'Section' => route('section.index'),
                'Posts' => '',
            ]
        ]);
    }

    public function create(Request $request, $sectionId)
    {
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['section'] = $this->serviceSection->find($sectionId);
        $data['categories'] = $this->serviceCategory->getCategoryBySection($sectionId);
        $data['tags'] = $this->serviceTags->getTags();
        $data['template'] = $this->serviceTemplate->getTemplate(3);

        return view('backend.content.posts.form', compact('data'), [
            'title' => 'Post - Create',
            'breadcrumbs' => [
                'Content' => '',
                'Section' => route('section.index'),
                'Post' => route('post.index', ['sectionId' => $sectionId]),
                'Create' => '',
            ],
        ]);
    }

    public function store(PostRequest $request, $sectionId)
    {
        $this->service->store($request, $sectionId);

        $redir = $this->redirectForm($request, $sectionId);
        return $redir->with('success', 'post successfully added');
    }

    public function edit($sectionId, $id)
    {
        $data['languages'] = $this->serviceLang->getAllLang();
        $data['section'] = $this->serviceSection->find($sectionId);
        $data['categories'] = $this->serviceCategory->getCategoryBySection($sectionId);
        $data['tags'] = $this->serviceTags->getTags();
        $data['post'] = $this->service->find($id);
        $data['template'] = $this->serviceTemplate->getTemplate(3);

        $collectTags = collect($data['post']->tags);
        $data['tags_id'] = $collectTags->map(function($item, $key) {
            return $item->tag_id;
        })->all();

        return view('backend.content.posts.form-edit', compact('data'), [
            'title' => 'Post - Edit',
            'breadcrumbs' => [
                'Content' => '',
                'Section' => route('section.index'),
                'Post' => route('post.index', ['sectionId' => $sectionId]),
                'Edit' => ''
            ],
        ]);
    }

    public function update(PostRequest $request, $sectionId, $id)
    {
        $this->service->update($request, $id);

        $redir = $this->redirectForm($request, $sectionId);
        return $redir->with('success', 'post successfully updated');
    }

    public function publish($sectionId, $id)
    {
        $this->service->publish($id);

        return back()->with('success', 'status post changed');
    }

    public function selection($sectionId, $id)
    {
        $post = $this->service->find($id);
        $check = $this->service->selection($id);

        if ($check == true) {
            return back()->with('success', 'Post selected');
        } else {
            return back()->with('warning', 'Cannot select post because post select limited '.$post->section->post_selection);
        }
    }

    public function position($sectionId, $id, $position)
    {
        $this->service->position($id, $position);

        return back()->with('success', 'position post changed');
    }

    public function destroy($sectionId, $id)
    {
        $this->service->delete($id);

        return response()->json([
            'success' => 1,
            'message' => ''
        ], 200);
    }

    public function destroyFile($sectionId, $id)
    {
        $this->service->deleteFile($id);

        return response()->json([
            'success' => 1,
            'message' => ''
        ], 200);
    }

    public function redirectForm($request, $sectionId)
    {
        $redir = redirect()->route('post.index', ['sectionId' => $sectionId]);
        if ($request->action == 'back') {
            $redir = back();
        }

        return $redir;
    }
}
