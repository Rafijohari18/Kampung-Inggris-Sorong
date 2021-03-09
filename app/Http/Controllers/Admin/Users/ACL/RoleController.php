<?php

namespace App\Http\Controllers\Admin\Users\ACL;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\ACL\RoleRequest;
use App\Services\Users\ACL\PermissionService;
use App\Services\Users\ACL\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    private $service, $servicePermission;

    public function __construct(
        RoleService $service,
        PermissionService $servicePermission
    )
    {
        $this->service = $service;
        $this->servicePermission = $servicePermission;
    }

    public function index(Request $request)
    {
        $l = '';
        $q = '';
        if (isset($request->l) || isset($request->q)) {
            $l = '?l='.$request->l;
            $q = '&q='.$request->q;
        }

        $data['roles'] = $this->service->getRoleList($request);
        $data['no'] = $data['roles']->firstItem();
        $data['roles']->withPath(url()->current().$l.$q);

        return view('backend.users.acl.role.index', compact('data'), [
            'title' => 'Roles',
            'breadcrumbs' => [
                'Management Users' => '',
                'Access Control' => '',
                'Roles' => '',
            ]
        ]);
    }

    public function permission(Request $request, $id)
    {
        $data['role'] = $this->service->find($id);

        $collectPermission = collect($data['role']->permissions);
        $data['permission_id'] = $collectPermission->map(function($item, $key) {
            return $item->id;
        })->all();

        $data['permission'] = $this->servicePermission
            ->getPermissionList($request, false);

        return view('backend.users.acl.role.set-permission', compact('data'), [
            'title' => 'Roles - Set Permission',
            'breadcrumbs' => [
                'Management Users' => '',
                'Access Control' => '',
                'Roles' => route('role.index'),
                'Set Permission' => ''
            ]
        ]);
    }

    public function store(RoleRequest $request)
    {
        $this->service->store($request);

        return back()->with('success', 'role successfully added');
    }

    public function update(RoleRequest $request, $id)
    {
        $this->service->update($request, $id);

        return back()->with('success', 'role successfully updated');
    }

    public function setPermission(Request $request, $id)
    {
        $this->service->setPermission($request, $id);

        $redir = redirect()->route('role.index');
        if ($request->action == 'back') {
            $redir = back();
        }
        
        return $redir->with('success', 'permission successfully given');
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
                'message' => 'cannot delete default role'
            ], 200);
        }
        
    }
}
