<?php

namespace App\Http\Controllers\Admin\Users\ACL;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ACL\PermissionRequest;
use App\Services\Users\ACL\PermissionService;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    private $service;

    public function __construct(PermissionService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $l = '';
        $q = '';
        if (isset($request->l) || isset($request->q)) {
            $l = '?l='.$request->l;
            $q = '&q='.$request->q;
        }

        $data['permissions'] = $this->service->getPermissionList($request);
        $data['no'] = $data['permissions']->firstItem();
        $data['permissions']->withPath(url()->current().$l.$q);

        return view('backend.users.acl.permission.index', compact('data'), [
            'title' => 'Permissions',
            'breadcrumbs' => [
                'Management Users' => '',
                'Access Control' => '',
                'Permissions' => '',
            ]
        ]);
    }

    public function store(PermissionRequest $request)
    {
        $this->service->store($request);

        return back()->with('success', 'permission successfully added');
    }

    public function update(PermissionRequest $request, $id)
    {
        $this->service->update($request, $id);

        return back()->with('success', 'permission successfully updated');
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
                'message' => 'cannot delete default permission'
            ], 200);
        }
    }
}
