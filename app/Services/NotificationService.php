<?php

namespace App\Services;

use App\Models\Notification;

class NotificationService
{
    private $model;

    public function __construct(Notification $model)
    {
        $this->model = $model;
    }

    public function getNotificationList($request)
    {
        $query = $this->model->query();

        $query->when($request->r, function ($query, $r) {
            $query->where('read', $r);
        })->when($request->q, function ($query, $q) {
            $query->where(function ($query) use ($q) {
                $query->where('title', 'like', '%'.$q.'%')
                ->orWhere('content', 'like', '%'.$q.'%');
            });
        });
        $query->where('user_to', auth()->user()->id);

        $limit = 20;
        if (!empty($request->l)) {
            $limit = $request->l;
        }

        $result = $query->orderBy('created_at', 'DESC')->paginate($limit);

        return $result;
    }

    public function totalNotification($status)
    {
        $query = $this->model->query();

        $query->where('user_to', auth()->user()->id)
            ->where('read', $status);

        $result = $query->count();

        return $result;
    }

    public function latestNotification()
    {
        $query = $this->model->query();

        $query->where('user_to', auth()->user()->id);

        $result = $query->orderBy('created_at', 'DESC')
            ->limit(5)->get();

        return $result;
    }

    public function sendNotification($from = null, $to, $type, $icon, $color, $title, $content, $link)
    {
        $notification = new Notification;
        $notification->user_from = $from;
        $notification->user_to = $to;
        $notification->type = $type;
        $notification->icon = $icon;
        $notification->color = $color;
        $notification->title = $title;
        $notification->content = $content;
        $notification->link = $link;
        $notification->save();

        return $notification;
    }

    public function read($link)
    {
        return $this->model->where('user_to', auth()->user()->id)->where('link', $link)->update([
            'read' => 1,
        ]);
    }

    public function readAll()
    {
        return $this->model->where('user_to', auth()->user()->id)->update([
            'read' => 1,
        ]);
    }

    public function delete(int $id)
    {
        $notification = $this->model->find($id);
        $notification->delete();

        return true;

    }
}
