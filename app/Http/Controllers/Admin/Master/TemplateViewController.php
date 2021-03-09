<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\TemplateViewRequest;
use App\Services\Master\TemplateViewService;
use Illuminate\Http\Request;

class TemplateViewController extends Controller
{
    private $service;

    public function __construct(TemplateViewService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $l = '';
        $m = '';
        $q = '';
        if (isset($request->l) || isset($request->m) || isset($request->q)) {
            $l = '?l='.$request->l;
            $m = '&m='.$request->m;
            $q = '&q='.$request->q;
        }

        $data['template'] = $this->service->getTemplateList($request);
        $data['no'] = $data['template']->firstItem();
        $data['template']->withPath(url()->current().$l.$m.$q);

        return view('backend.master.template.index', compact('data'), [
            'title' => 'Template',
            'breadcrumbs' => [
                'Data Master' => '',
                'Template' => '',
            ]
        ]);
    }

    public function store(TemplateViewRequest $request)
    {
        $this->service->store($request);

        return back()->with('success', 'template successfully added');
    }

    public function update(TemplateViewRequest $request, $id)
    {
        $this->service->update($request, $id);

        return back()->with('success', 'template successfully updated');
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
                'message' => 'cannot delete template that are already used'
            ], 200);
        }
    }
}
