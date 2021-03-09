<?php

namespace App\Services\Master;

use App\Models\Master\Comments\Comment;
use App\Models\Master\Comments\CommentReply;

class CommentService
{
    private $model, $modelReply;

    public function __construct(
        Comment $model,
        CommentReply $modelReply
    )
    {
        $this->model = $model;
        $this->modelReply = $modelReply;
    }

    public function getCommentList($request)
    {
        $query = $this->model->query();

        $query->when($request->f, function ($query, $f) {
            $query->where('flags', $f);
        })->when($request->q, function ($query, $q) {
            $query->where(function ($query) use ($q) {
                $query->where('name', 'like', '%'.$q.'%');
            });
        });

        $limit = 20;
        if (!empty($request->l)) {
            $limit = $request->l;
        }

        $result = $query->orderBy('created_at', 'DESC')->paginate($limit);

        return $result;
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function findReply(int $id)
    {
        return $this->modelReply->findOrFail($id);
    }

    public function flags(int $id)
    {
        $comment = $this->find($id);
        $comment->flags = !$comment->flags;
        $comment->save();

        return $comment;
    }

    public function flagsReply(int $id)
    {
        $commentReply = $this->findReply($id);
        $commentReply->flags = !$commentReply->flags;
        $commentReply->save();

        return $commentReply;
    }

    public function delete(int $id)
    {
        $comment = $this->find($id);

        if ($comment->reply->count() == 0) {
            $comment->delete();

            return true;
        } else {
            return false;
        }
    }

    public function deleteReply(int $id)
    {
        $commentReply = $this->findReply($id);
        $commentReply->delete();

        return true;
    }
}
