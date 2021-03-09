<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageRequest;
use App\Services\LanguageService;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    private $service;

    public function __construct(LanguageService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $l = '';
        $s = '';
        $q = '';
        $trash = '';
        if (isset($request->l) || isset($request->s) || isset($request->q) || isset($request->is_trash)) {
            $l = '?l='.$request->l;
            $s = '&s='.$request->s;
            $q = '&q='.$request->q;
            $trash = '&trash='.$request->is_trash;
        }

        $data['languages'] = $this->service->getLanguageList($request);
        $data['no'] = $data['languages']->firstItem();
        $data['languages']->withPath(url()->current().$l.$s.$q.$trash);

        return view('backend.languages.index', compact('data'), [
            'title' => 'Languages',
            'breadcrumbs' => [
                'Languages' => '',
            ]
        ]);
    }

    public function create()
    {
        return view('backend.languages.form', [
            'title' => 'Language - Create',
            'breadcrumbs' => [
                'Language' => route('language.index'),
                'Create' => '',
            ]
        ]);
    }

    public function store(LanguageRequest $request)
    {
        $this->service->store($request);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'language successfully added');
    }

    public function edit($id)
    {
        $data['language'] = $this->service->find($id);

        return view('backend.languages.form', compact('data'), [
            'title' => 'Language - Edit',
            'breadcrumbs' => [
                'Language' => route('language.index'),
                'Edit' => '',
            ]
        ]);
    }

    public function update(LanguageRequest $request, $id)
    {
        $this->service->update($request, $id);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'language successfully updated');
    }

    public function status($id)
    {
        $this->service->status($id);

        return back()->with('success', 'update status language successfully');
    }

    public function softDelete($id)
    {
        $delete = $this->service->softDelete($id);

        if ($delete == true) {
            return response()->json([
                'success' => 1,
                'message' => ''
            ], 200);
        }  else {
            return response()->json([
                'success' => 0,
                'message' => 'Language default cannot deleted'
            ], 200);
        }
    }

    public function permanentDelete(Request $request, $id)
    {
        $delete = $this->service->permanentDelete($request, $id);

        if ($delete == true) {
            return response()->json([
                'success' => 1,
                'message' => ''
            ], 200);
        } else {
            return response()->json([
                'success' => 0,
                'message' => 'Language default cannot deleted'
            ], 200);
        }
    }

    public function restore($id)
    {
        $this->service->restore($id);

        return back()->with('success', 'restore user successfully');
    }

    public function redirectForm($request)
    {
        $redir = redirect()->route('language.index');
        if ($request->action == 'back') {
            $redir = back();
        }

        return $redir;
    }
}
