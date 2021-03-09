<?php

namespace App\Services\Master;

use App\Models\Master\Tags\Tag;
use App\Models\Master\Tags\TagType;

class TagService
{
    private $model, $modelType;

    public function __construct(
        Tag $model,
        TagType $modelType
    )
    {
        $this->model = $model;
        $this->modelType = $modelType;
    }

    public function getTagsList($request)
    {
        $query = $this->model->query();

        $query->when($request->f, function ($query, $f) {
            $query->where('flags', $f);
        })->when($request->s, function ($query, $s) {
            $query->where('standar', $s);
        })->when($request->q, function ($query, $q) {
            $query->where(function ($query) use ($q) {
                $query->where('name', 'like', '%'.$q.'%');
            });
        });

        $limit = 20;
        if (!empty($request->l)) {
            $limit = $request->l;
        }

        $result = $query->orderBy('id', 'ASC')->paginate($limit);

        return $result;
    }

    public function getTags()
    {
        $query = $this->model->query();

        $query->flags();

        $result = $query->orderBy('id', 'ASC')->get();

        return $result;
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function store($request)
    {
        $tags = new Tag($request->only(['name']));
        $tags->creator_id = auth()->user()->id;
        $tags->description = $request->description ?? null;
        $tags->save();

        return $tags;
    }

    public function tagsTypeInput($request, $model)
    {
        foreach ($request->tags as $key => $value) {
            $type = new TagType;
            $type->tag_id = $value;
            $type->tagable()->associate($model);
            $type->save();
        }
    }

    public function update($request, int $id)
    {
        $tags = $this->find($id);
        $tags->fill($request->only(['name']));
        $tags->description = $request->description ?? null;
        $tags->save();

        return $tags;
    }

    public function flags(int $id)
    {
        $tags = $this->find($id);
        $tags->flags = !$tags->flags;
        $tags->save();

        return $tags;
    }

    public function standar(int $id)
    {
        $tags = $this->find($id);
        $tags->standar = !$tags->standar;
        $tags->save();

        return $tags;
    }

    public function delete(int $id)
    {
        $tags = $this->find($id);

        if ($tags->type->count() == 0) {
            $tags->delete();

            return true;
        } else {
            return false;
        }
    }
}
