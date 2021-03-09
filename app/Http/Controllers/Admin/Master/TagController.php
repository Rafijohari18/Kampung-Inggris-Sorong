<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\TagRequest;
use App\Services\Master\TagService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    private $service;

    public function __construct(TagService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $l = '';
        $f = '';
        $s = '';
        $q = '';
        if (isset($request->l) || isset($request->f) || isset($request->s) || isset($request->q)) {
            $l = '?l='.$request->l;
            $f = '&f='.$request->f;
            $s = '&s='.$request->s;
            $q = '&q='.$request->q;
        }

        $data['tags'] = $this->service->getTagsList($request);
        $data['no'] = $data['tags']->firstItem();
        $data['tags']->withPath(url()->current().$l.$f.$s.$q);

        return view('backend.master.tags.index', compact('data'), [
            'title' => 'Tags',
            'breadcrumbs' => [
                'Data Master' => '',
                'Tags' => '',
            ]
        ]);
    }

    public function create()
    {
        return view('backend.master.tags.form', [
            'title' => 'Tag - Create',
            'breadcrumbs' => [
                'Data Master' => '',
                'Tag' => route('tag.index'),
                'Create' => '',
            ]
        ]);
    }

    public function store(TagRequest $request)
    {
        $this->service->store($request);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'tags successfully added');
    }

    public function edit($id)
    {
        $data['tag'] = $this->service->find($id);

        return view('backend.master.tags.form', compact('data'), [
            'title' => 'Tag - Edit',
            'breadcrumbs' => [
                'Data Master' => '',
                'Tag' => route('tag.index'),
                'Edit' => '',
            ]
        ]);
    }

    public function update(TagRequest $request, $id)
    {
        $this->service->update($request, $id);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'tags successfully updated');
    }

    public function flags($id)
    {
        $this->service->flags($id);

        return back()->with('success', 'approve / inapprove tags successfully');
    }

    public function standar($id)
    {
        $this->service->standar($id);

        return back()->with('success', 'update standar tags successfully');
    }

    public function destroy($id)
    {
        $delete = $this->service->delete($id);

        if ($delete == true) {
            return response()->json([
                'success' => 1,
                'message' => ''
            ], 200);
        } else {
            return response()->json([
                'success' => 0,
                'message' => 'cannot delete tags that are already used'
            ], 200);
        }
    }

    public function redirectForm($request)
    {
        $redir = redirect()->route('tag.index');
        if ($request->action == 'back') {
            $redir = back();
        }

        return $redir;
    }
}
