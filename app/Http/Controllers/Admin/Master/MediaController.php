<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\MediaRequest;
use App\Services\Content\PostService;
use App\Services\Master\MediaService;
use App\Services\PageService;
use Illuminate\Http\Request;

class MediaController extends Controller
{
    private $service, $servicePage, $servicePost;

    public function __construct(
        MediaService $service,
        PageService $servicePage,
        PostService $servicePost
    )
    {
        $this->service = $service;
        $this->servicePage = $servicePage;
        $this->servicePost = $servicePost;
    }

    public function index(Request $request, $id, $module)
    {
        $data['module'] = $this->checkModule($id, $module);

        if ($module == 'post') {
            $data['routeStore'] = route('media.store', [
                'id' => $request->segment(4),
                'module' => $request->segment(5),
                'sectionId' => $request->get('section'),
            ]);
            $data['routeBack'] = route($request->segment(5).'.index', ['sectionId' => $request->get('section')]);
        } else {
            $data['routeStore'] = route('media.store', [
                'id' => $request->segment(4),
                'module' => $request->segment(5),
            ]);
            $data['routeBack'] = route($request->segment(5).'.index');
        }

        return view('backend.master.media.index', compact('data'), [
            'title' => 'Media',
            'breadcrumbs' => [
                'Data Master' => '',
                'Media' => '',
            ]
        ]);
    }

    public function create(Request $request, $id, $module)
    {
        if ($module == 'post') {
            $data['routeStore'] = route('media.store', [
                'id' => $request->segment(4),
                'module' => $request->segment(5),
                'sectionId' => $request->get('sectionId'),
            ]);
            $data['routeBack'] = route('media.index', ['id' => $id, 'module' => $module, 'section' => $request->get('sectionId')]);
        } else {
            $data['routeStore'] = route('media.store', [
                'id' => $request->segment(4),
                'module' => $request->segment(5),
            ]);
            $data['routeBack'] = route('media.index', ['id' => $id, 'module' => $module]);
        }

        return view('backend.master.media.form', compact('data'), [
            'title' => 'Media - Create',
            'breadcrumbs' => [
                'Data Master' => '',
                'Media' => $data['routeBack'],
                'Create' => '',
            ]
        ]);
    }

    public function store(MediaRequest $request, $id, $module)
    {
        $model = $this->checkModule($id, $module);

        $this->service->store($request, $id, $model, $module);

        $redir = $this->redirectForm($request, $id, $module);
        return $redir->with('success', 'media successfully added');
    }

    public function edit(Request $request, $moduleId, $module, $id)
    {
        $data['media'] = $this->service->find($id);

        if ($module == 'post') {
            $data['routeUpdate'] = route('media.update', [
                'id' => $request->segment(4),
                'module' => $request->segment(5),
                'mediaId' => $id,
                'sectionId' => $request->get('sectionId'),
            ]);
            $data['routeBack'] = route('media.index', ['id' => $moduleId, 'module' => $module, 'section' => $request->get('sectionId')]);
        } else {
            $data['routeUpdate'] = route('media.update', [
                'id' => $request->segment(4),
                'module' => $request->segment(5),
                'mediaId' => $id,
            ]);
            $data['routeBack'] = route('media.index', ['id' => $moduleId, 'module' => $module]);
        }

        return view('backend.master.media.form', compact('data'), [
            'title' => 'Media - Edit',
            'breadcrumbs' => [
                'Data Master' => '',
                'Media' => $data['routeBack'],
                'Edit' => '',
            ]
        ]);
    }

    public function update(MediaRequest $request, $moduleId, $module, $id)
    {
        $this->service->update($request, $id);

        $redir = $this->redirectForm($request, $moduleId, $module);
        return $redir->with('success', 'media successfully added');
    }

    public function sort()
    {
        $i = 0;

        foreach ($_POST['datas'] as $value) {
            $i++;
            $this->service->sort($value, $i);
        }
    }

    public function destroy($id)
    {
        $this->service->delete($id);

        return response()->json([
            'success' => 1,
            'message' => ''
        ], 200);
    }

    public function checkModule($id, $module)
    {
        if ($module == 'page') {
            $model = $this->servicePage->find($id);
        }

        if ($module == 'post') {
            $model = $this->servicePost->find($id);
        }

        return $model;
    }

    public function redirectForm($request, $id, $module)
    {
        $redir = redirect()->route('media.index', ['id' => $id, 'module' => $module, 'section' => $request->get('sectionId')]);
        if ($request->action == 'back') {
            $redir = back();
        }

        return $redir;
    }
}
