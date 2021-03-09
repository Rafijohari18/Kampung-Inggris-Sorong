<?php

namespace App\Services;

use App\Models\Inquiry\Inquiry;
use App\Models\Inquiry\InquiryMessage;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class InquiryService
{
    private $model, $modelMessage, $lang;

    public function __construct(
        Inquiry $model,
        InquiryMessage $modelMessage,
        LanguageService $lang
    )
    {
        $this->model = $model;
        $this->modelMessage = $modelMessage;
        $this->lang = $lang;
    }

    public function getInquiryList($request)
    {
        $query = $this->model->query();

        $query->when($request->s, function ($query, $s) {
            $query->where('publish', $s);
        })->when($request->q, function ($query, $q) {
            $query->where(function ($query) use ($q) {
                $query->where('name->'.App::getLocale(), 'like', '%'.$q.'%');
            });
        });

        $limit = 20;
        if (!empty($request->l)) {
            $limit = $request->l;
        }

        $result = $query->paginate($limit);

        return $result;
    }

    public function getMessageList($request, int $inquiryId)
    {
        $query = $this->modelMessage->query();

        $query->where('inquiry_id', $inquiryId);
        $query->when($request->s, function ($query, $s) {
            $query->where('status', $s);
        })->when($request->q, function ($query, $q) {
            $query->where(function ($query) use ($q) {
                $query->where('ip_address', 'like', '%'.$q.'%')
                    ->orWhere('name', 'like', '%'.$q.'%')
                    ->orWhere('email', 'like', '%'.$q.'%');
            });
        });

        $limit = 20;
        if (!empty($request->l)) {
            $limit = $request->l;
        }

        $result = $query->orderBy('submit_time', 'DESC')->paginate($limit);

        return $result;
    }

    public function getInquiry($request = null, $withPaginate = null, $limit = null)
    {
        $query = $this->model->query();

        $query->publish();

        if (!empty($request)) {
            $query->when($request->q, function ($query, $q) {
                $query->where(function ($query) use ($q) {
                    $query->where('name->'.App::getLocale(), 'like', '%'.$q.'%')
                        ->orWhere('body->'.App::getLocale(), 'like', '%'.$q.'%');
                });
            });
        }

        if (!empty($withPaginate)) {
            $result = $query->paginate($limit);
        } else {
            if (!empty($limit)) {
                $result = $query->limit($limit)->get();
            } else {
                $result = $query->get();
            }
        }

        return $result;
    }

    public function getMessage($request, $limit = null, $isInquiry = null)
    {
        $query = $this->modelMessage->query();

        if (!empty($isInquiry)) {
            $query->where('inquiry_id', $isInquiry);
        }

        $result = $query->orderBy('status', 'ASC')->orderBy('submit_time')->paginate($limit);

        return $result;
    }

    public function countInquiry()
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

    public function findMessage(int $id)
    {
        return $this->modelMessage->findOrFail($id);
    }

    public function findBySlug($slug)
    {
        $query = $this->model->query();

        $query->where('slug', $slug);

        $result = $query->first();

        return $result;
    }

    public function store($request)
    {
        $inquiry = new Inquiry;
        $this->setField($request, $inquiry);

        File::makeDirectory(resource_path('views/backend/inquiries/detail'), $mode = 0777, true, true);
        File::makeDirectory(resource_path('views/frontend/inquiries'), $mode = 0777, true, true);
        $path = resource_path('views/backend/inquiries/detail/'.Str::slug($request->slug, '-').'.blade.php');
        File::put($path, '');
        $path2 = resource_path('views/frontend/inquiries/'.Str::slug($request->slug, '-').'.blade.php');
        File::put($path2, '');

        $inquiry->created_by = auth()->user()->id;
        $inquiry->save();

        return $inquiry;
    }

    public function update($request, int $id)
    {
        $inquiry = $this->find($id);
        $this->setField($request, $inquiry);

        if ($request->slug != $request->old_slug) {

            $pathOld1 = resource_path('views/backend/inquiries/detail/'.$request->old_slug.'.blade.php');
            File::delete($pathOld1);
            $pathOld2 = resource_path('views/frontend/inquiries/'.$request->old_slug.'.blade.php');
            File::delete($pathOld2);

            File::makeDirectory(resource_path('views/backend/inquiries/detail'), $mode = 0777, true, true);
            File::makeDirectory(resource_path('views/frontend/inquiries'), $mode = 0777, true, true);
            $path = resource_path('views/backend/inquiries/detail/'.Str::slug($request->slug, '-').'.blade.php');
            File::put($path, '');
            $path2 = resource_path('views/frontend/inquiries/'.Str::slug($request->slug, '-').'.blade.php');
            File::put($path2, '');
        }

        if ($inquiry->field->count() > 0) {
            $field = [];
            foreach ($inquiry->field as $key) {
                $field[$key->name] = $request->input('field_'.$key->name) ?? null;
            }
            $inquiry->custom_field = $field;
        }
        $inquiry->save();

        return $inquiry;
    }

    public function setField($request, $inquiry)
    {
        foreach ($this->lang->getAllLang() as $key => $value) {
            $name[$value->iso_codes] = ($request->input('name_'.$value->iso_codes) == null) ?
                $request->input('name_'.App::getLocale()) : $request->input('name_'.$value->iso_codes);
            $body[$value->iso_codes] = ($request->input('body_'.$value->iso_codes) == null) ?
                $request->input('body_'.App::getLocale()) : $request->input('body_'.$value->iso_codes);
            $afterBody[$value->iso_codes] = ($request->input('after_body_'.$value->iso_codes) == null) ?
                $request->input('after_body_'.App::getLocale()) : $request->input('after_body_'.$value->iso_codes);
        }

        $inquiry->slug = str_replace('...', '', Str::limit(Str::slug($request->slug, '-'), 50));
        $inquiry->name = $name;
        $inquiry->body = $body;
        $inquiry->after_body = $afterBody;
        $inquiry->publish = (bool)$request->publish;
        $inquiry->is_widget = (bool)$request->is_widget;
        $inquiry->show_form = (bool)$request->show_form;
        $inquiry->show_map = (bool)$request->show_map;
        if ((bool)$request->show_map == 1) {
            $inquiry->longitude = $request->longitude ?? null;
            $inquiry->latitude = $request->latitude ?? null;
        }
        $inquiry->banner = [
            'file_path' => str_replace(url('/storage/filemanager/'), '', $request->banner_file) ?? null,
            'title' => $request->banner_title ?? null,
            'alt' => $request->banner_alt ?? null,
        ];
        $inquiry->updated_by = auth()->user()->id;

        return $inquiry;
    }

    public function publish(int $id)
    {
        $inquiry = $this->find($id);
        $inquiry->publish = !$inquiry->publish;
        $inquiry->save();

        return $inquiry;
    }

    public function status(int $id, $status)
    {
        $inquiryMessage = $this->findMessage($id);
        $inquiryMessage->status = $status;
        $inquiryMessage->save();

        return $inquiryMessage;
    }

    public function recordViewer(int $id)
    {
        $inquiry = $this->find($id);
        $inquiry->viewer = ($inquiry->viewer+1);
        $inquiry->save();

        return $inquiry;
    }

    public function delete(int $id)
    {
        $inquiry = $this->find($id);

        if ($inquiry->message->count() == 0) {

            $pathOld1 = resource_path('views/backend/inquiries/detail/'.$inquiry->slug.'.blade.php');
            File::delete($pathOld1);
            $pathOld2 = resource_path('views/frontend/inquiries/'.$inquiry->slug.'.blade.php');
            File::delete($pathOld2);

            $inquiry->delete();

            return true;

        } else {
            return false;
        }

    }

    public function deleteMessage(int $id)
    {
        $inquiryMessage = $this->findMessage($id);
        $inquiryMessage->delete();

        return true;
    }

    public function sendMessageContact($request, int $inquiryId)
    {
        $message = new InquiryMessage;
        $message->inquiry_id = $inquiryId;
        $message->ip_address = $request->ip();
        $message->name = $request->name ?? null;
        $message->email = $request->email ?? null;
        $message->custom_field = [
            'subject' => $request->subject ?? null,
            'message' => $request->message ?? null,
        ];
        $message->submit_time = now();
        $message->save();

        return $message;
    }
}
