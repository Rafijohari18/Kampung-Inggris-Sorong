<?php

namespace App\Services\Users;

use App\Models\Users\Log;
use App\Models\Users\User;
use App\Models\Users\UserInformation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserService
{
    private $model, $modelInfo, $modelLog;

    public function __construct(
        User $model,
        UserInformation $modelInfo,
        Log $modelLog
    )
    {
        $this->model = $model;
        $this->modelInfo = $modelInfo;
        $this->modelLog = $modelLog;
    }

    public function getUserList($request)
    {
        $query = $this->model->query();

        if ($request->get('is_trash') == 'yes') {
            $query->onlyTrashed();
        }

        $query->when($request->r, function ($query, $r) {
            return $query->whereHas('roles', function ($query) use ($r) {
                $query->where('id', $r);
            });
        })->when($request->a, function ($query, $a) {
            $query->where('active', $a);
        })->when($request->q, function ($query, $q) {
            $query->where(function ($query) use ($q) {
                $query->where('name', 'like', '%'.$q.'%')
                ->orWhere('email', 'like', '%'.$q.'%')
                ->orWhere('username', 'like', '%'.$q.'%');
            });
        });
        if (!auth()->user()->hasRole('super|support')) {
            $query->whereHas('roles', function ($query) {
                $query->whereNotIn('id', [1, 2]);
            });
        }
        $query->with('roles');

        $limit = 20;
        if (!empty($request->l)) {
            $limit = $request->l;
        }

        $result = $query->orderBy('id', 'ASC')->paginate($limit);

        return $result;
    }

    public function getUserActive($type, array $byRole = null)
    {
        $query = $this->model->query();

        if (!empty($byRole)) {
            $query->whereHas('roles', function ($query) use ($byRole) {
                $query->whereIn('id', $byRole);
            });
        }

        $query->active();
        $query->verified();
        $plucked = $query->pluck($type);
        $plucked->all();

        return $plucked;
    }

    public function getUserLog($request, $userId = null)
    {
        if ($userId != null) {
            $id = $userId;
        } else {
            $id = auth()->user()->id;
        }

        $query = $this->modelLog->query();

        $query->where('creator_id', $id);
        $query->when($request->q, function ($query, $q) {
            $query->where(function ($queryA) use ($q) {
                $queryA->whereHas('creator', function (Builder $queryB) use ($q) {
                    $queryB->where('name', 'like', '%'.$q.'%');
                });
            });
        });

        $limit = 20;
        if (!empty($request->l)) {
            $limit = $request->l;
        }

        $result = $query->orderBy('created_at', 'DESC')->paginate($limit);

        return $result;
    }

    public function countUser()
    {
        $query = $this->model->query();

        $query->active();

        $result = $query->count();

        return $result;
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function updateOrStoreInfo($request, int $userId)
    {
        $info = $this->modelInfo->updateOrCreate([
            'user_id' => $userId,
        ], [
            'user_id' => $userId,
            'general' => [
                'date_of_birthday' => $request->date_of_birthday ?? null,
                'place_of_birthday' => $request->place_of_birthday ?? null,
                'gender' => $request->gender ?? null,
                'address' => $request->address ?? null,
                'phone' => $request->phone ?? null,
                'description' => $request->description ?? null,
            ],
            'socmed' => [
                'facebook' => $request->facebook ?? null,
                'instagram' => $request->instagram ?? null,
                'twitter' => $request->twitter ?? null,
                'pinterest' => $request->pinterest ?? null,
                'linkedin' => $request->linkedin ?? null,
            ],
        ]);

        return $info;
    }

    public function store($request)
    {
        if ($request->hasFile('photo_file')) {
            $file = $request->file('photo_file');
            $replace = str_replace(' ', '-', $file->getClientOriginalName());
            $fileName = Str::random(5).'-'.$replace;

            $this->avatarsDir('put', $fileName, $file);
        }

        $user = new User($request->only(['name', 'email', 'username']));
        $user->password = Hash::make($request->password);
        $user->active = (bool)$request->active;
        $user->active_at = ($request->active == 1) ? now() : null;
        $user->profile_photo_path = [
            'filename' => !empty($request->photo_file) ? $fileName : null,
            'title' => $request->photo_title ?? null,
            'alt' => $request->photo_alt ?? null,
        ];
        $user->assignRole($request->roles);
        $user->save();

        $this->updateOrStoreInfo($request, $user->id);

        return $user;
    }

    public function update($request, int $id)
    {

        if ($request->hasFile('photo_file')) {
            $file = $request->file('photo_file');
            $replace = str_replace(' ', '-', $file->getClientOriginalName());
            $fileName = Str::random(5).'-'.$replace;

            $this->avatarsDir('put', $fileName, $file, $request->old_photo_file);
        }

        $user = $this->find($id);
        $user->fill($request->only(['name', 'email', 'username']));

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->email != $request->old_email) {
            $user->email_verified = 0;
            $user->email_verified_at = null;
        }

        $user->active = (bool)$request->active;
        $user->active_at = ($request->active == 1) ? now() : null;
        $user->profile_photo_path = [
            'filename' => !empty($request->photo_file) ? $fileName : $user->profile_photo_path['filename'],
            'title' => $request->photo_title ?? null,
            'alt' => $request->photo_alt ?? null,
        ];
        $user->assignRole($request->roles);
        $user->save();

        $this->updateOrStoreInfo($request, $user->id);

        return $user;
    }

    public function activate(int $id)
    {
        $user = $this->find($id);
        $user->active = !$user->active;
        $user->active_at = $user->active == 1 ? now() : null;
        $user->save();

        return $user;
    }

    public function softDelete(int $id)
    {
        $user = $this->find($id);
        $user->delete();

        return true;
    }

    public function permanentDelete($request, int $id)
    {
        if ($request->get('is_trash') == 'yes') {
            $user = $this->model->onlyTrashed()->where('id', $id)->first();
        } else {
            $user = $this->find($id);
        }

        if (!empty($user->profile_photo_path['filename'])) {
            $this->avatarsDir('delete', $user->profile_photo_path['filename']);
        }
        $user->info()->delete();
        $user->forceDelete();

        return true;
    }

    public function restore(int $id)
    {
        $user = $this->model->onlyTrashed()->where('id', $id);
        $user->restore();

        return $user;
    }

    public function logDelete(int $id)
    {
        $log = $this->modelLog->findOrFail($id);
        $log->delete();

        return $log;
    }

    public function avatarsDir($type, $param1 = null, $param2 = null, $param3 = null)
    {
        if ($type == 'put') {
            if (!empty($param3)) {
                Storage::delete(config('custom.images.path.photo').$param3);
            }
            Storage::put(config('custom.images.path.photo').$param1, file_get_contents($param2));
        } else {
            if (!empty($param1)) {
                Storage::delete(config('custom.images.path.photo').$param1);
            }
        }
    }

    /**
     * profile
     */
    public function updateProfile($request)
    {
        $user = $this->find(auth()->user()->id);
        $user->fill($request->only(['name', 'email', 'username']));

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->email != $request->old_email) {
            $user->email_verified = 0;
            $user->email_verified_at = null;
        }
        $user->save();

        $this->updateOrStoreInfo($request, $user->id);

        return $user;
    }

    public function changePhoto($request)
    {
        if ($request->hasFile('photo_file')) {
            $file = $request->file('photo_file');
            $replace = str_replace(' ', '-', $file->getClientOriginalName());
            $fileName = Str::random(5).'-'.$replace;

            $this->avatarsDir('put', $fileName, $file, $request->old_photo_file);
        }

        $user = $this->find(auth()->user()->id);
        $user->profile_photo_path = [
            'filename' => !empty($request->photo_file) ? $fileName : $user->profile_photo_path['filename'],
            'title' => $request->photo_title ?? null,
            'alt' => $request->photo_alt ?? null,
        ];
        $user->save();

        return $user;
    }

    public function removePhoto()
    {
        $user = $this->find(auth()->user()->id);
        $this->avatarsDir('delete', $user->profile_photo_path['filename']);
        $user->profile_photo_path = [
            'filename' => null,
            'title' => $user->profile_photo_path['title'],
            'alt' => $user->profile_photo_path['alt'],
        ];
        $user->save();

        return $user;
    }

    public function verificationEmail($email)
    {
        $decrypt = Crypt::decrypt($email);

        $user = $this->model->where('email', $decrypt)->first();
        $user->email_verified = 1;
        $user->email_verified_at = now();
        $user->save();

        return $user;
    }
}
