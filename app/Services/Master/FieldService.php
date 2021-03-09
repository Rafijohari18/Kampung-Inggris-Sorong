<?php

namespace App\Services\Master;

use App\Models\Master\Field;
use Illuminate\Support\Str;

class FieldService
{
    private $model;

    public function __construct(
        Field $model
    )
    {
        $this->model = $model;
    }

    public function find(int $id)
    {
        return $this->model->findOrFail($id);
    }

    public function store($request, $model)
    {
        $properties[] = $request->properties;
        foreach ($request->label as $key => $value) {
            $field = new Field;
            $field->label = $value;
            $field->name = $this->generateField($request->name[$key]);
            $field->type = $request->type[$key];
            $field->properties = $request->properties[$key] ?? null;
            $field->fieldable()->associate($model);
            $field->save();

            return $field;
        }
    }

    public function update($request, int $id)
    {
        $field = $this->find($id);
        $field->label = $request->label;
        $field->name = $this->generateField($request->name);
        $field->type = $request->type;
        $field->properties = $request->properties;
        $field->save();

        return $field;
    }

    public function generateField($fieldName)
    {
        return Str::slug($fieldName, '_');
    }

    public function delete(int $id)
    {
        $field = $this->find($id);
        $field->delete();

        return $field;
    }
}
