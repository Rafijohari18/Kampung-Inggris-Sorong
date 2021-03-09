<?php

namespace App\Http\Controllers\Admin\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Users\PhotoProfileRequest;
use App\Http\Requests\Users\ProfileRequest;
use App\Http\Requests\Users\UserRequest;
use App\Mail\VerificationMail;
use App\Services\Users\ACL\RoleService;
use App\Services\Users\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    private $service, $serviceRole;

    public function __construct(
        UserService $service,
        RoleService $serviceRole
    )
    {
        $this->service = $service;
        $this->serviceRole = $serviceRole;
    }

    public function index(Request $request)
    {
        $l = '';
        $r = '';
        $a = '';
        $q = '';
        $trash = '';
        if (isset($request->l) || isset($request->r) || isset($request->a) || isset($request->q) || isset($request->is_trash)) {
            $l = '?l='.$request->l;
            $r = '&r='.$request->r;
            $a = '&a='.$request->a;
            $q = '&q='.$request->q;
            $trash = '&trash='.$request->is_trash;
        }

        $data['users'] = $this->service->getUserList($request);
        $data['no'] = $data['users']->firstItem();
        $data['users']->withPath(url()->current().$l.$r.$a.$q.$trash);
        $data['roles'] = $this->serviceRole->getRoleByUserRole(false);

        return view('backend.users.index', compact('data'), [
            'title' => 'Users',
            'breadcrumbs' => [
                'Management Users' => '',
                'Users' => '',
            ]
        ]);
    }

    public function log(Request $request)
    {
        $l = '';
        $userId = '';
        $q = '';
        if (isset($request->l) || isset($request->user_id)|| isset($request->q)) {
            $l = '?l='.$request->l;
            $userId = '&user_id='.$request->user_id;
            $q = '&q='.$request->q;
        }

        if ($request->get('user_id') != null) {
            $id = $request->get('user_id');
        } else {
            $id = auth()->user()->id;
        }

        $data['logs'] = $this->service->getUserLog($request);
        $data['no'] = $data['logs']->firstItem();
        $data['logs']->withPath(url()->current().$l.$userId.$q);
        $data['user'] = $this->service->find($id);

        if ($data['user']->roles[0]->id < auth()->user()->roles[0]->id) {
            return abort(403);
        }

        return view('backend.users.log', compact('data'), [
            'title' => 'User - Log',
            'breadcrumbs' => [
                'Management Users' => '',
                'Users' => route('user.index'),
                'Log' => ''
            ]
        ]);
    }

    public function create()
    {
        $data['roles'] = $this->serviceRole->getRoleByUserRole(false);

        return view('backend.users.form', compact('data'), [
            'title' => 'User - Create',
            'breadcrumbs' => [
                'Management Users' => '',
                'User' => route('user.index'),
                'Create' => '',
            ]
        ]);
    }

    public function store(UserRequest $request)
    {
        $this->service->store($request);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'user successfully added');
    }

    public function edit($id)
    {
        $data['user'] = $this->service->find($id);
        $data['roles'] = $this->serviceRole->getRoleByUserRole(false);

        if (($data['user']->roles[0]->id <= auth()->user()->roles[0]->id ) || ($id == auth()->user()->id)) {
            return abort(403);
        }

        return view('backend.users.form', compact('data'), [
            'title' => 'User - Edit',
            'breadcrumbs' => [
                'User' => route('user.index'),
                'Edit' => '',
            ]
        ]);
    }

    public function update(UserRequest $request, $id)
    {
        $this->service->update($request, $id);

        $redir = $this->redirectForm($request);
        return $redir->with('success', 'user successfully updated');
    }

    public function activate($id)
    {
        $this->service->activate($id);

        return back()->with('success', 'activate / inactivate user successfully');
    }

    public function softDelete($id)
    {
        $delete = $this->service->softDelete($id);

        if ($delete == true) {
            return response()->json([
                'success' => 1,
                'message' => ''
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
        }
    }

    public function restore($id)
    {
        $this->service->restore($id);

        return back()->with('success', 'restore user successfully');
    }

    public function logDelete($id)
    {
        $this->service->logDelete($id);

        return response()->json([
            'success' => 1,
            'message' => ''
        ], 200);
    }

    public function redirectForm($request)
    {
        $redir = redirect()->route('user.index');
        if ($request->action == 'back') {
            $redir = back();
        }

        return $redir;
    }

    /**
     * profile
     */
    public function profile()
    {
        $data['user'] = $this->service->find(auth()->user()->id);

        return view('backend.users.profile', compact('data'), [
            'title' => 'Profile',
            'breadcrumbs' => [
                'Profile' => '',
            ]
        ]);
    }

    public function updateProfile(ProfileRequest $request)
    {
        $this->service->updateProfile($request);

        return back()->with('success', 'update profile successfully');
    }

    public function sendMailVerification()
    {
        if (config('custom.mailing.email_verification') == true) {
            $encrypt = Crypt::encrypt(auth()->user()->email);
            $data = [
                'email' => auth()->user()->email,
                'link' => route('profile.mail.verification', ['email' => $encrypt]),
            ];

            Mail::to(auth()->user()->email)->send(new VerificationMail($data));

            return back()->with('info', 'check your email for verification');
        } else {
            return back()->with('warning', 'send mail disabled, contact developer for activate mailing');
        }

    }

    public function verified($email)
    {
        $this->service->verificationEmail($email);

        return redirect()->route('profile')->with('success', 'your email is verified');
    }

    public function changePhoto(PhotoProfileRequest $request)
    {
        $this->service->changePhoto($request);

        return back()->with('success', 'change photo successfully');
    }

    public function removePhoto()
    {
        $this->service->removePhoto();

        return back()->with('success', 'remove photo successfully');
    }
}
